<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,   // akun admin (admin / admin123) & perawat (perawat / perawat123)
            ObatSeeder::class,   // data obat awal
        ]);
    }
}