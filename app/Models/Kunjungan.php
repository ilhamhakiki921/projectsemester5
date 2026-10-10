<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    protected $table = 'kunjungan';

    protected $fillable = [
        'pasien_id',
        'no_antrian',
        'tanggal_kunjungan',
        'dokter',
        'poli',
        'pembayaran',
        'status',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
    ];

    /**
     * Nomor antrian berikutnya untuk poli & tanggal tertentu.
     * Nomor dihitung per poli per hari: kunjungan pertama = 1, dst.
     */
    public static function nomorAntrianBerikutnya($poli, $tanggal): int
    {
        $terakhir = static::where('poli', $poli)
            ->whereDate('tanggal_kunjungan', $tanggal)
            ->max('no_antrian');

        return ((int) $terakhir) + 1;
    }

    /**
     * Nama dokter untuk ditampilkan/diisi otomatis di form pemeriksaan.
     * Memakai dokter yang diinput saat pendaftaran; kalau kosong atau masih berisi
     * "Dokter Jaga" (nilai cadangan lama), dipakai dokter bawaan poli (config/dokter.php).
     */
    public function getDokterTampilAttribute(): string
    {
        $dokter = trim((string) $this->dokter);

        if ($dokter !== '' && strcasecmp($dokter, 'Dokter Jaga') !== 0) {
            return $dokter;
        }

        return (string) config('dokter.daftar.' . $this->poli . '.0', $dokter);
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function rekamMedis()
    {
        return $this->hasOne(RekamMedis::class);
    }

    public function informedConsent()
    {
        return $this->hasOne(InformedConsent::class);
    }

    public function resep()
    {
        return $this->hasMany(Resep::class);
    }
}
