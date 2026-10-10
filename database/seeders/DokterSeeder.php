<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DokterSeeder extends Seeder
{
    public function run(): void
    {

        $dokter = [
            ['hendrik',  'dr. Hendrik Wahyudi, Sp.PD'],
            ['bagus',    'dr. Bagus Fitriadi, Sp.JP'],
            ['ichwanul', 'dr. Ichwanul Aminin, Sp.Rad'],
            ['devi',     'dr. Devi Anggraini P., Sp.KJ'],
            ['ratna',    'dr. Ratna Wijayanti, Sp.S'],
            ['aditya',   'dr. Aditya Iqbal Maulana, Sp.OG'],
            ['amin',     'dr. Muhammad Amin, Sp.MK, M.Ked.Klin'],
            ['denisa',   'dr. Denisa Khoirunnisa'],
            ['aisya',    'drg. Aisya Nurrachma'],
            ['veti',     'Veti Novitasari, A.Md.Ft.'],
        ];

        foreach ($dokter as [$username, $nama]) {
            User::updateOrCreate(
                ['username' => $username],
                ['nama_lengkap' => $nama, 'role' => 'dokter', 'status' => 'aktif', 'password' => 'GantiPassword123']
            );
        }
    }
}