<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SuratMedis extends Model
{
    protected $table = 'surat_medis';

    protected $fillable = [
        'kunjungan_id',
        'jenis',
        'no_urut',
        'nomor_surat',
        'alamat_pasien',
        'lama_istirahat',
        'mulai_tanggal',
        'sampai_tanggal',
        'keperluan',
        'tanggal_pemeriksaan',
        'hasil_pemeriksaan',
        'berat_badan',
        'tinggi_badan',
        'sistole',
        'diastole',
        'tempat',
        'tanggal_surat',
        'dokter',
        'ttd_dokter',
    ];

    protected $casts = [
        'mulai_tanggal'  => 'date',
        'sampai_tanggal' => 'date',
        'tanggal_pemeriksaan' => 'date',
        'tanggal_surat'  => 'date',
    ];

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }

    /** Gabungan sistole/diastole, mis. "120/80" (dipakai halaman cetak dan berkas). */
    public function getTekananDarahAttribute(): ?string
    {
        if ($this->sistole === null || $this->diastole === null) {
            return null;
        }
        return $this->sistole . '/' . $this->diastole;
    }

    /** Kode surat di awal nomor. */
    public static function kode(string $jenis): string
    {
        return $jenis === 'Sakit' ? 'SKSK' : 'SKSH';
    }

    /**
     * Nomor surat berikutnya. Urutan dihitung per jenis per bulan.
     * Format: SKSK/001/X/10/2026 (kode / urut / bulan romawi / bulan / tahun)
     *
     * @return array{0:int,1:string} [no_urut, nomor_surat]
     */
    public static function nomorBerikutnya(string $jenis, Carbon $tanggal): array
    {
        $urut = ((int) static::where('jenis', $jenis)
            ->whereYear('tanggal_surat', $tanggal->year)
            ->whereMonth('tanggal_surat', $tanggal->month)
            ->max('no_urut')) + 1;

        $romawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

        return [
            $urut,
            sprintf('%s/%03d/%s/%d/%d', static::kode($jenis), $urut, $romawi[$tanggal->month], $tanggal->month, $tanggal->year),
        ];
    }

    /** Angka jadi huruf (untuk lama istirahat), contoh: 3 => "tiga". */
    public static function terbilang(int $n): string
    {
        $s = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];

        return match (true) {
            $n < 12   => $s[$n],
            $n < 20   => static::terbilang($n - 10) . ' belas',
            $n < 100  => static::terbilang(intdiv($n, 10)) . ' puluh' . ($n % 10 ? ' ' . static::terbilang($n % 10) : ''),
            $n < 200  => 'seratus' . ($n > 100 ? ' ' . static::terbilang($n - 100) : ''),
            $n < 1000 => static::terbilang(intdiv($n, 100)) . ' ratus' . ($n % 100 ? ' ' . static::terbilang($n % 100) : ''),
            default   => (string) $n,
        };
    }
}
