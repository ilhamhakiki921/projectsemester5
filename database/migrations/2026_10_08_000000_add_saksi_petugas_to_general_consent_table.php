<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom tambahan General Consent untuk halaman Berkas Rekam Medis:
     * keluarga/pendamping, saksi, dan petugas yang mencatat.
     */
    public function up(): void
    {
        if (! Schema::hasTable('general_consent')) {
            return;
        }

        Schema::table('general_consent', function (Blueprint $table) {
            if (! Schema::hasColumn('general_consent', 'pendamping')) {
                $table->string('pendamping')->nullable();
            }
            if (! Schema::hasColumn('general_consent', 'saksi')) {
                $table->string('saksi')->nullable();
            }
            if (! Schema::hasColumn('general_consent', 'petugas_pencatat')) {
                $table->string('petugas_pencatat')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('general_consent')) {
            return;
        }

        Schema::table('general_consent', function (Blueprint $table) {
            foreach (['pendamping', 'saksi', 'petugas_pencatat'] as $kolom) {
                if (Schema::hasColumn('general_consent', $kolom)) {
                    $table->dropColumn($kolom);
                }
            }
        });
    }
};
