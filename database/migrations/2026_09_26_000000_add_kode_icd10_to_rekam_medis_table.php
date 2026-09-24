<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rekam_medis', function (Blueprint $table) {
            // Kode ICD-10 untuk diagnosis utama, dipakai oleh tabel
            // "10 Besar Penyakit" di dashboard. Boleh kosong karena
            // petugas tidak selalu tahu/mengisi kodenya.
            $table->string('kode_icd10', 20)->nullable()->after('diagnosis_utama');
        });
    }

    public function down(): void
    {
        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->dropColumn('kode_icd10');
        });
    }
};
