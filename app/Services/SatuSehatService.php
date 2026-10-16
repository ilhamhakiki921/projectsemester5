<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class SatuSehatService
{
    protected function cfg(string $key): ?string
    {
        return config("services.satusehat.$key");
    }

    public function token(): string
    {
        return Cache::remember('satusehat_token', 3000, function () {
            $res = Http::asForm()->post($this->cfg('auth_url') . '/accesstoken?grant_type=client_credentials', [
                'client_id'     => $this->cfg('client_id'),
                'client_secret' => $this->cfg('client_secret'),
            ]);

            if ($res->failed() || !$res->json('access_token')) {
                throw new RuntimeException('Gagal ambil token SATUSEHAT: ' . $res->body());
            }

            return $res->json('access_token');
        });
    }

    protected function client(): PendingRequest
    {
        return Http::withToken($this->token())
            ->baseUrl($this->cfg('base_url'))
            ->acceptJson();
    }

    public function get(string $path, array $query = []): Response
    {
        return $this->client()->get($path, $query);
    }

    public function post(string $path, array $body): Response
    {
        return $this->client()->post($path, $body);
    }

    public function organizationId(): ?string
    {
        return $this->cfg('organization_id');
    }

    public function cariPasienByNik(string $nik): ?string
{
    $res = $this->get('/Patient', [
        'identifier' => 'https://fhir.kemkes.go.id/id/nik|' . $nik,
    ]);

    if ($res->failed()) {
        throw new \RuntimeException('Gagal cari pasien SATUSEHAT: ' . $res->body());
    }

    return $res->json('entry.0.resource.id'); // null jika tidak ditemukan
}

public function cariPractitionerByNik(string $nik): ?string
{
    $res = $this->get('/Practitioner', [
        'identifier' => 'https://fhir.kemkes.go.id/id/nik|' . $nik,
    ]);

    if ($res->failed()) {
        throw new \RuntimeException('Gagal cari dokter SATUSEHAT: ' . $res->body());
    }

    return $res->json('entry.0.resource.id');
}

public function buatLocation(string $kode, string $nama): string
{
    $org = $this->organizationId();

    $res = $this->post('/Location', [
        'resourceType' => 'Location',
        'identifier' => [[
            'system' => "http://sys-ids.kemkes.go.id/location/{$org}",
            'value'  => $kode,
        ]],
        'status' => 'active',
        'name'   => $nama,
        'mode'   => 'instance',
        'physicalType' => ['coding' => [[
            'system'  => 'http://terminology.hl7.org/CodeSystem/location-physical-type',
            'code'    => 'ro',
            'display' => 'Room',
        ]]],
        'managingOrganization' => ['reference' => "Organization/{$org}"],
    ]);

    if ($res->failed() || !$res->json('id')) {
        throw new \RuntimeException('Gagal buat Location SATUSEHAT: ' . $res->body());
    }

    return $res->json('id');
}

public function kirimEncounter(\App\Models\Kunjungan $kunjungan): string
{
    $kunjungan->loadMissing('pasien');
    $pasien = $kunjungan->pasien;
    $dokter = \App\Models\Dokter::where('nama', $kunjungan->dokter_tampil)->first();
    $poli   = \App\Models\Poli::all()
        ->first(fn ($p) => \App\Models\Poli::slugDari($p->nama_poli) === $kunjungan->poli);

    $wajib = [
        'IHS Number pasien' => $pasien?->ihs_number,
        'IHS Number dokter' => $dokter?->ihs_number,
    ];
    foreach ($wajib as $label => $nilai) {
        if (!$nilai) {
            throw new \RuntimeException("$label belum ada.");
        }
    }

    $org   = $this->organizationId();
    $mulai = now()->toIso8601String();

    $body = [
        'resourceType' => 'Encounter',
        'identifier'   => [[
            'system' => "http://sys-ids.kemkes.go.id/encounter/{$org}",
            'value'  => (string) $kunjungan->id,
        ]],
        'status' => 'arrived',
        'class'  => [
            'system'  => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
            'code'    => 'AMB',
            'display' => 'ambulatory',
        ],
        'subject' => [
            'reference' => "Patient/{$pasien->ihs_number}",
            'display'   => $pasien->nama,
        ],
        'participant' => [[
            'type' => [['coding' => [[
                'system'  => 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType',
                'code'    => 'ATND',
                'display' => 'attender',
            ]]]],
            'individual' => [
                'reference' => "Practitioner/{$dokter->ihs_number}",
                'display'   => $dokter->nama,
            ],
        ]],
        'period'          => ['start' => $mulai],
        'statusHistory'   => [['status' => 'arrived', 'period' => ['start' => $mulai]]],
        'serviceProvider' => ['reference' => "Organization/{$org}"],
    ];

    if ($poli?->satusehat_location_id) {
        $body['location'] = [[
            'location' => [
                'reference' => "Location/{$poli->satusehat_location_id}",
                'display'   => $poli->nama_poli,
            ],
        ]];
    }

    $res = $this->post('/Encounter', $body);

    if ($res->failed() || !$res->json('id')) {
        throw new \RuntimeException('Gagal kirim Encounter: ' . $res->body());
    }

    $kunjungan->update(['satusehat_encounter_id' => $res->json('id')]);

    return $res->json('id');
}

public function kirimCondition(\App\Models\Kunjungan $kunjungan): string
{
    $kunjungan->loadMissing('pasien', 'rekamMedis');
    $pasien = $kunjungan->pasien;
    $rekam  = $kunjungan->rekamMedis;

    $wajib = [
        'IHS Number pasien'       => $pasien?->ihs_number,
        'Encounter ID kunjungan'  => $kunjungan->satusehat_encounter_id,
        'Kode ICD-10 diagnosis'   => $rekam?->kode_icd10,
    ];
    foreach ($wajib as $label => $nilai) {
        if (!$nilai) {
            throw new \RuntimeException("$label belum ada.");
        }
    }

    $kode    = trim($rekam->kode_icd10);
    $display = \App\Models\Icd10::where('code', $kode)->value('title') ?? $rekam->diagnosis_utama;
    $waktu   = now()->toIso8601String();

    $res = $this->post('/Condition', [
        'resourceType'   => 'Condition',
        'clinicalStatus' => ['coding' => [[
            'system'  => 'http://terminology.hl7.org/CodeSystem/condition-clinical',
            'code'    => 'active',
            'display' => 'Active',
        ]]],
        'category' => [['coding' => [[
            'system'  => 'http://terminology.hl7.org/CodeSystem/condition-category',
            'code'    => 'encounter-diagnosis',
            'display' => 'Encounter Diagnosis',
        ]]]],
        'code' => ['coding' => [[
            'system'  => 'http://hl7.org/fhir/sid/icd-10',
            'code'    => $kode,
            'display' => $display,
        ]]],
        'subject' => [
            'reference' => "Patient/{$pasien->ihs_number}",
            'display'   => $pasien->nama,
        ],
        'encounter'    => ['reference' => "Encounter/{$kunjungan->satusehat_encounter_id}"],
        'onsetDateTime'  => $waktu,
        'recordedDate'   => $waktu,
    ]);

    if ($res->failed() || !$res->json('id')) {
        throw new \RuntimeException('Gagal kirim Condition: ' . $res->body());
    }

    $rekam->update(['satusehat_condition_id' => $res->json('id')]);

    return $res->json('id');
}

}