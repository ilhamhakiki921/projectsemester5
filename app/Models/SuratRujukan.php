<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratRujukan extends Model
{
    protected $table = 'surat_rujukan';

    protected $fillable = [
        'kunjungan_id',
        'jenis',
        'poli_tujuan',
        'jadwal_datang',
        'kunjungan_tujuan_id',
        'ditujukan_kepada',
        'nama_faskes',
        'anamnesa',
        'diagnosa',
        'terapi',
        'dokter_perujuk',
        'tanggal_surat',
    ];

    protected $casts = [
        'jadwal_datang' => 'date',
        'tanggal_surat' => 'date',
    ];

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }
}
