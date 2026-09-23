<?php

namespace Database\Seeders;

use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name'     => 'Test User',
                'password' => 'password',
            ]
        );

        // Data contoh pasien + antrian pemeriksaan hari ini (Poli Umum)
        $contohPasien = [
            [
                'no_rm'          => 'RM001006',
                'nama'           => 'Budi Santoso',
                'jenis_kelamin'  => 'Laki-laki',
                'tanggal_lahir'  => '1985-04-12',
                'nik'            => '3201012001850001',
                'no_telepon'     => '081234567890',
                'alamat'         => 'Jl. Merdeka No. 12',
                'status_kunjungan' => 'Menunggu',
            ],
            [
                'no_rm'          => 'RM001009',
                'nama'           => 'Dewi Puspita',
                'jenis_kelamin'  => 'Perempuan',
                'tanggal_lahir'  => '1997-11-02',
                'nik'            => '3201014511970002',
                'no_telepon'     => '081298765432',
                'alamat'         => 'Jl. Melati No. 21',
                'status_kunjungan' => 'Menunggu',
            ],
            [
                'no_rm'          => 'RM001012',
                'nama'           => 'Andi Wijaya',
                'jenis_kelamin'  => 'Laki-laki',
                'tanggal_lahir'  => '1990-06-25',
                'nik'            => '3201012506900003',
                'no_telepon'     => '081211112222',
                'alamat'         => 'Jl. Kenanga No. 5',
                'status_kunjungan' => 'Menunggu',
            ],
        ];

        foreach ($contohPasien as $i => $data) {

            $pasien = Pasien::firstOrCreate(
                ['no_rm' => $data['no_rm']],
                [
                    'nama'          => $data['nama'],
                    'jenis_kelamin' => $data['jenis_kelamin'],
                    'tanggal_lahir' => $data['tanggal_lahir'],
                    'nik'           => $data['nik'],
                    'no_telepon'    => $data['no_telepon'],
                    'alamat'        => $data['alamat'],
                ]
            );

            Kunjungan::firstOrCreate(
                [
                    'pasien_id'         => $pasien->id,
                    'tanggal_kunjungan' => today(),
                    'poli'              => 'umum',
                ],
                [
                    'no_antrian' => $i + 1,
                    'dokter'     => 'dr. Sarah',
                    'pembayaran' => 'BPJS',
                    'status'     => $data['status_kunjungan'],
                ]
            );

        }
    }
}
