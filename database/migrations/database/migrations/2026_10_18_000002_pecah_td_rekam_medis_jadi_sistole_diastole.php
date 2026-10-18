<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $pola = '^[0-9]{2,3}[[:space:]]*/[[:space:]]*[0-9]{2,3}$';

    public function up(): void
    {
        // Berhenti kalau ada TD lama yang formatnya bukan 120/80, supaya data tidak hilang diam-diam
        $tidakCocok = DB::table('rekam_medis')
            ->whereNotNull('td')->where('td', '<>', '')
            ->whereRaw('TRIM(td) NOT REGEXP ?', [$this->pola])
            ->pluck('td', 'id');

        if ($tidakCocok->isNotEmpty()) {
            throw new RuntimeException(
                'TD berformat tidak standar (id => td): ' . $tidakCocok->toJson() . ' — rapikan dulu jadi 120/80.'
            );
        }

        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->integer('sistole')->nullable()->after('tb');
            $table->integer('diastole')->nullable()->after('sistole');
        });

        DB::statement("UPDATE rekam_medis SET
                sistole  = CAST(TRIM(SUBSTRING_INDEX(td, '/', 1)) AS UNSIGNED),
                diastole = CAST(TRIM(SUBSTRING_INDEX(td, '/', -1)) AS UNSIGNED)
            WHERE td IS NOT NULL AND TRIM(td) REGEXP ?", [$this->pola]);

        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->dropColumn('td');
        });
    }

    public function down(): void
    {
        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->string('td')->nullable()->after('tb');
        });

        DB::statement("UPDATE rekam_medis SET td = CONCAT(sistole, '/', diastole)
            WHERE sistole IS NOT NULL AND diastole IS NOT NULL");

        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->dropColumn(['sistole', 'diastole']);
        });
    }
};