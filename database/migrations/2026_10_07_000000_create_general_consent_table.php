<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * General Consent pasien baru: diisi otomatis setelah Identitas Pasien
     * (Step 1) pada pendaftaran, sebelum Informasi Kunjungan.
     */
    public function up(): void
    {
        if (Schema::hasTable('general_consent')) {
            return;
        }

        Schema::create('general_consent', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pasien_id')
                  ->constrained('pasien')
                  ->cascadeOnDelete();

            $table->string('pemberi_persetujuan', 20);     // Pasien / Wali
            $table->string('nama_penyetuju');
            $table->string('hubungan', 100)->nullable();
            $table->boolean('setuju')->default(true);
            $table->timestamp('disetujui_pada')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('general_consent');
    }
};
