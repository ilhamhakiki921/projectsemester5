<?php

use App\Http\Controllers\PenunjangController;
use Illuminate\Support\Facades\Route;

Route::prefix('pemeriksaan/{poli}/{no_rm}')->group(function () {
    Route::get('penunjang', [PenunjangController::class, 'index'])->name('penunjang.index');
    Route::post('penunjang', [PenunjangController::class, 'store'])->name('penunjang.store');
    Route::delete('penunjang/{id}', [PenunjangController::class, 'destroy'])->name('penunjang.destroy');
});
