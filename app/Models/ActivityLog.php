<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Throwable;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    public $timestamps = false;

    /** Daftar aksi yang dipakai di Audit Trail (key = nilai kolom `aksi`). */
    public const AKSI = [
        'Login'    => 'Login',
        'Logout'   => 'Logout',
        'Input'    => 'Input',
        'Simpan'   => 'Simpan',
        'Edit'     => 'Edit',
        'Hapus'    => 'Hapus',
        'Cetak'    => 'Cetak',
        'Password' => 'Ganti/Lupa Password',
        'TTD'      => 'Tanda Tangan',
    ];

    protected $fillable = [
        'user_id', 'username', 'aksi', 'tabel', 'data_id', 'no_rm',
        'keterangan', 'data_lama', 'data_baru', 'ip_address', 'created_at',
    ];

    protected $casts = [
        'data_lama'  => 'array',
        'data_baru'  => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Catat tanda tangan (ttd) ke Audit Trail. Gambar ttd TIDAK ikut disimpan
     * di log, hanya keterangan siapa/di mana/kapan.
     */
    public static function catatTtd(
        string $keterangan,
        ?string $noRm = null,
        ?string $tabel = null,
        ?int $dataId = null,
        array $detail = []
    ): void {
        static::catat('TTD', $tabel, $dataId, $keterangan, null, array_filter($detail, fn ($v) => $v !== null && $v !== '') ?: null, $noRm);
    }

    /**
     * Catat satu baris audit trail.
     * Tidak pernah melempar error: kalau pencatatan gagal (mis. tabel belum
     * di-migrate) aplikasi tetap jalan seperti biasa.
     */
    public static function catat(
        string $aksi,
        ?string $tabel = null,
        ?int $dataId = null,
        ?string $keterangan = null,
        ?array $lama = null,
        ?array $baru = null,
        ?string $noRm = null,
        ?string $username = null
    ): void {
        try {
            $user = Auth::user();

            // Login saat ini memakai mode tanpa pengecekan (lihat LoginController),
            // jadi Auth::user() bisa kosong. Pakai nama yang disimpan di session.
            $nama = $username
                ?? $user?->username
                ?? session('audit_username')
                ?? 'admin';

            static::create([
                'user_id'    => $user?->id,
                'username'   => $nama,
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
        } catch (Throwable $e) {
            report($e);
        }
    }
}
