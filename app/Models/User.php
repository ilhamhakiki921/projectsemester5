<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * Login sistem ini memakai kolom `username` (bukan email), lihat
     * App\Http\Controllers\Auth\LoginController::username().
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_lengkap',
        'username',
        'email',
        'password',
        'role',
        'status',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Apakah akun ini Petugas Rekam Medis (role `rm`).
     */
    public function isRm(): bool
    {
        return $this->role === 'rm';
    }

    /**
     * Apakah akun ini Administrator (role `admin`): akses penuh ke seluruh sistem.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Boleh mengubah koding & mencetak di halaman Rekam Medis
     * (Petugas Rekam Medis dan Administrator).
     */
    public function bisaKelolaRekamMedis(): bool
    {
        return $this->isRm() || $this->isAdmin();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // Password otomatis di-hash setiap kali diisi (create/update),
            // jadi di kode lain cukup isi 'password' => 'plain-text-nya'.
            'password' => 'hashed',
        ];
    }
}
