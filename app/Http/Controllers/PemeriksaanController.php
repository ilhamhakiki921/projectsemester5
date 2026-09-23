<?php

namespace App\Http\Controllers;

class PemeriksaanController extends Controller
{
    /**
     * Daftar poli yang tersedia.
     * key = slug (dipakai di URL), value = nama tampilan
     */
    protected $daftarPoli = [
        'umum'         => 'Poli Umum',
        'gigi'         => 'Poli Gigi',
        'dalam'        => 'Poli Dalam',
        'mikrobiologi' => 'Mikrobiologi',
        'fisioterapi'  => 'Fisioterapi',
    ];

    public function index($poli = 'umum')
    {
        // Kalau slug tidak dikenal, fallback ke 'umum'
        if (! array_key_exists($poli, $this->daftarPoli)) {
            $poli = 'umum';
        }

        // ==================================================
        // DATA DUMMY SEMENTARA
        // Nanti bagian ini diganti query ke database, contoh:
        // $antrian = Pemeriksaan::where('poli', $poli)
        //              ->whereDate('tanggal', today())
        //              ->get();
        // ==================================================
        $dataDummy = [
            'umum' => [
                [
                    'no_antrian'  => 'A001',
                    'no_rm'       => '00123',
                    'nama_pasien' => 'Budi Santoso',
                    'alamat'      => 'Jl. Merdeka No. 10',
                    'status'      => 'Menunggu',
                ],
                [
                    'no_antrian'  => 'A002',
                    'no_rm'       => '00124',
                    'nama_pasien' => 'Siti Aminah',
                    'alamat'      => 'Jl. Sudirman No. 5',
                    'status'      => 'Diperiksa',
                ],
            ],
            'gigi' => [
                [
                    'no_antrian'  => 'B001',
                    'no_rm'       => '00201',
                    'nama_pasien' => 'Andi Wijaya',
                    'alamat'      => 'Jl. Diponegoro No. 3',
                    'status'      => 'Menunggu',
                ],
            ],
            'dalam'        => [],
            'mikrobiologi' => [],
            'fisioterapi'  => [],
        ];

        $antrian = $dataDummy[$poli] ?? [];

        return view('pemeriksaan.index', [
            'poli'       => $poli,
            'namaPoli'   => $this->daftarPoli[$poli],
            'daftarPoli' => $this->daftarPoli,
            'antrian'    => $antrian,
        ]);
    }
}
