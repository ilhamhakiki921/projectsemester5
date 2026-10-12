<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('permintaan_penunjang')) {
            return;
        }

        Schema::table('permintaan_penunjang', function (Blueprint $table) {
            if (! Schema::hasColumn('permintaan_penunjang', 'hasil')) {
                $table->text('hasil')->nullable();
            }
            if (! Schema::hasColumn('permintaan_penunjang', 'waktu_hasil')) {
                $table->timestamp('waktu_hasil')->nullable();
            }
            if (! Schema::hasColumn('permintaan_penunjang', 'petugas')) {
                $table->string('petugas', 150)->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('permintaan_penunjang')) {
            return;
        }

        Schema::table('permintaan_penunjang', function (Blueprint $table) {
            foreach (['hasil', 'waktu_hasil', 'petugas'] as $kolom) {
                if (Schema::hasColumn('permintaan_penunjang', $kolom)) {
                    $table->dropColumn($kolom);
                }
            }
        });
    }
};