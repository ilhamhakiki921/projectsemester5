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
    Schema::table('pasien', function (Blueprint $table) {
        $table->string('ihs_number')->nullable()->after('nik');
    });
}

public function down(): void
{
    Schema::table('pasien', function (Blueprint $table) {
        $table->dropColumn('ihs_number');
    });
}
};
