<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. rencana_kunjungan: tinyint -> varchar
        Schema::table('clinical_pathways', function (Blueprint $t) {
            $t->string('rencana_kunjungan', 10)->change();
        });

        // 2. catatan: text -> varchar(500)
        // potong dulu data lama yang lebih dari 500 karakter supaya tidak error
        DB::table('clinical_pathway_kunjungans')
            ->whereRaw('CHAR_LENGTH(catatan) > 500')
            ->update(['catatan' => DB::raw('LEFT(catatan, 500)')]);

        Schema::table('clinical_pathway_kunjungans', function (Blueprint $t) {
            $t->string('catatan', 500)->nullable()->change();
        });

        // 3. hapus kolom cost
        if (Schema::hasColumn('clinical_pathway_kunjungans', 'cost')) {
            Schema::table('clinical_pathway_kunjungans', function (Blueprint $t) {
                $t->dropColumn('cost');
            });
        }

        // 4. perlu_puasa: tinyint(1) -> varchar Ya/Tidak
        Schema::table('permintaan_penunjang', function (Blueprint $t) {
            $t->string('perlu_puasa', 5)->default('Tidak')->change();
        });
        DB::table('permintaan_penunjang')->where('perlu_puasa', '1')->update(['perlu_puasa' => 'Ya']);
        DB::table('permintaan_penunjang')->where('perlu_puasa', '0')->update(['perlu_puasa' => 'Tidak']);
    }

    public function down(): void
    {
        DB::table('permintaan_penunjang')->where('perlu_puasa', 'Ya')->update(['perlu_puasa' => '1']);
        DB::table('permintaan_penunjang')->where('perlu_puasa', 'Tidak')->update(['perlu_puasa' => '0']);
        Schema::table('permintaan_penunjang', function (Blueprint $t) {
            $t->boolean('perlu_puasa')->default(false)->change();
        });

        Schema::table('clinical_pathway_kunjungans', function (Blueprint $t) {
            $t->text('cost')->nullable();
            $t->text('catatan')->nullable()->change();
        });

        Schema::table('clinical_pathways', function (Blueprint $t) {
            $t->unsignedTinyInteger('rencana_kunjungan')->change();
        });
    }
};