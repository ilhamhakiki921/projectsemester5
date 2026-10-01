<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('autentikasi_kunjungan')) {
            return;
        }

        Schema::create('autentikasi_kunjungan', function (Blueprint $t) {
            $t->id();
            $t->foreignId('kunjungan_id')->unique()->constrained('kunjungan')->cascadeOnDelete();
            $t->string('nama_dokter', 150);
            $t->string('jabatan', 100);
            $t->longText('tanda_tangan');          // gambar PNG (data URL base64)
            $t->timestamp('waktu_autentikasi');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('autentikasi_kunjungan');
    }
};
