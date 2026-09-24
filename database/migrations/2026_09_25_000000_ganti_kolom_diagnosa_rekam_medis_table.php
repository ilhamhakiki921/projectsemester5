<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->dropColumn('diagnosa_kerja');
        });

        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->dropColumn('kode_icd10');
        });

        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->dropColumn('diagnosa_banding');
        });

        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->string('diagnosis_utama')->nullable();
        });

        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->string('diagnosis_sekunder')->nullable();
        });

        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->string('tindakan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->dropColumn(['diagnosis_utama', 'diagnosis_sekunder', 'tindakan']);
        });

        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->string('diagnosa_kerja')->nullable();
            $table->string('kode_icd10')->nullable();
            $table->text('diagnosa_banding')->nullable();
        });
    }
};
