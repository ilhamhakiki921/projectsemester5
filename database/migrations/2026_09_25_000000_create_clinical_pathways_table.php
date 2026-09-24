<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clinical_pathways', function (Blueprint $t) {
            $t->id();
            $t->string('no_rm')->index();
            $t->string('poli')->default('umum');
            $t->string('nama_pasien')->nullable();
            $t->unsignedTinyInteger('rencana_kunjungan');
            $t->string('interval_kunjungan')->nullable();
            $t->string('satuan_interval')->nullable();
            $t->string('diagnosis');
            $t->string('kode_icd10', 20);
            $t->string('status')->default('Berjalan');
            $t->timestamps();
            $t->unique(['no_rm', 'poli']);
        });

        Schema::create('clinical_pathway_kunjungans', function (Blueprint $t) {
            $t->id();
            $t->foreignId('clinical_pathway_id')->constrained()->cascadeOnDelete();
            $t->unsignedTinyInteger('urutan');
            $t->string('nomor')->nullable();
            $t->date('tanggal')->nullable();
            $t->string('dpjp')->nullable();
            $t->text('catatan')->nullable();
            // Diagnosis (ICD-10)
            $t->string('diagnosis_utama')->nullable();
            $t->string('kode_utama', 20)->nullable();
            $t->string('diagnosis_penyerta')->nullable();
            $t->string('kode_penyerta', 20)->nullable();
            $t->string('komplikasi')->nullable();
            $t->string('kode_komplikasi', 20)->nullable();
            // Tindakan (ICD-9-CM)
            $t->string('tindakan_nama')->nullable();
            $t->string('tindakan_kode', 20)->nullable();
            $t->string('tindakan_catatan')->nullable();
            // Obat, edukasi, cost
            $t->string('obat_nama')->nullable();
            $t->string('obat_keterangan')->nullable();
            $t->string('edukasi_materi')->nullable();
            $t->string('edukasi_keterangan')->nullable();
            $t->text('cost')->nullable();
            // Solusi tindak lanjut
            $t->string('solusi_rencana')->nullable();
            $t->string('solusi_pj')->nullable();
            $t->date('solusi_target')->nullable();
            $t->string('solusi_catatan')->nullable();
            $t->timestamps();
            $t->unique(['clinical_pathway_id', 'urutan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinical_pathway_kunjungans');
        Schema::dropIfExists('clinical_pathways');
    }
};
