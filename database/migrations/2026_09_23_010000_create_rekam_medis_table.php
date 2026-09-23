<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekam_medis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kunjungan_id')
                  ->unique()
                  ->constrained('kunjungan')
                  ->cascadeOnDelete();

            // Asesmen
            $table->text('keluhan_utama')->nullable();
            $table->text('riwayat_penyakit_sekarang')->nullable();
            $table->text('riwayat_penyakit_terdahulu')->nullable();
            $table->string('riwayat_alergi')->nullable();
            $table->string('riwayat_pengobatan')->nullable();

            // Tanda Vital
            $table->string('tb')->nullable();
            $table->string('td')->nullable();
            $table->string('lp')->nullable();
            $table->string('respiratory_rate')->nullable();
            $table->string('berat_badan')->nullable();
            $table->string('detak_jantung')->nullable();
            $table->string('lingkar_dada')->nullable();
            $table->string('saturasi')->nullable();
            $table->string('imt')->nullable();
            $table->string('suhu_badan')->nullable();
            $table->string('lingkar_kepala')->nullable();
            $table->string('gcs')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekam_medis');
    }
};
