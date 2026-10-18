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
        'sistole',
        'diastole',
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
        'status_koding',
    ];

    protected $casts = [
        'butuh_informed_consent' => 'boolean',
    ];

        /** Gabungan sistole/diastole, mis. "120/80" (dipakai tampilan, PDF, resume, surat medis). */
    public function getTdAttribute(): ?string
    {
        if ($this->sistole === null || $this->diastole === null) {
            return null;
        }
        return $this->sistole . '/' . $this->diastole;
    }

    /** Cache per request supaya tab di tiap halaman tidak query berulang. */
    protected static array $cacheInformedConsent = [];

    /**
     * Apakah kunjungan terbaru pasien di poli ini ditandai butuh Informed Consent
     * (pilihan "Ya" di halaman SOAP). Dipakai untuk menampilkan tab Informed Consent.
     * Status hanya berlaku untuk kunjungan tersebut, jadi kunjungan baru selalu mulai dari "tidak".
     */
    /** Informed Consent baru tersedia untuk poli tertentu (poli lain belum dibuat). */
    public const POLI_INFORMED_CONSENT = ['gigi', 'umum'];

    public static function poliPunyaInformedConsent(?string $poli): bool
    {
        return in_array($poli, self::POLI_INFORMED_CONSENT, true);
    }

    public static function butuhInformedConsent(string $poli, string $noRm): bool
    {
        if (! static::poliPunyaInformedConsent($poli)) {
            return false;
        }

        $kunjungan = null;

        try {
            $kunjungan = Kunjungan::where('poli', $poli)
                ->whereHas('pasien', fn ($q) => $q->where('no_rm', $noRm))
                ->latest('id')
                ->first();
        } catch (\Throwable $e) {
            return false;
        }

        if (! $kunjungan) {
            return false;
        }

        $kunci = (string) $kunjungan->id;

        // Pilihan terbaru dari halaman SOAP (disimpan di session per kunjungan) didahulukan
        $dariSession = session('ic_aktif', []);
        if (array_key_exists($kunci, $dariSession)) {
            return (bool) $dariSession[$kunci];
        }

        if (! array_key_exists($kunci, static::$cacheInformedConsent)) {
            try {
                static::$cacheInformedConsent[$kunci] = (bool) optional($kunjungan->rekamMedis)->butuh_informed_consent;
            } catch (\Throwable $e) {
                // kolom belum ada (migrasi belum dijalankan) -> anggap tidak butuh
                static::$cacheInformedConsent[$kunci] = false;
            }
        }

        return static::$cacheInformedConsent[$kunci];
    }

    /** Catat pilihan Ya/Tidak Informed Consent di session, per kunjungan. */
    public static function tandaiSession(int $kunjunganId, bool $butuh): void
    {
        $data = session('ic_aktif', []);
        $data[(string) $kunjunganId] = $butuh;
        session(['ic_aktif' => $data]);
    }

    /**
     * Rekam medis yang sudah dikoding & diverifikasi dikunci: data asesmen/SOAP
     * tidak boleh diubah lagi dari halaman Berkas Rekam Medis.
     */
    public function sudahTerverifikasi(): bool
    {
        return ($this->status_koding ?? null) === 'Terverifikasi';
    }

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }
}
