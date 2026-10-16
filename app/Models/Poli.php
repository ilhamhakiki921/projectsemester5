<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Poli extends Model
{
    protected $table = 'poli';

    protected $fillable = [
        'kode_poli',
        'nama_poli',
        'status',
        'satusehat_location_id'
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

    /** Daftar bawaan, dipakai kalau tabel poli belum ada / masih kosong. */
    public const BAWAAN = [
        'umum'         => 'Poli Umum',
        'gigi'         => 'Poli Gigi',
        'dalam'        => 'Poli Dalam',
        'mikrobiologi' => 'Mikrobiologi',
        'fisioterapi'  => 'Fisioterapi',
    ];

    /**
     * Slug poli = nama tanpa awalan "Poli ", huruf kecil.
     * "Poli Umum" -> umum, "Mikrobiologi" -> mikrobiologi, "Poli Mata" -> mata.
     * Slug inilah yang disimpan di kolom kunjungan.poli dan dipakai di URL pemeriksaan.
     */
    public static function slugDari(string $nama): string
    {
        return Str::slug(preg_replace('/^poli\s+/i', '', trim($nama)));
    }

    /**
     * Daftar poli sebagai [slug => nama], urut sesuai Data Poli.
     * $hanyaAktif = true untuk menu sidebar & pilihan pendaftaran;
     * false untuk pencarian/validasi (poli nonaktif tetap bisa dibuka datanya).
     */
    public static function daftar(bool $hanyaAktif = false): array
    {
        try {
            $query = static::orderBy('id');
            if ($hanyaAktif) {
                $query->where('status', 'aktif');
            }
            $rows = $query->get(['id', 'nama_poli']);
        } catch (\Throwable $e) {
            return self::BAWAAN;
        }

        if ($rows->isEmpty() && ! static::query()->exists()) {
            return self::BAWAAN;
        }

        $hasil = [];
        foreach ($rows as $r) {
            $slug = static::slugDari($r->nama_poli) ?: 'poli-' . $r->id;
            if (isset($hasil[$slug])) {
                $slug .= '-' . $r->id;
            }
            $hasil[$slug] = $r->nama_poli;
        }

        return $hasil;
    }
}
