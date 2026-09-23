<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'pasien';

    protected $fillable = [
        'no_rm',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'nik',
        'no_telepon',
        'alamat',
    ];

    public function kunjungans()
    {
        return $this->hasMany(Kunjungan::class);
    }
}