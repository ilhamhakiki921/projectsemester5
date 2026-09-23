<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\RekamMedis;
use Illuminate\Http\Request;

class PemeriksaanController extends Controller
{
    // Daftar poli yang tersedia (ditampilkan sebagai dropdown)
    protected array $daftarPoli = [
        'umum'         => 'Poli Umum',
        'gigi'         => 'Poli Gigi',
        'dalam'        => 'Poli Dalam',
        'mikrobiologi' => 'Mikrobiologi',
        'fisioterapi'  => 'Fisioterapi',
    ];

    // Tabel antrian pemeriksaan sesuai poli yang dipilih
    public function index(Request $request)
    {
        $poli = $request->query('poli', 'umum');

        if (!array_key_exists($poli, $this->daftarPoli)) {
            $poli = 'umum';
        }

        $antrians = Kunjungan::with('pasien')
            ->where('poli', $poli)
            ->whereDate('tanggal_kunjungan', today())
            ->orderBy('no_antrian')
            ->get();

        return view('pemeriksaan.index', [
            'antrians'   => $antrians,
            'poli'       => $poli,
            'daftarPoli' => $this->daftarPoli,
        ]);
    }

    // Halaman detail pemeriksaan pasien (saat tombol "Periksa" diklik)
    public function show($id)
    {
        $antrian = Kunjungan::with(['pasien', 'rekamMedis'])->findOrFail($id);

        // Begitu dibuka oleh petugas, status kunjungan jadi "Sedang Diperiksa"
        if ($antrian->status === 'Menunggu') {
            $antrian->update(['status' => 'Sedang Diperiksa']);
        }

        $rekamMedis = $antrian->rekamMedis;

        return view('pemeriksaan.show', compact('antrian', 'rekamMedis'));
    }

    // Simpan hasil asesmen pemeriksaan
    public function simpanAsesmen(Request $request, $id)
    {
        $antrian = Kunjungan::findOrFail($id);

        $data = $request->validate([
            'keluhan_utama'              => 'nullable|string',
            'riwayat_penyakit_sekarang'  => 'nullable|string',
            'riwayat_penyakit_terdahulu' => 'nullable|string',
            'riwayat_alergi'             => 'nullable|string|max:255',
            'riwayat_pengobatan'         => 'nullable|string|max:255',
            'tb'                         => 'nullable|string|max:50',
            'td'                         => 'nullable|string|max:50',
            'lp'                         => 'nullable|string|max:50',
            'respiratory_rate'           => 'nullable|string|max:50',
            'berat_badan'                => 'nullable|string|max:50',
            'detak_jantung'              => 'nullable|string|max:50',
            'lingkar_dada'               => 'nullable|string|max:50',
            'saturasi'                   => 'nullable|string|max:50',
            'imt'                        => 'nullable|string|max:50',
            'suhu_badan'                 => 'nullable|string|max:50',
            'lingkar_kepala'             => 'nullable|string|max:50',
            'gcs'                        => 'nullable|string|max:50',
        ]);

        RekamMedis::updateOrCreate(
            ['kunjungan_id' => $antrian->id],
            $data
        );

        // Asesmen sudah lengkap -> tandai kunjungan selesai
        $antrian->update(['status' => 'Selesai']);

        return redirect()
            ->route('rekam-medis.index')
            ->with('success', 'Asesmen pemeriksaan berhasil disimpan.');
    }
}
