<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\Request;

class PemeriksaanController extends Controller
{
    // Daftar poli yang tersedia (ditampilkan sebagai tab)
    protected array $daftarPoli = [
        'umum' => 'Poli Umum',
        'gigi' => 'Poli Gigi',
        'anak' => 'Poli Anak',
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
}
