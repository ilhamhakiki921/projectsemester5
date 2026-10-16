<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HapusDataPasien extends Command
{
    protected $signature = 'pasien:hapus-semua {--force : Lewati konfirmasi}';

    protected $description = 'Hapus SEMUA data pasien beserta kunjungan, rekam medis, resep, rujukan, consent, dll. (user, obat, poli, ICD-10, tindakan tidak disentuh)';

    // Urutan: tabel anak dulu, baru tabel induk.
    protected array $tabel = [
        'clinical_pathway_kunjungans',
        'clinical_pathways',
        'permintaan_penunjang',
        'surat_medis',
        'informed_consent',
        'autentikasi_pemeriksaan',
        'autentikasi_kunjungan',
        'surat_rujukan',
        'resep',
        'rekam_medis',
        'general_consent',
        'kunjungan',
        'pasien',
    ];

    public function handle(): int
    {
        $this->warn('Tabel berikut akan DIKOSONGKAN:');

        $baris = [];
        foreach ($this->tabel as $t) {
            $baris[] = [$t, Schema::hasTable($t) ? DB::table($t)->count() : '(tabel tidak ada)'];
        }
        $this->table(['Tabel', 'Jumlah baris'], $baris);

        $this->line('Tidak disentuh: users, obat, poli, icd10, tindakan.');
        $this->error('Penghapusan TIDAK BISA dibatalkan. Pastikan sudah backup database.');

        if (! $this->option('force')) {
            if ($this->ask('Ketik HAPUS (huruf besar) untuk melanjutkan') !== 'HAPUS') {
                $this->info('Dibatalkan. Tidak ada data yang dihapus.');
                return self::FAILURE;
            }
        }

        Schema::disableForeignKeyConstraints();
        try {
            foreach ($this->tabel as $t) {
                if (Schema::hasTable($t)) {
                    DB::table($t)->truncate(); // sekaligus reset auto-increment ke 1
                }
            }
        } finally {
            Schema::enableForeignKeyConstraints();
        }

        $this->info('Selesai. Semua data pasien sudah dihapus. Pasien baru berikutnya akan mulai dari No. RM 123456.');

        return self::SUCCESS;
    }
}
