<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Sengaja dikosongkan: seeder lama otomatis membuat 3 pasien contoh
     * (RM001006, RM001009, RM001012) setiap kali `php artisan db:seed`
     * dijalankan, sehingga data yang sudah dihapus akan muncul lagi.
     *
     * Kalau suatu saat butuh data contoh lagi, pakai
     * seeder-contoh-pasien/database/seeders/DatabaseSeeder.php
     */
    public function run(): void
    {
        //
    }
}
