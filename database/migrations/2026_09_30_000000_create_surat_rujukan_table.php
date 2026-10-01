<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('surat_rujukan', function (Blueprint $t) {
            $t->id();

            // Kunjungan asal (tempat surat rujukan dibuat)
            $t->foreignId('kunjungan_id')->constrained('kunjungan')->cascadeOnDelete();

            $t->enum('jenis', ['Internal', 'Eksternal']);

            // Internal: poli tujuan + jadwal datang
            $t->string('poli_tujuan')->nullable();
            $t->date('jadwal_datang')->nullable();
            // Kunjungan yang otomatis dibuat di poli tujuan (rujukan internal)
            $t->unsignedBigInteger('kunjungan_tujuan_id')->nullable();

            // Eksternal: dokter jaga/poli tujuan + nama RS/fasyankes
            $t->string('ditujukan_kepada')->nullable();
            $t->string('nama_faskes')->nullable();

            // Resume medis
            $t->text('anamnesa')->nullable();
            $t->text('diagnosa')->nullable();
            $t->text('terapi')->nullable();
            $t->string('dokter_perujuk');

            $t->date('tanggal_surat');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_rujukan');
    }
};
