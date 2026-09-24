<?php

namespace App\Http\Controllers;

use App\Models\Resep;

class FarmasiController extends Controller
{
    public function index()
    {
        $daftarResep = Resep::with(['obat', 'kunjungan.pasien'])
            ->latest('tanggal_penulisan')
            ->latest('id')
            ->get();

        return view('farmasi.index', [
            'daftarResep' => $daftarResep,
        ]);
    }
}
