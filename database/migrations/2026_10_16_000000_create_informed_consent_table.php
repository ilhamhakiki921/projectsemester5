<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('informed_consent')) {
            return;
        }

        Schema::create('informed_consent', function (Blueprint $t) {
            $t->id();

            // Satu Informed Consent per kunjungan
            $t->foreignId('kunjungan_id')->unique()->constrained('kunjungan')->cascadeOnDelete();
            $t->string('poli', 50)->nullable();

            // Seluruh isian form (termasuk gambar tanda tangan) dalam bentuk JSON
            $t->longText('data');

            // true = tombol "Simpan" sudah ditekan dan formulir lengkap, false = masih draft
            $t->boolean('lengkap')->default(false);

            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informed_consent');
    }
};
