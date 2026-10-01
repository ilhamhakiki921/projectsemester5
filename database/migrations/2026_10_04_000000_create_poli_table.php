<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel master poli untuk halaman Data Master > Data Poli.
     * Diisi awal dengan 5 poli yang sudah dipakai sistem.
     */
    public function up(): void
    {
        if (! Schema::hasTable('poli')) {
            Schema::create('poli', function (Blueprint $table) {
                $table->id();
                $table->string('kode_poli', 20)->unique();
                $table->string('nama_poli', 100);
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
                $table->timestamps();
            });
        }

        if (DB::table('poli')->count() === 0) {
            $now = now();
            DB::table('poli')->insert([
                ['kode_poli' => 'PLI-001', 'nama_poli' => 'Poli Umum',    'status' => 'aktif', 'created_at' => $now, 'updated_at' => $now],
                ['kode_poli' => 'PLI-002', 'nama_poli' => 'Poli Gigi',    'status' => 'aktif', 'created_at' => $now, 'updated_at' => $now],
                ['kode_poli' => 'PLI-003', 'nama_poli' => 'Poli Dalam',   'status' => 'aktif', 'created_at' => $now, 'updated_at' => $now],
                ['kode_poli' => 'PLI-004', 'nama_poli' => 'Mikrobiologi', 'status' => 'aktif', 'created_at' => $now, 'updated_at' => $now],
                ['kode_poli' => 'PLI-005', 'nama_poli' => 'Fisioterapi',  'status' => 'aktif', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('poli');
    }
};
