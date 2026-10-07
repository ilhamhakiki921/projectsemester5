<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pasien', function (Blueprint $table) {
            $table->string('title')->nullable()->after('nama');
            $table->string('tempat_lahir')->nullable()->after('tanggal_lahir');
            $table->string('agama')->nullable();
            $table->string('status_pernikahan')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('negara')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('pekerjaan')->nullable();

            // Data lain pasien
            $table->string('no_kk')->nullable();
            $table->string('domisili')->nullable();
            $table->string('nip')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('divisi')->nullable();
            $table->string('departemen')->nullable();
            $table->string('sub_departemen')->nullable();
            $table->string('perusahaan')->nullable();
            $table->string('suku')->nullable();
            $table->string('bahasa')->nullable();
            $table->string('kewarganegaraan')->nullable();

            // Keluarga/kerabat yang bisa dihubungi
            $table->string('keluarga_nama')->nullable();
            $table->string('keluarga_jk')->nullable();
            $table->string('keluarga_hubungan')->nullable();
            $table->string('keluarga_telepon')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pasien', function (Blueprint $table) {
            $table->dropColumn([
                'title', 'tempat_lahir', 'agama', 'status_pernikahan', 'provinsi', 'kabupaten',
                'kelurahan', 'negara', 'pendidikan', 'pekerjaan',
                'no_kk', 'domisili', 'nip', 'jabatan', 'divisi', 'departemen', 'sub_departemen',
                'perusahaan', 'suku', 'bahasa', 'kewarganegaraan',
                'keluarga_nama', 'keluarga_jk', 'keluarga_hubungan', 'keluarga_telepon',
            ]);
        });
    }
};
