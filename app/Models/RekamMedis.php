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
        'respiratory_rate',
        'berat_badan',
        'detak_jantung',
        'saturasi',
        'imt',
        'suhu_badan',
        'gcs',

        // SOAP
        'subjective',
        'objective',
        'assessment',
        'plan',

        // Diagnosa
        'diagnosis_utama',
        'kode_icd10',
        'diagnosis_sekunder',
        'tindakan',
    ];

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }
}
