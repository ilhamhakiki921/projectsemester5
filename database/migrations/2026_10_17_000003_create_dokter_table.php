<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('dokter')) {
            Schema::create('dokter', function (Blueprint $table) {
                $table->id();
                $table->string('nama', 150);
                $table->string('poli', 50)->index();
                $table->string('jabatan', 100)->nullable();
                $table->boolean('utama')->default(false);
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
                $table->timestamps();

                $table->unique(['nama', 'poli']);
            });
        }

        if (DB::table('dokter')->count() === 0) {
            $now = now();
            $baris = [];

            foreach ((array) config('dokter.daftar', []) as $poli => $daftar) {
                foreach (array_values((array) $daftar) as $i => $nama) {
                    $baris[] = [
                        'nama'       => $nama,
                        'poli'       => $poli,
                        'jabatan'    => config('dokter.jabatan.' . $poli),
                        'utama'      => $i === 0,
                        'status'     => 'aktif',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            if ($baris) {
                DB::table('dokter')->insert($baris);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('dokter');
    }
};