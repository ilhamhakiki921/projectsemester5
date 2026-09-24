<?php

use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Membuat akun administrator awal.
     * Username: admin | Password: admin123  (SEGERA GANTI setelah login pertama)
     */
    public function run()
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name'     => 'Administrator',
                'email'    => 'admin@siprek.test',
                'password' => bcrypt('admin123'),
            ]
        );
    }
}
