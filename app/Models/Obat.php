<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'obat';

    protected $fillable = [
        'kode_obat',
        'nama_obat',   // nama saja, TANPA dosis (mis. "Paracetamol")
        'dosis',       // kekuatan, kolom sendiri (mis. "500 mg")
        'sediaan',     // Tablet, Kapsul, Sirup, dll
        'stok',
        'harga',
    ];

    protected $casts = [
        'stok'  => 'integer',
        'harga' => 'integer',
    ];

    protected static function booted(): void
    {
        // Obat yang dibuat lewat jalur mana pun (termasuk dari form Resep) selalu dapat kode.
        static::creating(function (Obat $obat) {
            if (blank($obat->kode_obat)) {
                $obat->kode_obat = static::kodeBerikutnya();
            }
            if (blank($obat->dosis)) {
                [$obat->nama_obat, $obat->dosis] = static::pisahDosis($obat->nama_obat);
            }
        });
    }

    public function resep()
    {
        return $this->hasMany(Resep::class);
    }

    /** Kode otomatis berikutnya: OBT-001, OBT-002, ... */
    public static function kodeBerikutnya(): string
    {
        $terbesar = static::pluck('kode_obat')
            ->map(fn ($k) => preg_match('/^OBT-(\d+)$/', (string) $k, $m) ? (int) $m[1] : 0)
            ->max() ?? 0;

        return 'OBT-' . str_pad($terbesar + 1, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Kalau dosis terlanjur diketik di akhir nama ("Paracetamol 500mg"),
     * pisahkan jadi [nama, dosis] = ["Paracetamol", "500 mg"].
     * Kalau tidak ada dosis di nama, dosis dikembalikan null.
     */
    public static function pisahDosis(?string $nama): array
    {
        $nama = trim((string) $nama);
        $pola = '/^(.*?)\s+(\d+(?:[.,]\d+)?\s*(?:mg|mcg|g|ml|iu)(?:\s*\/\s*\d+(?:[.,]\d+)?\s*(?:mg|mcg|g|ml))?)\s*$/i';

        if (preg_match($pola, $nama, $m) && trim($m[1]) !== '') {
            return [trim($m[1]), static::rapikanDosis($m[2])];
        }

        return [$nama, null];
    }

    /** "500mg" -> "500 mg" */
    public static function rapikanDosis(?string $dosis): ?string
    {
        $dosis = trim((string) $dosis);

        return $dosis === '' ? null : trim(preg_replace('/(\d)\s*([a-z])/i', '$1 $2', $dosis));
    }

    /** habis | menipis | aman -- dipakai untuk warna bar stok */
    public function getStatusStokAttribute(): string
    {
        if ($this->stok <= 0) {
            return 'habis';
        }

        return $this->stok <= 20 ? 'menipis' : 'aman';
    }

    /** Lebar bar stok dalam persen (skala 1000 = penuh, minimal 4% kalau masih ada stok) */
    public function getPersenStokAttribute(): int
    {
        if ($this->stok <= 0) {
            return 0;
        }

        return (int) max(4, min(100, round($this->stok / 1000 * 100)));
    }
}
