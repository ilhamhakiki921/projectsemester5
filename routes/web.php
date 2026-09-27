<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\ClinicalPathwayController;
use App\Http\Controllers\RekamMedisController;
use App\Http\Controllers\FarmasiController;
use App\Http\Controllers\WilayahController;

// ===========================
// LOGIN / LOGOUT
// Hanya tamu (belum login) yang boleh membuka halaman login; kalau input
// kosong atau username/password salah, tetap dikembalikan ke form login
// (lihat App\Http\Controllers\Auth\LoginController).
// ===========================

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.page');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

// ===========================
// HALAMAN YANG WAJIB LOGIN
// SEMENTARA DINONAKTIFKAN atas permintaan: memakai middleware 'web' saja
// (bukan 'auth') supaya semua halaman di bawah ini bisa diakses tanpa
// login dulu. Tinggal ganti 'web' jadi 'auth' lagi kalau mau diaktifkan.
// ===========================

Route::middleware('web')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/dashboard/kunjungan-per-poli', [DashboardController::class, 'kunjunganPerPoli'])
        ->name('dashboard.kunjungan-per-poli');

    Route::get('/pendaftaran', [PasienController::class, 'pendaftaran'])->name('pendaftaran');

    // ===========================
    // PASIEN LAMA
    // ===========================

    Route::get('/pendaftaran/pasien-lama', [PasienController::class, 'indexLama'])->name('pasien.lama');
    Route::get('/pendaftaran/pasien/cari', [PasienController::class, 'cari'])->name('pasien.cari');
    Route::get('/pendaftaran/pasien-lama/{id}/edit', [PasienController::class, 'edit'])->name('pasien.lama.edit');
    Route::post('/pendaftaran/pasien-lama/{id}/kunjungan', [PasienController::class, 'storeKunjungan'])->name('pasien.lama.kunjungan.store');
    Route::delete('/pendaftaran/pasien-lama/{id}', [PasienController::class, 'destroy'])->name('pasien.lama.destroy');

    // ===========================
    // PASIEN BARU
    // ===========================

    Route::get('/pendaftaran/pasien-baru', [PasienController::class, 'create'])->name('pasien.baru');
    Route::post('/pendaftaran/pasien-baru', [PasienController::class, 'store'])->name('pasien.baru.store');
    Route::put('/pendaftaran/pasien/{no_rm}', [PasienController::class, 'update'])->name('pasien.update');

    // ===========================
    // WILAYAH (PROVINSI/KABUPATEN/KECAMATAN)
    // Proxy server-side supaya tidak kena CORS kalau dipanggil dari browser.
    // ===========================

    Route::get('/pendaftaran/wilayah/provinsi', [WilayahController::class, 'provinsi'])->name('wilayah.provinsi');
    Route::get('/pendaftaran/wilayah/kabupaten/{provinsiId}', [WilayahController::class, 'kabupaten'])->name('wilayah.kabupaten');
    Route::get('/pendaftaran/wilayah/kecamatan/{kabupatenId}', [WilayahController::class, 'kecamatan'])->name('wilayah.kecamatan');
    Route::get('/pendaftaran/wilayah/kelurahan/{kecamatanId}', [WilayahController::class, 'kelurahan'])->name('wilayah.kelurahan');

    // ===========================
    // PEMERIKSAAN
    // ===========================

    Route::get('/pemeriksaan/{poli?}', [PemeriksaanController::class, 'index'])->name('pemeriksaan.index');
    Route::get('/pemeriksaan/{poli}/{no_rm}/asesmen', [PemeriksaanController::class, 'asesmen'])->name('pemeriksaan.asesmen');
    Route::post('/pemeriksaan/{poli}/{no_rm}/asesmen', [PemeriksaanController::class, 'simpanAsesmen'])->name('pemeriksaan.asesmen.store');
    Route::get('/pemeriksaan/{poli}/{no_rm}/soap', [PemeriksaanController::class, 'soap'])->name('pemeriksaan.soap');
    Route::post('/pemeriksaan/{poli}/{no_rm}/soap', [PemeriksaanController::class, 'simpanSoap'])->name('pemeriksaan.soap.store');
    Route::get('/pemeriksaan/{poli}/{no_rm}/diagnosa', [PemeriksaanController::class, 'diagnosa'])->name('pemeriksaan.diagnosa');
    Route::post('/pemeriksaan/{poli}/{no_rm}/diagnosa', [PemeriksaanController::class, 'simpanDiagnosa'])->name('pemeriksaan.diagnosa.store');
    Route::get('/pemeriksaan/{poli}/{no_rm}/resep', [PemeriksaanController::class, 'resep'])->name('pemeriksaan.resep');
    Route::post('/pemeriksaan/{poli}/{no_rm}/resep', [PemeriksaanController::class, 'simpanResep'])->name('pemeriksaan.resep.store');
    Route::get('/pemeriksaan/{poli}/{no_rm}/clinical-pathway', [PemeriksaanController::class, 'clinicalPathway'])->name('pemeriksaan.clinical-pathway');

    // ===========================
    // REKAM MEDIS
    // ===========================

    Route::get('/rekam-medis', [RekamMedisController::class, 'index'])->name('rekam-medis.index');
    Route::get('/rekam-medis/{kunjungan}', [RekamMedisController::class, 'show'])->name('rekam-medis.show');

    Route::get('/farmasi', [FarmasiController::class, 'index'])->name('farmasi.index');

    Route::prefix('pemeriksaan/{poli}/{rm}')->group(function () {
        Route::get('clinical-pathway', [ClinicalPathwayController::class, 'index'])->name('clinical-pathway.index');
        Route::post('clinical-pathway', [ClinicalPathwayController::class, 'store'])->name('clinical-pathway.store');
    });

    require __DIR__.'/penunjang.php';

});