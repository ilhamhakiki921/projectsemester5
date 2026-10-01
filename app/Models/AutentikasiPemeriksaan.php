<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutentikasiPemeriksaan extends Model
{
    protected $table = 'autentikasi_kunjungan';

    protected $fillable = [
        'kunjungan_id',
        'nama_dokter',
        'jabatan',
        'tanda_tangan',
        'waktu_autentikasi',
    ];

    protected $casts = [
        'waktu_autentikasi' => 'datetime',
    ];

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }
}
