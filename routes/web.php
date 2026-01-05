<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\MasterPoinController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboarController;
use App\Http\Controllers\Mahasiswa\SertifikatController;
use App\Http\Controllers\Dosen\SertifikatController as DosenSertifikatController;
use App\Http\Controllers\Kaprodi\DashboardController as KaprodiDashboardController;
use App\Http\Controllers\Kaprodi\RekapController;
use Illuminate\Support\Facades\Route;

// landing
Route::get('/', fn () => view('welcome'));

// auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ================= MAHASISWA =================
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->group(function () {
    Route::get('/dashboard', [MahasiswaDashboarController::class, 'index']);
    Route::get('/sertifikat', [SertifikatController::class, 'index']);
    Route::get('/sertifikat/upload',[SertifikatController::class,'create'])
    ->name('mahasiswa.sertifikat.create');
    Route::post('/sertifikat/upload',[SertifikatController::class,'store'])
    ->name('mahasiswa.sertifikat.store');
    // DROPDOWN
    Route::get('/master-poin/jenis/{kategori}',
        [SertifikatController::class, 'getJenisKegiatan']);
    Route::get('/master-poin/peran/{kategori}/{jenis}',
        [SertifikatController::class, 'getPeran']);
    Route::get('/master-poin/tingkat/{kategori}/{jenis}/{peran}',
        [SertifikatController::class, 'getTingkat']);
    
    Route::get('/sertifikat/transkrip/pdf', 
    [SertifikatController::class, 'transkripPdf'])
    ->name('mahasiswa.transkrip.pdf');
    });

// ================= DOSEN =================
Route::middleware(['auth', 'role:dosen'])->group(function () {
    Route::get('/dosen/dashboard', fn () => view('dosen.dashboard'));
    Route::get('/dosen/sertifikat', [DosenSertifikatController::class, 'index']);
    Route::post('/dosen/sertifikat/{id}/approve', [DosenSertifikatController::class, 'approve']);
    Route::post('/dosen/sertifikat/{id}/reject', [DosenSertifikatController::class, 'reject']);
});

// ================= KAPRODI =================
Route::middleware(['auth', 'role:kaprodi'])->prefix('kaprodi')->group(function () {
    Route::get('/dashboard', [KaprodiDashboardController::class, 'index'])
        ->name('kaprodi.dashboard');
    Route::get('/rekap', [\App\Http\Controllers\Kaprodi\RekapController::class, 'index']);
});

// ================= ADMIN =================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);
    Route::get('/master-poin', [MasterPoinController::class, 'index']);
    Route::get('/master-poin/create', [MasterPoinController::class, 'create']);
    Route::post('/master-poin', [MasterPoinController::class, 'store']);
    Route::get('/master-poin/{id}/edit', [MasterPoinController::class, 'edit']);
    Route::put('/master-poin/{id}', [MasterPoinController::class, 'update']);
});
