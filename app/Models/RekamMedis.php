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
        'butuh_informed_consent',

        // Diagnosa
        'diagnosis_utama',
        'kode_icd10',
        'diagnosis_sekunder',
        'tindakan',
    ];

    protected $casts = [
        'butuh_informed_consent' => 'boolean',
    ];

    /** Cache per request supaya tab di tiap halaman tidak query berulang. */
    protected static array $cacheInformedConsent = [];

    /**
     * Apakah kunjungan terbaru pasien di poli ini ditandai butuh Informed Consent
     * (pilihan "Ya" di halaman SOAP). Dipakai untuk menampilkan tab Informed Consent.
     */
    public static function butuhInformedConsent(string $poli, string $noRm): bool
    {
        $kunci = $poli . '|' . $noRm;

        if (! array_key_exists($kunci, static::$cacheInformedConsent)) {
            try {
                $kunjungan = Kunjungan::where('poli', $poli)
                    ->whereHas('pasien', fn ($q) => $q->where('no_rm', $noRm))
                    ->latest('id')
                    ->first();

                static::$cacheInformedConsent[$kunci] = (bool) optional(optional($kunjungan)->rekamMedis)->butuh_informed_consent;
            } catch (\Throwable $e) {
                // kolom belum ada (migrasi belum dijalankan) -> anggap tidak butuh
                static::$cacheInformedConsent[$kunci] = false;
            }
        }

        return static::$cacheInformedConsent[$kunci];
    }

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }
}
