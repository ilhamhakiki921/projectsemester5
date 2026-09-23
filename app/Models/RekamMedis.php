<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
    protected $table = 'rekam_medis';

    protected $fillable = [
        'kunjungan_id',

        // Asesmen
        'keluhan_utama',
        'riwayat_penyakit_sekarang',
        'riwayat_penyakit_terdahulu',
        'riwayat_alergi',
        'riwayat_pengobatan',
        'tb',
        'td',
        'lp',
        'respiratory_rate',
        'berat_badan',
        'detak_jantung',
        'lingkar_dada',
        'saturasi',
        'imt',
        'suhu_badan',
        'lingkar_kepala',
        'gcs',

        // SOAP
        'subjective',
        'objective',
        'assessment',
        'plan',

        // Diagnosa
        'diagnosa_kerja',
        'kode_icd10',
        'diagnosa_banding',
    ];

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }
}
