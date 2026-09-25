<?php

use App\Http\Controllers\PenunjangController;
use Illuminate\Support\Facades\Route;

Route::prefix('pemeriksaan/{poli}/{no_rm}')->group(function () {
    Route::get('penunjang', [PenunjangController::class, 'index'])->name('penunjang.index');
    route('penunjang.store', ['poli' => $poli, 'no_rm' => $no_rm])
    Route::delete('penunjang/{id}', [PenunjangController::class, 'destroy'])->name('penunjang.destroy');
});
