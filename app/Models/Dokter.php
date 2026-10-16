<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    protected $table = 'dokter';

    protected $fillable = ['nama', 'poli', 'jabatan', 'utama', 'status'];

    protected $casts = ['utama' => 'boolean'];

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}