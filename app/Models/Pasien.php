<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'pasiens';

    protected $primaryKey = 'No_RM';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'Nama_Lengkap',
        'NIK',
        'Alamat',
        'Jenis_Kelamin',
        'Tanggal_Lahir',
        'Nomor_Telepon',
        'Tanggal_Kunjungan',
        'Poli_Tujuan',
        'Nama_Dokter',
    ];
}