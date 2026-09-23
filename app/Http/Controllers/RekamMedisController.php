<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;

class RekamMedisController extends Controller
{
    public function index()
    {
        $daftar = Kunjungan::with('pasien')
            ->latest('tanggal_kunjungan')
            ->latest('id')
            ->get();

        return view('rekam-medis.index', [
            'daftar' => $daftar,
        ]);
    }

    public function show(Kunjungan $kunjungan)
    {
        $kunjungan->load('pasien', 'rekamMedis');

        return view('rekam-medis.show', [
            'kunjungan' => $kunjungan,
            'pasien'    => $kunjungan->pasien,
            'rekam'     => $kunjungan->rekamMedis,
        ]);
    }
}
