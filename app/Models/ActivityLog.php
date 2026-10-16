<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    public $timestamps = false;

    protected $fillable = [
        'user_id', 'username', 'aksi', 'tabel', 'data_id', 'no_rm',
        'keterangan', 'data_lama', 'data_baru', 'ip_address', 'created_at',
    ];

    protected $casts = [
        'data_lama'  => 'array',
        'data_baru'  => 'array',
        'created_at' => 'datetime',
    ];

    public static function catat(
        string $aksi,
        ?string $tabel = null,
        ?int $dataId = null,
        ?string $keterangan = null,
        ?array $lama = null,
        ?array $baru = null,
        ?string $noRm = null
    ): void {
        $user = Auth::user();

        static::create([
            'user_id'    => $user?->id,
            'username'   => $user?->username,
            'aksi'       => $aksi,
            'tabel'      => $tabel,
            'data_id'    => $dataId,
            'no_rm'      => $noRm,
            'keterangan' => $keterangan,
            'data_lama'  => $lama,
            'data_baru'  => $baru,
            'ip_address' => request()->ip(),
            'created_at' => now(),
        ]);
    }
}