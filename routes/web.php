<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\RekamMedisController;
use App\Http\Controllers\FarmasiController;

Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::get('/login', function () {
    return view('auth.login');
})->name('login.page');

Route::post('/login', function () {
    return redirect()->route('dashboard');
})->name('login.process');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/dashboard/kunjungan-per-poli', [DashboardController::class, 'kunjunganPerPoli'])
    ->name('dashboard.kunjungan-per-poli');

Route::get('/pendaftaran', function () {
    return view('pendaftaran.index');
})->name('pendaftaran');

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