<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Status koding diagnosa (ICD-10) per rekam medis: null = belum dikoding,
     * "Terverifikasi" = kode ICD-10 sudah diperiksa petugas rekam medis.
     */
    public function up(): void
    {
        if (Schema::hasTable('rekam_medis') && ! Schema::hasColumn('rekam_medis', 'status_koding')) {
            Schema::table('rekam_medis', function (Blueprint $table) {
                $table->string('status_koding', 30)->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('rekam_medis') && Schema::hasColumn('rekam_medis', 'status_koding')) {
            Schema::table('rekam_medis', function (Blueprint $table) {
                $table->dropColumn('status_koding');
            });
        }
    }
};
