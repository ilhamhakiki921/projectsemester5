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
    ];

    protected $casts = [
        'setuju'         => 'boolean',
        'disetujui_pada' => 'datetime',
        'pernyataan_setuju' => 'array',
    ];

    /** Kolom tambahan (perlu migrasi 2026_10_08) */
    public const KOLOM_TAMBAHAN = ['pendamping', 'saksi', 'petugas_pencatat', 'pernyataan_setuju'];

    /** Isi pernyataan General Consent (sama dengan yang tampil saat pendaftaran). */
    public const PERNYATAAN = [
        ['Persetujuan pelayanan kesehatan', 'Saya menyetujui pemeriksaan, perawatan, dan pengobatan di klinik ini sesuai indikasi medis oleh dokter dan tenaga kesehatan yang bertugas.'],
        ['Hak dan kewajiban pasien', 'Saya telah mendapat penjelasan mengenai hak dan kewajiban saya sebagai pasien.'],
        ['Tata tertib', 'Saya bersedia mematuhi tata tertib dan peraturan yang berlaku di klinik.'],
        ['Kerahasiaan informasi medis', 'Saya memahami bahwa data dan rekam medis saya dijaga kerahasiaannya dan hanya dibuka untuk kepentingan pelayanan, sesuai peraturan perundang-undangan.'],
        ['Pelepasan informasi', 'Saya mengizinkan informasi yang diperlukan dibuka kepada pihak penjamin pembayaran (BPJS/asuransi) untuk keperluan klaim, serta kepada tenaga kesehatan yang terlibat dalam perawatan saya.'],
        ['Informasi biaya', 'Saya telah mendapat informasi tentang biaya pelayanan dan bersedia menyelesaikan pembayaran sesuai cara pembayaran yang dipilih.'],
        ['Barang berharga', 'Saya memahami bahwa barang berharga yang dibawa menjadi tanggung jawab saya sendiri.'],
        ['Tindakan khusus', 'Saya memahami bahwa tindakan tertentu yang berisiko memerlukan persetujuan tindakan (informed consent) tersendiri.'],
    ];

    /** Pilihan hubungan penandatangan dengan pasien. */
    public const HUBUNGAN = [
        'Pasien sendiri', 'Suami', 'Istri', 'Ayah', 'Ibu', 'Anak',
        'Saudara kandung', 'Kakek/Nenek', 'Wali hukum', 'Lainnya',
    ];

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
