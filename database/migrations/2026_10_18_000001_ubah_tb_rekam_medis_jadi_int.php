<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // bulatkan nilai desimal lama (mis. 165,5 -> 166) supaya bisa jadi int
        DB::statement("UPDATE rekam_medis
            SET tb = ROUND(CAST(REPLACE(tb, ',', '.') AS DECIMAL(6,2)))
            WHERE tb IS NOT NULL AND tb REGEXP '^[0-9]+([.,][0-9]+)?$'");

        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->integer('tb')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->string('tb')->nullable()->change();
        });
    }
};