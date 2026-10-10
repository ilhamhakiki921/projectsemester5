<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinical_pathway_kunjungans', function (Blueprint $t) {
            $t->string('tindakan_kode', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('clinical_pathway_kunjungans', function (Blueprint $t) {
            $t->string('tindakan_kode', 20)->nullable()->change();
        });
    }
};