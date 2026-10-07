<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\Poli;

class SuratMedisController extends Controller
{
    /**
     * Halaman Surat Medis (tab setelah Rujukan, sebelum Autentikasi) untuk semua poli.
     * Isi halaman masih kosong; desainnya menyusul.
     */
    public function index($poli, $no_rm)
    {
        $daftarPoli = Poli::daftar();
        abort_unless(array_key_exists($poli, $daftarPoli), 404);

        $pasien = Pasien::where('no_rm', $no_rm)->first();
        abort_unless($pasien, 404, 'Data pasien tidak ditemukan.');

        $kunjungan = Kunjungan::where('pasien_id', $pasien->id)
            ->where('poli', $poli)->latest('id')->first();
        abort_unless($kunjungan, 404, 'Data kunjungan pasien tidak ditemukan di poli ini.');

        return view('pemeriksaan.surat-medis', [
            'poli'      => $poli,
            'rm'        => $no_rm,
            'namaPoli'  => $daftarPoli[$poli],
            'pasien'    => $pasien,
            'kunjungan' => $kunjungan,
        ]);
    }
}
