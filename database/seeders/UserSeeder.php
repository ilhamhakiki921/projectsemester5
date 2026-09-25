<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Akun admin default untuk login ke sistem.
     *
     * Username : admin
     * Password : admin123
     *
     * Password otomatis di-hash lewat cast 'password' => 'hashed' pada
     * model User, jadi di sini cukup ditulis teks biasa "admin123".
     *
     * Pakai updateOrCreate supaya aman dijalankan berkali-kali (seed ulang
     * tidak akan membuat akun admin dobel / menimpa role & status kalau
     * sudah pernah diubah manual, kecuali kolom password memang mau di-reset).
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'nama_lengkap' => 'Administrator',
                'password'     => 'admin123',
                'role'         => 'admin',
                'status'       => 'aktif',
            ]
        );
    }
}
