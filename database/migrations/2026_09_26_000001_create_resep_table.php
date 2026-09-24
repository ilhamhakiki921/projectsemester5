<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resep', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kunjungan_id')
                  ->constrained('kunjungan')
                  ->cascadeOnDelete();

            $table->foreignId('obat_id')
                  ->constrained('obat')
                  ->cascadeOnDelete();

            $table->unsignedInteger('jumlah_obat');
            $table->string('dosis_obat');
            $table->string('frekuensi');
            $table->date('tanggal_penulisan');

            $table->enum('status_farmasi', ['Menunggu Diambil', 'Sudah Diserahkan'])
                  ->default('Menunggu Diambil');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resep');
    }
};
