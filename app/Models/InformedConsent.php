<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformedConsent extends Model
{
    protected $table = 'informed_consent';

    protected $fillable = [
        'kunjungan_id',
        'poli',
        'data',
        'lengkap',
    ];

    protected $casts = [
        // Daftar pasangan [nama_field, nilai] dari form Informed Consent
        'data'    => 'array',
        'lengkap' => 'boolean',
    ];

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }
}
