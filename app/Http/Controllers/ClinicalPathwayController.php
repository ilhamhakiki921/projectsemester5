<?php

namespace App\Http\Controllers;

use App\Models\ClinicalPathway;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ClinicalPathwayController extends Controller
{
    /** Tab berikutnya setelah Clinical Pathway tersimpan (ubah sesuai route Anda). */
    private const TAB_BERIKUTNYA = 'penunjang';

    private const FIELD_KUNJUNGAN = [
        'nomor', 'tanggal', 'dpjp', 'catatan',
        'diagnosis_utama', 'kode_utama', 'diagnosis_penyerta', 'kode_penyerta', 'komplikasi', 'kode_komplikasi',
        'pemeriksaan_dpjp', 'konsultasi_bagian', 'konsultasi_catatan',
        'tindakan_nama', 'tindakan_kode', 'tindakan_catatan',
        'obat_nama', 'obat_keterangan', 'edukasi_materi', 'edukasi_keterangan', 'cost',
        'solusi_rencana', 'solusi_pj', 'solusi_target', 'solusi_catatan',
    ];

    public function index(string $poli, string $rm)
    {
        $pathway = ClinicalPathway::with('kunjungan')->where('no_rm', $rm)->where('poli', $poli)->first();
        $riwayat = ClinicalPathway::withCount('kunjungan as terisi')->latest('updated_at')->get();

        // TODO: ambil data pasien dari model pasien Anda, mis. Pasien::where('no_rm', $rm)->first()
        $pasien = null;

        return view('pemeriksaan.clinical-pathway', compact('pathway', 'riwayat', 'pasien', 'poli', 'rm'));
    }

    public function store(Request $request, string $poli, string $rm)
    {
        $data = $request->validate([
            'nama_pasien' => 'nullable|string|max:150',
            'rencana_kunjungan' => 'required|integer|min:1|max:12',
            'interval_kunjungan' => 'nullable|string|max:50',
            'satuan_interval' => 'nullable|in:Hari,Minggu,Bulan',
            'diagnosis' => 'required|string|max:255',
            'kode_icd10' => 'required|string|max:20',
            'kunjungan' => 'nullable|array',
            'kunjungan.*.tanggal' => 'nullable|date',
            'kunjungan.*.solusi_target' => 'nullable|date',
        ]);

        // Ambil hanya kunjungan yang ada isinya
        $rows = collect($request->input('kunjungan', []))
            ->map(fn ($r) => Arr::only($r, self::FIELD_KUNJUNGAN))
            ->filter(fn ($r) => collect($r)->contains(fn ($v) => filled($v)));

        $errors = [];
        if ($rows->isEmpty()) {
            $errors['kunjungan'] = 'Isi minimal satu kunjungan.';
        }
        foreach ($rows as $i => $r) {
            if (blank($r['tanggal'] ?? null)) {
                $errors["kunjungan.$i.tanggal"] = "Kunjungan $i: tanggal wajib diisi.";
            }
            if (blank($r['dpjp'] ?? null)) {
                $errors["kunjungan.$i.dpjp"] = "Kunjungan $i: Dokter wajib dipilih.";
            }
        }
        if ($errors) {
            throw ValidationException::withMessages($errors);
        }

        DB::transaction(function () use ($data, $rows, $poli, $rm) {
            $pathway = ClinicalPathway::updateOrCreate(
                ['no_rm' => $rm, 'poli' => $poli],
                [
                    'nama_pasien' => $data['nama_pasien'] ?? null,
                    'rencana_kunjungan' => $data['rencana_kunjungan'],
                    'interval_kunjungan' => $data['interval_kunjungan'] ?? null,
                    'satuan_interval' => $data['satuan_interval'] ?? null,
                    'diagnosis' => $data['diagnosis'],
                    'kode_icd10' => $data['kode_icd10'],
                    'status' => $rows->count() >= (int) $data['rencana_kunjungan'] ? 'Selesai' : 'Berjalan',
                ]
            );

            $pathway->kunjungan()->delete();
            foreach ($rows as $i => $r) {
                $pathway->kunjungan()->create($r + ['urutan' => (int) $i]);
            }
        });

        return redirect()
            ->to(url("pemeriksaan/$poli/$rm/" . self::TAB_BERIKUTNYA))
            ->with('success', 'Clinical Pathway tersimpan di Rekam Medis.');
    }
}
