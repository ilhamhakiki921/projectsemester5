<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\SuratRujukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RujukanController extends Controller
{
    protected $daftarPoli = [];

    public function __construct()
    {
        $this->daftarPoli = Poli::daftar();
    }

    /** Pasien + kunjungan terbaru di poli ini (sama seperti halaman Pemeriksaan lain). */
    protected function konteks($poli, $no_rm): array
    {
        abort_unless(array_key_exists($poli, $this->daftarPoli), 404);

        $pasien = Pasien::where('no_rm', $no_rm)->first();
        abort_unless($pasien, 404, 'Data pasien tidak ditemukan.');

        $kunjungan = Kunjungan::where('pasien_id', $pasien->id)
            ->where('poli', $poli)->latest('id')->first();
        abort_unless($kunjungan, 404, 'Data kunjungan pasien tidak ditemukan di poli ini.');

        return [$pasien, $kunjungan];
    }

    /** Surat rujukan milik pasien ini (semua kunjungannya). */
    protected function suratPasien(Pasien $pasien)
    {
        return SuratRujukan::whereIn(
            'kunjungan_id',
            Kunjungan::where('pasien_id', $pasien->id)->pluck('id')
        );
    }

    public function index($poli, $no_rm)
    {
        [$pasien, $kunjungan] = $this->konteks($poli, $no_rm);
        $kunjungan->load(['rekamMedis', 'resep.obat']);

        $riwayat = $this->suratPasien($pasien)->orderBy('id')->get()
            ->map(fn ($s) => $this->format($s, $poli, $no_rm))->values();

        return view('pemeriksaan.rujukan', [
            'poli'        => $poli,
            'rm'          => $no_rm,
            'namaPoli'    => $this->daftarPoli[$poli],
            'kunjungan'   => $kunjungan,
            'pasien'      => $pasien,
            'daftarPoli'  => collect($this->daftarPoli)->except($poli)->all(),
            'resume'      => $this->resume($kunjungan),
            'dokterPerujuk' => Auth::check() ? Auth::user()->nama_lengkap : $kunjungan->dokter,
            'storeUrl'    => route('rujukan.store', [$poli, $no_rm]),
            'riwayat'     => $riwayat,
        ]);
    }

    public function store(Request $request, $poli, $no_rm)
    {
        [$pasien, $kunjungan] = $this->konteks($poli, $no_rm);

        $internal = $request->input('jenis') === 'Internal';

        $rules = [
            'jenis'          => 'required|in:Internal,Eksternal',
            'anamnesa'       => 'nullable|string|max:3000',
            'diagnosa'       => 'nullable|string|max:1000',
            'terapi'         => 'nullable|string|max:3000',
            'dokter_perujuk' => 'required|string|max:150',
        ];

        if ($internal) {
            $rules['poli_tujuan']   = 'required|in:' . collect($this->daftarPoli)->except($poli)->keys()->implode(',');
            $rules['jadwal_datang'] = 'required|date|after_or_equal:today';
        } else {
            $rules['ditujukan_kepada'] = 'required|string|max:150';
            $rules['nama_faskes']      = 'required|string|max:150';
        }

        $data = $request->validate($rules, [
            'required'               => ':attribute wajib diisi.',
            'poli_tujuan.in'         => 'Poli tujuan tidak valid.',
            'jadwal_datang.after_or_equal' => 'Jadwal datang tidak boleh sebelum hari ini.',
        ], [
            'poli_tujuan'      => 'Poli tujuan',
            'jadwal_datang'    => 'Jadwal datang',
            'ditujukan_kepada' => 'Ditujukan kepada',
            'nama_faskes'      => 'Nama rumah sakit / fasyankes',
            'dokter_perujuk'   => 'Dokter / petugas perujuk',
        ]);

        $surat = DB::transaction(function () use ($data, $internal, $kunjungan) {
            $tujuanId = null;

            // Rujukan internal: pasien otomatis terdaftar & masuk antrian poli tujuan
            if ($internal) {
                $tujuan = Kunjungan::create([
                    'pasien_id'         => $kunjungan->pasien_id,
                    'no_antrian'        => Kunjungan::nomorAntrianBerikutnya($data['poli_tujuan'], $data['jadwal_datang']),
                    'tanggal_kunjungan' => $data['jadwal_datang'],
                    'dokter'            => $kunjungan->dokter,
                    'poli'              => $data['poli_tujuan'],
                    'pembayaran'        => $kunjungan->pembayaran,
                    'status'            => 'Menunggu',
                ]);
                $tujuanId = $tujuan->id;
            }

            return SuratRujukan::create([
                'kunjungan_id'        => $kunjungan->id,
                'jenis'               => $data['jenis'],
                'poli_tujuan'         => $internal ? $data['poli_tujuan'] : null,
                'jadwal_datang'       => $internal ? $data['jadwal_datang'] : null,
                'kunjungan_tujuan_id' => $tujuanId,
                'ditujukan_kepada'    => $internal ? null : $data['ditujukan_kepada'],
                'nama_faskes'         => $internal ? null : $data['nama_faskes'],
                'anamnesa'            => $data['anamnesa'] ?? null,
                'diagnosa'            => $data['diagnosa'] ?? null,
                'terapi'              => $data['terapi'] ?? null,
                'dokter_perujuk'      => $data['dokter_perujuk'],
                'tanggal_surat'       => now()->toDateString(),
            ]);
        });

        return response()->json($this->format($surat, $poli, $no_rm), 201);
    }

    public function destroy($poli, $no_rm, $id)
    {
        [$pasien] = $this->konteks($poli, $no_rm);
        $surat = $this->suratPasien($pasien)->findOrFail($id);

        DB::transaction(function () use ($surat) {
            // Batalkan antrian poli tujuan kalau belum dilayani
            if ($surat->kunjungan_tujuan_id) {
                Kunjungan::where('id', $surat->kunjungan_tujuan_id)
                    ->where('status', 'Menunggu')->delete();
            }
            $surat->delete();
        });

        return response()->json(['ok' => true]);
    }

    public function cetak($poli, $no_rm, $id)
    {
        [$pasien] = $this->konteks($poli, $no_rm);
        $surat = $this->suratPasien($pasien)->findOrFail($id);

        return view('pemeriksaan.rujukan-cetak', [
            'surat'    => $surat,
            'pasien'   => $pasien,
            'namaPoli' => $this->daftarPoli,
        ]);
    }

    // ----------------------------------------------------------

    /** Resume medis otomatis dari data Asesmen, SOAP, Diagnosa, dan Resep. */
    protected function resume(Kunjungan $kunjungan): array
    {
        $rm = $kunjungan->rekamMedis;

        $anamnesa = collect([$rm?->keluhan_utama, $rm?->riwayat_penyakit_sekarang])
            ->filter()->implode("\n");
        if ($anamnesa === '') {
            $anamnesa = (string) $rm?->subjective;
        }

        $diagnosa = (string) $rm?->diagnosis_utama;
        if ($diagnosa !== '' && $rm?->kode_icd10) {
            $diagnosa .= ' (' . $rm->kode_icd10 . ')';
        }

        $terapi = $kunjungan->resep->map(function ($r) {
            return '- ' . trim(($r->obat->nama_obat ?? 'Obat') . ' ' . $r->jumlah_obat . ' | ' . $r->dosis_obat . ' | ' . $r->frekuensi);
        })->all();
        if ($rm?->tindakan) {
            $terapi[] = 'Tindakan: ' . $rm->tindakan;
        }

        return [
            'anamnesa' => $anamnesa,
            'diagnosa' => $diagnosa,
            'terapi'   => implode("\n", $terapi),
        ];
    }

    protected function format(SuratRujukan $s, $poli, $no_rm): array
    {
        $internal = $s->jenis === 'Internal';

        return [
            'id'        => $s->id,
            'jenis'     => $s->jenis,
            'tujuan'    => $internal ? ($this->daftarPoli[$s->poli_tujuan] ?? $s->poli_tujuan) : $s->nama_faskes,
            'dokter'    => $s->dokter_perujuk,
            'tgl_surat' => $s->tanggal_surat->format('d-m-Y'),
            'jadwal'    => $s->jadwal_datang ? $s->jadwal_datang->format('d-m-Y') : '-',
            'cetak_url' => route('rujukan.cetak', [$poli, $no_rm, $s->id]),
            'hapus_url' => route('rujukan.destroy', [$poli, $no_rm, $s->id]),
        ];
    }
}
