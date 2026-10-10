<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Isian tambahan Surat Keterangan Sehat:
     * tanggal pemeriksaan, hasil pemeriksaan, dan keterangan fisik (berat, tinggi, tekanan darah).
     * Kolom "keperluan" yang sudah ada dipakai untuk isian "Dipergunakan Untuk".
     */
    public function up(): void
    {
        Schema::table('surat_medis', function (Blueprint $t) {
            if (! Schema::hasColumn('surat_medis', 'tanggal_pemeriksaan')) {
                $t->date('tanggal_pemeriksaan')->nullable()->after('keperluan');
            }
            if (! Schema::hasColumn('surat_medis', 'hasil_pemeriksaan')) {
                $t->text('hasil_pemeriksaan')->nullable()->after('tanggal_pemeriksaan');
            }
            if (! Schema::hasColumn('surat_medis', 'berat_badan')) {
                $t->decimal('berat_badan', 5, 1)->nullable()->after('hasil_pemeriksaan');
            }
            if (! Schema::hasColumn('surat_medis', 'tinggi_badan')) {
                $t->decimal('tinggi_badan', 5, 1)->nullable()->after('berat_badan');
            }
            if (! Schema::hasColumn('surat_medis', 'tekanan_darah')) {
                $t->string('tekanan_darah', 15)->nullable()->after('tinggi_badan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('surat_medis', function (Blueprint $t) {
            $t->dropColumn(['tanggal_pemeriksaan', 'hasil_pemeriksaan', 'berat_badan', 'tinggi_badan', 'tekanan_darah']);
        });
    }
};
