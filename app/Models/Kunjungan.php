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

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function rekamMedis()
    {
        return $this->hasOne(RekamMedis::class);
    }

    // Nomor antrian berikutnya untuk poli & tanggal tertentu
    public static function nomorAntrianBerikutnya(string $poli, string $tanggal): int
    {
        $terakhir = self::where('poli', $poli)
            ->whereDate('tanggal_kunjungan', $tanggal)
            ->max('no_antrian');

        return $terakhir ? $terakhir + 1 : 1;
    }
}
