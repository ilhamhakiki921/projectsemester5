<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    protected $table = 'poli';

    protected $fillable = [
        'kode_poli',
        'nama_poli',
        'status',
    ];

    /**
     * Kode poli otomatis berikutnya, formatnya PLI-001, PLI-002, dst.
     * Kode lain yang tidak berformat PLI-### (mis. dibuat manual) diabaikan.
     */
    public static function kodeBerikutnya(): string
    {
        $terbesar = static::pluck('kode_poli')
            ->map(fn ($k) => preg_match('/^PLI-(\d+)$/', $k, $m) ? (int) $m[1] : 0)
            ->max() ?? 0;

        return 'PLI-' . str_pad($terbesar + 1, 3, '0', STR_PAD_LEFT);
    }
}
