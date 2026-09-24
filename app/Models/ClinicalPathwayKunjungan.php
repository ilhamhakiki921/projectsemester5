<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicalPathwayKunjungan extends Model
{
    protected $guarded = [];

    protected $casts = [
        'tanggal' => 'date:Y-m-d',
        'solusi_target' => 'date:Y-m-d',
    ];

    public function clinicalPathway()
    {
        return $this->belongsTo(ClinicalPathway::class);
    }
}
