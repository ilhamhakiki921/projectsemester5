<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Penanda dari halaman SOAP: apakah pasien butuh Informed Consent.
     * Kalau true, tab "Informed Consent" muncul di antara SOAP dan Diagnosa.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('rekam_medis', 'butuh_informed_consent')) {
            Schema::table('rekam_medis', function (Blueprint $table) {
                $table->boolean('butuh_informed_consent')->default(false)->after('plan');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('rekam_medis', 'butuh_informed_consent')) {
            Schema::table('rekam_medis', function (Blueprint $table) {
                $table->dropColumn('butuh_informed_consent');
            });
        }
    }
};
