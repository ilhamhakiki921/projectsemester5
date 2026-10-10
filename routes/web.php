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
use App\Http\Controllers\Icd10Controller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\DiagnosaController;
use App\Http\Controllers\TindakanController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\DataPasienController;

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
    Route::post('/pendaftaran/pasien/{no_rm}/kunjungan', [PasienController::class, 'storeKunjunganByRm'])->name('pasien.kunjungan.store');
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
    // ICD-10 (autocomplete Diagnosa, sumber: WHO ICD-API)
    // ===========================

    Route::get('/icd10/cari', [Icd10Controller::class, 'cari'])->name('icd10.cari');

    Route::get('/icd9/cari', [TindakanController::class, 'cari'])->name('icd9.cari');

    // ===========================
    // PEMERIKSAAN
    // ===========================

    Route::get('/pemeriksaan/{poli?}', [PemeriksaanController::class, 'index'])->name('pemeriksaan.index');
    Route::get('/pemeriksaan/{poli}/{no_rm}/asesmen', [PemeriksaanController::class, 'asesmen'])->name('pemeriksaan.asesmen');
    Route::post('/pemeriksaan/{poli}/{no_rm}/asesmen', [PemeriksaanController::class, 'simpanAsesmen'])->name('pemeriksaan.asesmen.store');
    Route::get('/pemeriksaan/{poli}/{no_rm}/soap', [PemeriksaanController::class, 'soap'])->name('pemeriksaan.soap');
    Route::post('/pemeriksaan/{poli}/{no_rm}/soap', [PemeriksaanController::class, 'simpanSoap'])->name('pemeriksaan.soap.store');
    Route::get('/pemeriksaan/{poli}/{no_rm}/informed-consent', [PemeriksaanController::class, 'informedConsent'])->name('pemeriksaan.informed-consent');
    Route::post('/pemeriksaan/{poli}/{no_rm}/informed-consent/status', [PemeriksaanController::class, 'setStatusInformedConsent'])->name('pemeriksaan.informed-consent.status');
    Route::get('/pemeriksaan/{poli}/{no_rm}/diagnosa', [PemeriksaanController::class, 'diagnosa'])->name('pemeriksaan.diagnosa');
    Route::post('/pemeriksaan/{poli}/{no_rm}/diagnosa', [PemeriksaanController::class, 'simpanDiagnosa'])->name('pemeriksaan.diagnosa.store');
    Route::get('/pemeriksaan/{poli}/{no_rm}/resep', [PemeriksaanController::class, 'resep'])->name('pemeriksaan.resep');
    Route::post('/pemeriksaan/{poli}/{no_rm}/resep', [PemeriksaanController::class, 'simpanResep'])->name('pemeriksaan.resep.store');
    Route::get('/pemeriksaan/{poli}/{no_rm}/clinical-pathway', [PemeriksaanController::class, 'clinicalPathway'])->name('pemeriksaan.clinical-pathway');

    // ===========================
    // REKAM MEDIS
    // ===========================

    Route::get('/rekam-medis', [RekamMedisController::class, 'index'])->name('rekam-medis.index');
    Route::get('/rekam-medis/pasien/{pasien}', [RekamMedisController::class, 'berkas'])->name('rekam-medis.berkas');
    Route::put('/rekam-medis/pasien/{pasien}/general-consent', [RekamMedisController::class, 'consentUpdate'])->name('rekam-medis.consent.update');
    Route::get('/rekam-medis/pasien/{pasien}/general-consent/cetak', [RekamMedisController::class, 'consentCetak'])->name('rekam-medis.consent.cetak');
    Route::put('/rekam-medis/kunjungan/{kunjungan}/koding', [RekamMedisController::class, 'kodingUpdate'])->name('rekam-medis.koding.update');
    Route::get('/rekam-medis/{kunjungan}', [RekamMedisController::class, 'show'])->name('rekam-medis.show');

    Route::get('/farmasi', [FarmasiController::class, 'index'])->name('farmasi.index');

    Route::prefix('pemeriksaan/{poli}/{rm}')->group(function () {
        Route::get('clinical-pathway', [ClinicalPathwayController::class, 'index'])->name('clinical-pathway.index');
        Route::post('clinical-pathway', [ClinicalPathwayController::class, 'store'])->name('clinical-pathway.store');
    });

    // ===========================
    // DATA MASTER > DATA USER
    // ===========================

    Route::get('/data-user', [UserController::class, 'index'])->name('user.index');
    Route::post('/data-user', [UserController::class, 'store'])->name('user.store');
    Route::put('/data-user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::patch('/data-user/{user}/status', [UserController::class, 'toggleStatus'])->name('user.status');
    Route::delete('/data-user/{user}', [UserController::class, 'destroy'])->name('user.destroy');

    // ===========================
    // DATA MASTER > DATA POLI
    // ===========================

    Route::get('/data-poli', [PoliController::class, 'index'])->name('poli.index');
    Route::post('/data-poli', [PoliController::class, 'store'])->name('poli.store');
    Route::put('/data-poli/{poli}', [PoliController::class, 'update'])->name('poli.update');
    Route::delete('/data-poli/{poli}', [PoliController::class, 'destroy'])->name('poli.destroy');

    // ===========================
    // DATA MASTER > DATA DIAGNOSA/PENYAKIT (ICD-10)
    // ===========================

    Route::get('/data-diagnosa', [DiagnosaController::class, 'index'])->name('diagnosa.index');
    Route::post('/data-diagnosa', [DiagnosaController::class, 'store'])->name('diagnosa.store');
    Route::post('/data-diagnosa/import', [DiagnosaController::class, 'import'])->name('diagnosa.import');
    Route::put('/data-diagnosa/{icd10}', [DiagnosaController::class, 'update'])->name('diagnosa.update');
    Route::delete('/data-diagnosa/{icd10}', [DiagnosaController::class, 'destroy'])->name('diagnosa.destroy');

    // ===========================
    // DATA MASTER > DATA TINDAKAN (ICD-9)
    // ===========================

    Route::get('/data-tindakan', [TindakanController::class, 'index'])->name('tindakan.index');
    Route::post('/data-tindakan', [TindakanController::class, 'store'])->name('tindakan.store');
    Route::get('/data-tindakan/template', [TindakanController::class, 'template'])->name('tindakan.template');
    Route::post('/data-tindakan/import', [TindakanController::class, 'import'])->name('tindakan.import');
    Route::put('/data-tindakan/{tindakan}', [TindakanController::class, 'update'])->name('tindakan.update');
    Route::delete('/data-tindakan/{tindakan}', [TindakanController::class, 'destroy'])->name('tindakan.destroy');

    // ===========================
    // DATA MASTER > DATA OBAT
    // ===========================

    Route::get('/data-obat', [ObatController::class, 'index'])->name('obat.index');
    Route::post('/data-obat', [ObatController::class, 'store'])->name('obat.store');
    Route::put('/data-obat/{obat}', [ObatController::class, 'update'])->name('obat.update');
    Route::delete('/data-obat/{obat}', [ObatController::class, 'destroy'])->name('obat.destroy');

    // ===========================
    // DATA MASTER > DATA PASIEN
    // (pasien baru didaftarkan lewat menu Pendaftaran; di sini hanya lihat & edit)
    // ===========================

    Route::get('/data-pasien', [DataPasienController::class, 'index'])->name('data-pasien.index');
    Route::put('/data-pasien/{pasien}', [DataPasienController::class, 'update'])->name('data-pasien.update');

    require __DIR__.'/penunjang.php';

});