<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('dokter', function (Blueprint $table) {
        $table->string('nik')->nullable()->after('nama');
        $table->string('ihs_number')->nullable()->after('nik');
    });

    Schema::table('poli', function (Blueprint $table) {
        $table->string('satusehat_location_id')->nullable();
    });

    Schema::table('kunjungan', function (Blueprint $table) {
        $table->string('satusehat_encounter_id')->nullable();
    });
}

public function down(): void
{
    Schema::table('dokter', fn (Blueprint $t) => $t->dropColumn(['nik', 'ihs_number']));
    Schema::table('poli', fn (Blueprint $t) => $t->dropColumn('satusehat_location_id'));
    Schema::table('kunjungan', fn (Blueprint $t) => $t->dropColumn('satusehat_encounter_id'));
}
};
