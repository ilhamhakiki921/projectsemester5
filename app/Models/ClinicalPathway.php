<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicalPathway extends Model
{
    protected $guarded = [];

    public function kunjungan()
    {
        return $this->hasMany(ClinicalPathwayKunjungan::class)->orderBy('urutan');
    }
}
