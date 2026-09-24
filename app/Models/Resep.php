<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    protected $table = 'resep';

    protected $fillable = [
        'kunjungan_id',
        'obat_id',
        'jumlah_obat',
        'dosis_obat',
        'frekuensi',
        'tanggal_penulisan',
        'status_farmasi',
    ];

    protected $casts = [
        'tanggal_penulisan' => 'date',
    ];

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }
}
