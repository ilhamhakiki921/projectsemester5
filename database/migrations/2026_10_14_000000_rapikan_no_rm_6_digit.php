<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Merapikan No. RM pasien yang sudah ada menjadi 6 digit berurutan
 * (000001, 000002, ...) sesuai urutan pendaftaran (kolom id).
 * Tabel lain yang menyimpan no_rm ikut diperbarui.
 */
return new class extends Migration
{
    // Tabel lain yang menyimpan no_rm sebagai teks
    protected array $tabelTerkait = ['permintaan_penunjang', 'clinical_pathways'];

    public function up(): void
    {
        DB::transaction(function () {
            $pasien = DB::table('pasien')->orderBy('id')->get(['id', 'no_rm']);

            $peta = [];
            $urut = 1;
            foreach ($pasien as $p) {
                $peta[$p->no_rm] = str_pad((string) $urut++, 6, '0', STR_PAD_LEFT);
            }

            // Tahap 1: ganti ke nilai sementara supaya tidak bentrok (unique)
            foreach ($peta as $lama => $baru) {
                $this->ganti($lama, 'TMP-' . $baru);
            }

            // Tahap 2: ganti ke nomor final
            foreach ($peta as $lama => $baru) {
                $this->ganti('TMP-' . $baru, $baru);
            }
        });
    }

    protected function ganti(string $dari, string $ke): void
    {
        DB::table('pasien')->where('no_rm', $dari)->update(['no_rm' => $ke]);

        foreach ($this->tabelTerkait as $tabel) {
            if (Schema::hasTable($tabel) && Schema::hasColumn($tabel, 'no_rm')) {
                DB::table($tabel)->where('no_rm', $dari)->update(['no_rm' => $ke]);
            }
        }
    }

    public function down(): void
    {
        // Tidak bisa dikembalikan ke nomor lama.
    }
};
