<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasienController;

Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::get('/login', function () {
    return view('auth.login');
})->name('login.page');

Route::post('/login', function () {
    return redirect()->route('dashboard');
})->name('login.process');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/pendaftaran', function () {
    return view('pendaftaran.index');
})->name('pendaftaran');

// ===========================
// PASIEN LAMA
// ===========================

Route::get('/pendaftaran/pasien-lama', [PasienController::class, 'indexLama'])->name('pasien.lama');
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

Route::get('/pemeriksaan', function () {
    return view('pemeriksaan.index');
})->name('pemeriksaan');

Route::get('/pemeriksaan/poli-umum', function () {
    return view('pemeriksaan.poli-umum');
})->name('pemeriksaan.umum');

Route::get('/pemeriksaan/poli-dalam', function () {
    return view('pemeriksaan.poli-dalam');
})->name('pemeriksaan.dalam');

Route::get('/pemeriksaan/poli-gigi', function () {
    return view('pemeriksaan.poli-gigi');
})->name('pemeriksaan.gigi');

Route::get('/pemeriksaan/fisioterapi', function () {
    return view('pemeriksaan.fisioterapi');
})->name('pemeriksaan.fisioterapi');

Route::get('/pemeriksaan/mikrobiologi', function () {
    return view('pemeriksaan.mikrobiologi');
})->name('pemeriksaan.mikrobiologi');