<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'obat';

    protected $fillable = [
        'nama_obat',
        'sediaan',
    ];

    public function resep()
    {
        return $this->hasMany(Resep::class);
    }
}
