<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralConsent extends Model
{
    protected $table = 'general_consent';

    protected $fillable = [
        'pasien_id',
        'pemberi_persetujuan',
        'nama_penyetuju',
        'hubungan',
        'setuju',
        'disetujui_pada',
    ];

    protected $casts = [
        'setuju'         => 'boolean',
        'disetujui_pada' => 'datetime',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }
}
