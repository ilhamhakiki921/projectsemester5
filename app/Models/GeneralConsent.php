<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralConsent extends Model
{
    protected $table = 'general_consent';

    protected $fillable = [
        'pasien_id',
        'pemberi_persetujuan',
        'nama_penyetuju',
        'hubungan',
        'setuju',
        'disetujui_pada',
        'pendamping',
        'saksi',
        'petugas_pencatat',
        'pernyataan_setuju',
        'data_form',
        'ttd_petugas',
        'ttd_penanggung_jawab',
        'ttd_saksi',
    ];

    protected $casts = [
        'data_form'      => 'array',
        'setuju'         => 'boolean',
        'disetujui_pada' => 'datetime',
        'pernyataan_setuju' => 'array',
    ];

    /** Kolom tambahan (perlu migrasi 2026_10_08) */
    public const KOLOM_TAMBAHAN = ['pendamping', 'saksi', 'petugas_pencatat', 'pernyataan_setuju'];

    /**
     * Isi pernyataan General Consent: [teks, sifat].
     * Sifat "Wajib" harus disetujui; "Opsional" boleh tidak disetujui.
     */
    public const PERNYATAAN = [
        ['Saya menyetujui pemeriksaan, perawatan, dan pengobatan di klinik ini sesuai indikasi medis oleh dokter dan tenaga kesehatan yang bertugas.', 'Wajib'],
        ['Saya telah mendapat penjelasan mengenai hak dan kewajiban saya sebagai pasien.', 'Wajib'],
        ['Saya bersedia mematuhi tata tertib dan peraturan yang berlaku di klinik.', 'Wajib'],
        ['Saya telah mendapat informasi tentang tarif dan sistem pembayaran pelayanan (umum, BPJS Kesehatan, atau asuransi lain).', 'Wajib'],
        ['Saya memahami bahwa informasi medis saya bersifat rahasia dan hanya dibuka sesuai ketentuan peraturan perundang-undangan.', 'Wajib'],
        ['Saya menyetujui informasi medis saya diberikan kepada penjamin pembiayaan (BPJS Kesehatan/asuransi) untuk keperluan klaim.', 'Opsional'],
        ['Saya menyetujui data saya dimanfaatkan untuk pendidikan, penelitian, dan pelaporan dengan identitas dirahasiakan.', 'Opsional'],
        ['Saya menyetujui informasi kondisi saya disampaikan kepada keluarga/pendamping yang saya tunjuk di bawah ini.', 'Opsional'],
    ];

    /** Nomor urut pernyataan yang sifatnya Wajib. */
    public static function indeksWajib(): array
    {
        $hasil = [];
        foreach (self::PERNYATAAN as $i => $row) {
            if ($row[1] === 'Wajib') {
                $hasil[] = $i;
            }
        }

        return $hasil;
    }

    /** Pilihan hubungan penandatangan dengan pasien. */
    public const HUBUNGAN = [
        'Pasien sendiri', 'Suami', 'Istri', 'Orang Tua', 'Anak',
        'Saudara Kandung', 'Wali', 'Lain-lain',
    ];

    /** Nilai lama / variasi penulisan -> pilihan baku di atas (supaya dropdown konsisten). */
    public const HUBUNGAN_ALIAS = [
        'ayah' => 'Orang Tua', 'ibu' => 'Orang Tua', 'orang tua' => 'Orang Tua', 'orangtua' => 'Orang Tua',
        'saudara kandung' => 'Saudara Kandung', 'saudara' => 'Saudara Kandung',
        'wali hukum' => 'Wali', 'wali' => 'Wali',
        'lainnya' => 'Lain-lain', 'lain-lain' => 'Lain-lain', 'lain lain' => 'Lain-lain',
        'pasien sendiri' => 'Pasien sendiri', 'pasien' => 'Pasien sendiri',
        'suami' => 'Suami', 'istri' => 'Istri', 'anak' => 'Anak',
    ];

    /** Samakan nilai hubungan tersimpan dengan salah satu pilihan baku (bila bisa). */
    public static function normalisasiHubungan(?string $nilai): ?string
    {
        if ($nilai === null || trim($nilai) === '') {
            return $nilai;
        }

        return self::HUBUNGAN_ALIAS[mb_strtolower(trim($nilai))] ?? $nilai;
    }

    /** Nomor urut pernyataan yang disetujui (untuk data lama = semua bila setuju). */
    public function pernyataanDisetujui(): array
    {
        if (is_array($this->pernyataan_setuju)) {
            return array_map('intval', $this->pernyataan_setuju);
        }

        return $this->setuju ? array_keys(self::PERNYATAAN) : [];
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }
}
