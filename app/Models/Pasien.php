<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'pasien';

    protected $fillable = [
        'no_rm',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'nik',
        'ihs_number',
        'no_telepon',
        'alamat',
        'kecamatan',
        'title', 'tempat_lahir', 'agama', 'status_pernikahan',
        'provinsi', 'kabupaten', 'kelurahan', 'negara', 'pendidikan', 'pekerjaan',
        'no_kk', 'domisili', 'nip', 'jabatan', 'divisi', 'departemen', 'sub_departemen',
        'perusahaan', 'suku', 'bahasa', 'kewarganegaraan',
        'keluarga_nama', 'keluarga_jk', 'keluarga_hubungan', 'keluarga_telepon',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function kunjungan()
    {
        return $this->hasMany(Kunjungan::class);
    }

    public function generalConsent()
    {
        return $this->hasOne(GeneralConsent::class);
    }
}