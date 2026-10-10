<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('surat_rujukan', 'tanda_tangan')) {
            Schema::table('surat_rujukan', function (Blueprint $t) {
                // Gambar tanda tangan dokter/petugas perujuk (PNG data URL base64)
                $t->longText('tanda_tangan')->nullable()->after('dokter_perujuk');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('surat_rujukan', 'tanda_tangan')) {
            Schema::table('surat_rujukan', function (Blueprint $t) {
                $t->dropColumn('tanda_tangan');
            });
        }
    }
};
