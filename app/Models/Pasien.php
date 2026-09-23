<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'pasien';

    public function kunjungans()
    {
        return $this->hasMany(Kunjungan::class);
    }
}