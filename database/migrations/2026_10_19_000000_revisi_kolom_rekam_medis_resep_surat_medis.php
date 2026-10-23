<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $polaAngka = '^[0-9]+([.,][0-9]+)?$';
    private string $polaGcs   = '^E[1-4]V[1-5]M[1-6]$';          // format lama, mis. E4V5M6
    private string $polaTd    = '^[0-9]{2,3}[[:space:]]*/[[:space:]]*[0-9]{2,3}$';

    public function up(): void
    {
        $this->cekDataRekamMedis();

        // ============================================================
        // rekam_medis
        // ============================================================

        // suhu_badan: "36,8" -> 37 (dibulatkan), string kosong -> NULL
        DB::statement("UPDATE rekam_medis SET suhu_badan = NULL WHERE TRIM(suhu_badan) = ''");
        DB::statement("UPDATE rekam_medis
            SET suhu_badan = ROUND(CAST(REPLACE(TRIM(suhu_badan), ',', '.') AS DECIMAL(6,2)))
            WHERE suhu_badan IS NOT NULL AND TRIM(suhu_badan) REGEXP '{$this->polaAngka}'");

        // gcs: "E4V5M6" -> 15 (jumlah E+V+M), string kosong -> NULL
        DB::statement("UPDATE rekam_medis SET gcs = NULL WHERE TRIM(gcs) = ''");
        DB::statement("UPDATE rekam_medis
            SET gcs = CAST(SUBSTRING(TRIM(gcs), 2, 1) AS UNSIGNED)
                    + CAST(SUBSTRING(TRIM(gcs), 4, 1) AS UNSIGNED)
                    + CAST(SUBSTRING(TRIM(gcs), 6, 1) AS UNSIGNED)
            WHERE gcs IS NOT NULL AND TRIM(gcs) REGEXP '{$this->polaGcs}'");
        DB::statement("UPDATE rekam_medis
            SET gcs = ROUND(CAST(REPLACE(TRIM(gcs), ',', '.') AS DECIMAL(6,2)))
            WHERE gcs IS NOT NULL AND TRIM(gcs) REGEXP '{$this->polaAngka}'");

        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->integer('suhu_badan')->nullable()->change();
            $table->integer('gcs')->nullable()->change();
        });

        // lp, lingkar_dada, lingkar_kepala dihapus
        foreach (['lp', 'lingkar_dada', 'lingkar_kepala'] as $kolom) {
            if (Schema::hasColumn('rekam_medis', $kolom)) {
                Schema::table('rekam_medis', fn (Blueprint $t) => $t->dropColumn($kolom));
            }
        }

        // ============================================================
        // resep: obat_id -> varchar(255)
        // Foreign key harus dilepas dulu (bigint vs varchar tidak bisa saling refer).
        // ============================================================
        Schema::table('resep', function (Blueprint $table) {
            $table->dropForeign(['obat_id']);
        });
        Schema::table('resep', function (Blueprint $table) {
            $table->string('obat_id', 255)->change();
        });

        // ============================================================
        // surat_medis
        // ============================================================
        Schema::table('surat_medis', function (Blueprint $table) {
            $table->string('berat_badan', 50)->nullable()->change();
            $table->integer('tinggi_badan')->nullable()->change();   // desimal lama dibulatkan oleh MySQL
            $table->integer('sistole')->nullable()->after('tinggi_badan');
            $table->integer('diastole')->nullable()->after('sistole');
        });

        // pecah "120/80" -> sistole 120, diastole 80
        DB::statement("UPDATE surat_medis SET
                sistole  = CAST(TRIM(SUBSTRING_INDEX(tekanan_darah, '/', 1)) AS UNSIGNED),
                diastole = CAST(TRIM(SUBSTRING_INDEX(tekanan_darah, '/', -1)) AS UNSIGNED)
            WHERE tekanan_darah IS NOT NULL AND TRIM(tekanan_darah) REGEXP '{$this->polaTd}'");

        Schema::table('surat_medis', function (Blueprint $table) {
            $table->dropColumn('tekanan_darah');
        });
    }

    public function down(): void
    {
        // surat_medis
        Schema::table('surat_medis', function (Blueprint $table) {
            $table->string('tekanan_darah', 15)->nullable()->after('tinggi_badan');
        });
        DB::statement("UPDATE surat_medis SET tekanan_darah = CONCAT(sistole, '/', diastole)
            WHERE sistole IS NOT NULL AND diastole IS NOT NULL");
        Schema::table('surat_medis', function (Blueprint $table) {
            $table->dropColumn(['sistole', 'diastole']);
            $table->decimal('tinggi_badan', 5, 1)->nullable()->change();
            $table->decimal('berat_badan', 5, 1)->nullable()->change();
        });

        // resep (gagal kalau ada obat_id yang bukan angka / obat-nya tidak ada)
        Schema::table('resep', function (Blueprint $table) {
            $table->unsignedBigInteger('obat_id')->change();
        });
        Schema::table('resep', function (Blueprint $table) {
            $table->foreign('obat_id')->references('id')->on('obat')->cascadeOnDelete();
        });

        // rekam_medis
        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->string('suhu_badan')->nullable()->change();
            $table->string('gcs')->nullable()->change();
            $table->string('lp')->nullable();
            $table->string('lingkar_dada')->nullable();
            $table->string('lingkar_kepala')->nullable();
        });
    }

    /**
     * Berhenti sebelum ada yang diubah kalau ada data lama yang tidak bisa jadi angka,
     * supaya data tidak hilang atau membuat ALTER TABLE gagal di tengah jalan.
     */
    private function cekDataRekamMedis(): void
    {
        $suhu = DB::table('rekam_medis')
            ->whereNotNull('suhu_badan')->whereRaw("TRIM(suhu_badan) <> ''")
            ->whereRaw('TRIM(suhu_badan) NOT REGEXP ?', [$this->polaAngka])
            ->pluck('suhu_badan', 'id');

        $gcs = DB::table('rekam_medis')
            ->whereNotNull('gcs')->whereRaw("TRIM(gcs) <> ''")
            ->whereRaw('TRIM(gcs) NOT REGEXP ?', [$this->polaAngka])
            ->whereRaw('TRIM(gcs) NOT REGEXP ?', [$this->polaGcs])
            ->pluck('gcs', 'id');

        if ($suhu->isNotEmpty() || $gcs->isNotEmpty()) {
            throw new RuntimeException(
                'Data lama tidak bisa diubah ke angka. suhu_badan (id => nilai): ' . $suhu->toJson()
                . ' | gcs (id => nilai): ' . $gcs->toJson() . ' — rapikan dulu datanya.'
            );
        }
    }
};
