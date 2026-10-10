<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('surat_medis', function (Blueprint $t) {
            $t->id();

            // Kunjungan tempat surat dibuat. Satu kunjungan = satu surat per jenis (Sakit / Sehat).
            $t->foreignId('kunjungan_id')->constrained('kunjungan')->cascadeOnDelete();
            $t->enum('jenis', ['Sakit', 'Sehat']);

            // Nomor otomatis, contoh: SKSK/001/X/10/2026
            $t->unsignedInteger('no_urut');
            $t->string('nomor_surat');

            $t->string('alamat_pasien');

            // Surat Keterangan Sakit
            $t->unsignedSmallInteger('lama_istirahat')->nullable();
            $t->date('mulai_tanggal')->nullable();
            $t->date('sampai_tanggal')->nullable();

            // Surat Keterangan Sehat
            $t->string('keperluan')->nullable();

            // Penerbitan surat
            $t->string('tempat');
            $t->date('tanggal_surat');
            $t->string('dokter');
            $t->longText('ttd_dokter')->nullable();   // data URL PNG dari kotak tanda tangan

            $t->timestamps();

            $t->unique(['kunjungan_id', 'jenis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_medis');
    }
};
