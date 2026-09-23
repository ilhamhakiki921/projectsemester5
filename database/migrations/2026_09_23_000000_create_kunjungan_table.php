<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kunjungan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pasien_id')
                  ->constrained('pasien')
                  ->cascadeOnDelete();

            $table->unsignedInteger('no_antrian');
            $table->date('tanggal_kunjungan');
            $table->string('dokter');
            $table->string('poli');
            $table->string('pembayaran');

            $table->enum('status', ['Menunggu', 'Sedang Diperiksa', 'Selesai'])
                  ->default('Menunggu');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungan');
    }
};
