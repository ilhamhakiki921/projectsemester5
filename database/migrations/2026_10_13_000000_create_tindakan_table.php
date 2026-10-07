<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Data Master > Data Tindakan (ICD-9 CM prosedur/tindakan medis).
     */
    public function up(): void
    {
        if (Schema::hasTable('tindakan')) {
            return;
        }

        Schema::create('tindakan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();   // contoh: 89.7, 99.21
            $table->string('nama', 500);            // nama tindakan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tindakan');
    }
};
