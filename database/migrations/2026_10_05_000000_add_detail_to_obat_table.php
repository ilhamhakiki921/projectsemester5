<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Data Obat: nama obat TIDAK memuat dosis. Dosis (kekuatan) punya kolom sendiri,
     * mis. nama_obat = "Paracetamol", dosis = "500 mg", sediaan = "Tablet".
     */
    public function up(): void
    {
        Schema::table('obat', function (Blueprint $table) {
            $table->string('kode_obat', 20)->nullable()->unique()->after('id');
            $table->string('dosis', 50)->nullable()->after('nama_obat');
            $table->unsignedInteger('stok')->default(0)->after('sediaan');
            $table->unsignedInteger('harga')->nullable()->after('stok');
        });

        // Rapikan data lama: pisahkan dosis dari nama ("Paracetamol 500mg" -> "Paracetamol" + "500 mg")
        // dan beri kode OBT-001, OBT-002, ... berdasarkan urutan id.
        $pola = '/^(.*?)\s+(\d+(?:[.,]\d+)?\s*(?:mg|mcg|g|ml|iu)(?:\s*\/\s*\d+(?:[.,]\d+)?\s*(?:mg|mcg|g|ml))?)\s*$/i';

        foreach (DB::table('obat')->orderBy('id')->get() as $obat) {
            $nama  = $obat->nama_obat;
            $dosis = null;

            if (preg_match($pola, $nama, $m) && trim($m[1]) !== '') {
                $nama  = trim($m[1]);
                $dosis = trim(preg_replace('/(\d)\s*([a-z])/i', '$1 $2', $m[2]));
            }

            DB::table('obat')->where('id', $obat->id)->update([
                'kode_obat'  => 'OBT-' . str_pad($obat->id, 3, '0', STR_PAD_LEFT),
                'nama_obat'  => $nama,
                'dosis'      => $dosis,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('obat', function (Blueprint $table) {
            $table->dropUnique(['kode_obat']);
            $table->dropColumn(['kode_obat', 'dosis', 'stok', 'harga']);
        });
    }
};
