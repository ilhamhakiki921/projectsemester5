<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('permintaan_penunjang', function (Blueprint $t) {
            $t->id();
            $t->string('no_rm')->index();
            $t->json('pemeriksaan');
            $t->boolean('perlu_puasa')->default(false);
            $t->string('hasil_ke')->default('dokter');
            $t->text('keterangan')->nullable();
            $t->string('status')->default('Menunggu');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_penunjang');
    }
};
