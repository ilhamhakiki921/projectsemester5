<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom untuk desain baru General Consent (form lengkap):
     * - data_form : riwayat alergi/penyakit, data penanda tangan, kerabat,
     *               pilihan privasi & persetujuan A/C/D (JSON)
     * - ttd_*     : tanda tangan digital (data URL PNG)
     */
    public function up(): void
    {
        if (! Schema::hasTable('general_consent')) {
            return;
        }

        Schema::table('general_consent', function (Blueprint $table) {
            if (! Schema::hasColumn('general_consent', 'data_form')) {
                $table->json('data_form')->nullable();
            }
            if (! Schema::hasColumn('general_consent', 'ttd_petugas')) {
                $table->longText('ttd_petugas')->nullable();
            }
            if (! Schema::hasColumn('general_consent', 'ttd_penanggung_jawab')) {
                $table->longText('ttd_penanggung_jawab')->nullable();
            }
            if (! Schema::hasColumn('general_consent', 'ttd_saksi')) {
                $table->longText('ttd_saksi')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('general_consent')) {
            return;
        }

        Schema::table('general_consent', function (Blueprint $table) {
            foreach (['data_form', 'ttd_petugas', 'ttd_penanggung_jawab', 'ttd_saksi'] as $kolom) {
                if (Schema::hasColumn('general_consent', $kolom)) {
                    $table->dropColumn($kolom);
                }
            }
        });
    }
};
