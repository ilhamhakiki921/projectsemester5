<?php
// Tempel ke routes/web.php (dan tambahkan use di bagian atas):
// use App\Http\Controllers\ClinicalPathwayController;

Route::prefix('pemeriksaan/{poli}/{rm}')->group(function () {
    Route::get('clinical-pathway', [ClinicalPathwayController::class, 'index'])->name('clinical-pathway.index');
    Route::post('clinical-pathway', [ClinicalPathwayController::class, 'store'])->name('clinical-pathway.store');
});
