<?php

use App\Http\Controllers\AutentikasiController;
use App\Http\Controllers\PenunjangController;
use App\Http\Controllers\RujukanController;
use Illuminate\Support\Facades\Route;

Route::prefix('pemeriksaan/{poli}/{no_rm}')->group(function () {
    // Penunjang
    Route::get('penunjang', [PenunjangController::class, 'index'])->name('penunjang.index');
    Route::post('penunjang', [PenunjangController::class, 'store'])->name('penunjang.store');
    Route::put('penunjang/{id}', [PenunjangController::class, 'update'])->name('penunjang.update');
    Route::delete('penunjang/{id}', [PenunjangController::class, 'destroy'])->name('penunjang.destroy');

    // Rujukan (ditaruh di file ini supaya routes/web.php tidak perlu diubah)
    Route::get('rujukan', [RujukanController::class, 'index'])->name('rujukan.index');
    Route::post('rujukan', [RujukanController::class, 'store'])->name('rujukan.store');
    Route::get('rujukan/{id}/cetak', [RujukanController::class, 'cetak'])->name('rujukan.cetak');
    Route::delete('rujukan/{id}', [RujukanController::class, 'destroy'])->name('rujukan.destroy');

    // Autentikasi
    Route::get('autentikasi', [AutentikasiController::class, 'index'])->name('autentikasi.index');
    Route::post('autentikasi', [AutentikasiController::class, 'store'])->name('autentikasi.store');
    Route::get('autentikasi/cetak', [AutentikasiController::class, 'cetak'])->name('autentikasi.cetak');
});
