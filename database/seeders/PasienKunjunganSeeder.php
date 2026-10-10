<?php

namespace Database\Seeders;

use App\Models\Kunjungan;
use App\Models\Pasien;
use Illuminate\Database\Seeder;

class PasienKunjunganSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'pasien' => [
                    'no_rm'         => '000004',
                    'nama'          => 'Budi Santoso',
                    'jenis_kelamin' => 'Laki-laki',
                    'tanggal_lahir' => '1990-04-20',
                    'nik'           => '3372010004900001',
                    'no_telepon'    => '081200000001',
                    'alamat'        => 'Jl. Merdeka No. 10',
                ],
                'kunjungan' => [
                    'no_antrian'        => 1,
                    'tanggal_kunjungan' => now()->toDateString(),
                    'dokter'            => 'dr. Ahmad Fauzi',
                    'poli'              => 'umum',
                    'pembayaran'        => 'BPJS',
                    'status'            => 'Menunggu',
                ],
            ],
            [
                'pasien' => [
                    'no_rm'         => '000005',
                    'nama'          => 'Siti Aminah',
                    'jenis_kelamin' => 'Perempuan',
                    'tanggal_lahir' => '1995-08-11',
                    'nik'           => '3372015108950002',
                    'no_telepon'    => '081200000002',
                    'alamat'        => 'Jl. Sudirman No. 5',
                ],
                'kunjungan' => [
                    'no_antrian'        => 2,
                    'tanggal_kunjungan' => now()->toDateString(),
                    'dokter'            => 'dr. Ahmad Fauzi',
                    'poli'              => 'umum',
                    'pembayaran'        => 'Umum',
                    'status'            => 'Selesai',
                ],
            ],
            [
                'pasien' => [
                    'no_rm'         => '000006',
                    'nama'          => 'Andi Wijaya',
                    'jenis_kelamin' => 'Laki-laki',
                    'tanggal_lahir' => '1988-01-02',
                    'nik'           => '3372010201880003',
                    'no_telepon'    => '081200000003',
                    'alamat'        => 'Jl. Diponegoro No. 3',
                ],
                'kunjungan' => [
                    'no_antrian'        => 1,
                    'tanggal_kunjungan' => now()->toDateString(),
                    'dokter'            => 'drg. Melati',
                    'poli'              => 'gigi',
                    'pembayaran'        => 'Umum',
                    'status'            => 'Menunggu',
                ],
            ],
        ];

        foreach ($data as $row) {
            $pasien = Pasien::updateOrCreate(
                ['no_rm' => $row['pasien']['no_rm']],
                $row['pasien']
            );

            Kunjungan::updateOrCreate(
                [
                    'pasien_id'         => $pasien->id,
                    'tanggal_kunjungan' => $row['kunjungan']['tanggal_kunjungan'],
                    'poli'              => $row['kunjungan']['poli'],
                ],
                $row['kunjungan']
            );
        }
    }
}
