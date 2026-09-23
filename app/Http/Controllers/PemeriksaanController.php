<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    /**
     * DATA DUMMY SEMENTARA.
     * Nanti diganti query ke database, contoh:
     * Pasien::whereHas('kunjungan', fn ($q) => $q->where('poli', $poli))->get();
     */
    protected function dataDummy()
    {
        return [
            'umum' => [
                [
                    'no_antrian'    => 'A001',
                    'no_rm'         => '00123',
                    'nama_pasien'   => 'Budi Santoso',
                    'alamat'        => 'Jl. Merdeka No. 10',
                    'status'        => 'Menunggu',
                    'nik'           => '3372xxxxxxxxxxxx',
                    'jenis_kelamin' => 'Laki-laki',
                    'tanggal_lahir' => '20-04-1990',
                    'usia'          => '36 Tahun',
                ],
                [
                    'no_antrian'    => 'A002',
                    'no_rm'         => '00124',
                    'nama_pasien'   => 'Siti Aminah',
                    'alamat'        => 'Jl. Sudirman No. 5',
                    'status'        => 'Diperiksa',
                    'nik'           => '3372yyyyyyyyyyyy',
                    'jenis_kelamin' => 'Perempuan',
                    'tanggal_lahir' => '11-08-1995',
                    'usia'          => '31 Tahun',
                ],
            ],
            'gigi' => [
                [
                    'no_antrian'    => 'B001',
                    'no_rm'         => '00201',
                    'nama_pasien'   => 'Andi Wijaya',
                    'alamat'        => 'Jl. Diponegoro No. 3',
                    'status'        => 'Menunggu',
                    'nik'           => '3372zzzzzzzzzzzz',
                    'jenis_kelamin' => 'Laki-laki',
                    'tanggal_lahir' => '02-01-1988',
                    'usia'          => '38 Tahun',
                ],
            ],
            'dalam'        => [],
            'mikrobiologi' => [],
            'fisioterapi'  => [],
        ];
    }

    public function index($poli = 'umum')
    {
        // Kalau slug tidak dikenal, fallback ke 'umum'
        if (! array_key_exists($poli, $this->daftarPoli)) {
            $poli = 'umum';
        }

        $antrian = $this->dataDummy()[$poli] ?? [];

        return view('pemeriksaan.index', [
            'poli'       => $poli,
            'namaPoli'   => $this->daftarPoli[$poli],
            'daftarPoli' => $this->daftarPoli,
            'antrian'    => $antrian,
        ]);
    }

    /**
     * Halaman Asesmen Pemeriksaan, dibuka waktu klik tombol "Periksa".
     */
    public function asesmen($poli, $no_rm)
    {
        if (! array_key_exists($poli, $this->daftarPoli)) {
            abort(404);
        }

        $antrian = $this->dataDummy()[$poli] ?? [];

        $pasien = collect($antrian)->firstWhere('no_rm', $no_rm);

        if (! $pasien) {
            abort(404, 'Data pasien tidak ditemukan di antrian poli ini.');
        }

        return view('pemeriksaan.asesmen', [
            'poli'     => $poli,
            'namaPoli' => $this->daftarPoli[$poli],
            'pasien'   => $pasien,
        ]);
    }

    /**
     * Simpan asesmen (sementara dummy, belum ke database).
     */
    public function simpanAsesmen(Request $request, $poli, $no_rm)
    {
        // ==================================================
        // Nanti bagian ini diganti simpan ke database, contoh:
        // RekamMedis::create([
        //     'no_rm' => $no_rm,
        //     'keluhan_utama' => $request->keluhan_utama,
        //     ...
        // ]);
        // ==================================================

        return redirect()
            ->route('pemeriksaan.index', $poli)
            ->with('success', 'Asesmen berhasil disimpan (sementara, belum tersambung ke database).');
    }
}
