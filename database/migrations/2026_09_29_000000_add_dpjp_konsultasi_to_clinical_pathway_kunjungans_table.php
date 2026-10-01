<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinical_pathway_kunjungans', function (Blueprint $t) {
            $t->string('pemeriksaan_dpjp')->nullable()->after('kode_komplikasi');
            $t->string('konsultasi_bagian')->nullable()->after('pemeriksaan_dpjp');
            $t->string('konsultasi_catatan')->nullable()->after('konsultasi_bagian');
        });
    }

    public function down(): void
    {
        Schema::table('clinical_pathway_kunjungans', function (Blueprint $t) {
            $t->dropColumn(['pemeriksaan_dpjp', 'konsultasi_bagian', 'konsultasi_catatan']);
        });
    }
};
