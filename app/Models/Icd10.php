<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Icd10 extends Model
{
    protected $table = 'icd10';

    protected $fillable = ['code', 'title', 'kind', 'parent_code'];
}
