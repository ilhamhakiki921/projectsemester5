<?php

use Illuminate\Support\Facades\Route;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

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