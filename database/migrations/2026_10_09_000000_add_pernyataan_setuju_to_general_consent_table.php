<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menyimpan pernyataan General Consent mana saja yang disetujui
     * (daftar nomor urut, mis. [0,1,2,3,4,5,6,7]).
     */
    public function up(): void
    {
        if (Schema::hasTable('general_consent') && ! Schema::hasColumn('general_consent', 'pernyataan_setuju')) {
            Schema::table('general_consent', function (Blueprint $table) {
                $table->json('pernyataan_setuju')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('general_consent') && Schema::hasColumn('general_consent', 'pernyataan_setuju')) {
            Schema::table('general_consent', function (Blueprint $table) {
                $table->dropColumn('pernyataan_setuju');
            });
        }
    }
};
