<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rekam_medis', function (Blueprint $table) {
            // SOAP
            $table->text('subjective')->nullable()->after('gcs');
            $table->text('objective')->nullable()->after('subjective');
            $table->text('assessment')->nullable()->after('objective');
            $table->text('plan')->nullable()->after('assessment');

            // Diagnosa
            $table->string('diagnosa_kerja')->nullable()->after('plan');
            $table->string('kode_icd10')->nullable()->after('diagnosa_kerja');
            $table->text('diagnosa_banding')->nullable()->after('kode_icd10');
        });
    }

    public function down(): void
    {
        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->dropColumn([
                'subjective',
                'objective',
                'assessment',
                'plan',
                'diagnosa_kerja',
                'kode_icd10',
                'diagnosa_banding',
            ]);
        });
    }
};
