<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BuktiController as AdminBuktiController;
use App\Http\Controllers\Admin\DosenWaliController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\MasterPoinController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Mahasiswa\BuktiController;
use App\Http\Controllers\Dosen\buktiController as DosenBuktiController;
use App\Http\Controllers\Kaprodi\CatatanController;
use App\Http\Controllers\Kaprodi\DashboardController as KaprodiDashboardController;
use App\Http\Controllers\Kaprodi\RekapController;
use App\Http\Controllers\Mahasiswa\MahasiswaController;
use Illuminate\Support\Facades\Route;


// landing
Route::get('/', fn() => view('welcome'));

// auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ================= MAHASISWA =================
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->group(function () {
    Route::get('/dashboard', [MahasiswaController::class, 'index'])
        ->name('mahasiswa.dashboard');
    //page riwayat
    Route::get('/bukti', [BuktiController::class, 'index'])
        ->name('mahasiswa.bukti.index');
    //page upload
    Route::get('/bukti/upload', [BuktiController::class, 'create'])
        ->name('mahasiswa.bukti.create');
    Route::post('/bukti/upload', [BuktiController::class, 'store'])
        ->name('mahasiswa.bukti.store');
    Route::prefix('mahasiswa/bukti')->group(function () {
        Route::get('/get-peran/{jenis_kegiatan_id}', [BuktiController::class, 'getPeran']);
        Route::get('/get-tingkat/{jenis_kegiatan_id}/{peran}', [BuktiController::class, 'getTingkat']);
    });
    // DROPDOWN
    Route::get(
        '/master-poin/jenis/{kategori}',
        [BuktiController::class, 'getJenisKegiatan']
    );

    Route::get(
        '/master-poin/peran/{jenis_kegiatan_id}',
        [BuktiController::class, 'getPeran']
    );

    Route::get(
        '/master-poin/tingkat/{jenis_kegiatan_id}/{peran}',
        [BuktiController::class, 'getTingkat']
    );
    Route::get(
        '/bukti/transkrip/pdf',
        [buktiController::class, 'transkripPdf']
    )
        ->name('mahasiswa.transkrip.pdf');
});

// ================= DOSEN =================
Route::middleware(['auth', 'role:dosen_wali'])->prefix('dosen')->group(function () {
    Route::get('/dashboard', [DosenBuktiController::class, 'dashboard'])
        ->name('dosen.dashboard');
    Route::get('/mahasiswa', [DosenBuktiController::class, 'mahasiswaBimbingan'])
        ->name('dosen.mahasiswa.index');
    Route::get('/mahasiswa/{id}', [DosenBuktiController::class, 'detailMahasiswa'])
        ->name('dosen.mahasiswa.detail');
    Route::get('/bukti', [DosenBuktiController::class, 'index'])
        ->name('dosen.bukti.index');
    Route::get('/bukti/{id}', [BuktiController::class, 'show'])->name('dosen.bukti.show');
    Route::post('/bukti/{id}/approve', [DosenBuktiController::class, 'approve'])->name('dosen.bukti.approve');
    Route::post('/bukti/{id}/reject', [DosenBuktiController::class, 'reject'])->name('dosen.bukti.reject');
    Route::get(
        '/catatan',
        [DosenBuktiController::class, 'catatanKaprodi']
    )->name('dosen.catatan.index');
});

// ================= KAPRODI =================
Route::middleware(['auth', 'role:kaprodi'])->prefix('kaprodi')->group(function () {
    Route::get(
        '/dashboard',
        [KaprodiDashboardController::class, 'index']
    )->name('kaprodi.dashboard');
    Route::get(
        '/rekap',
        [RekapController::class, 'index']
    )->name('kaprodi.rekap');
    Route::get('/catatan', [CatatanController::class, 'index'])
        ->name('kaprodi.catatan.index');
    Route::get('/catatan/create', [CatatanController::class, 'create'])
        ->name('kaprodi.catatan.create');
    Route::post('/catatan', [CatatanController::class, 'store'])
        ->name('kaprodi.catatan.store');
    Route::get('/kaprodi/dashboard/grafik-semester', [KaprodiDashboardController::class, 'grafikSemester'])
        ->name('kaprodi.dashboard.grafik');
    Route::get('/kaprodi/dashboard/filter', [KaprodiDashboardController::class, 'filter'])
        ->name('kaprodi.dashboard.filter');
    Route::get('/mahasiswa', [RekapController::class, 'daftarMahasiswa'])
        ->name('kaprodi.mahasiswa.index');
});

// ================= ADMIN =================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // DASHBOARD
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');
    //manajemen masterpoin
    Route::get('/master-poin', [MasterPoinController::class, 'index'])
        ->name('master-poin.index');
    Route::get('/master-poin/create', [MasterPoinController::class, 'create'])
        ->name('master-poin.create');
    Route::post('/master-poin/store', [MasterPoinController::class, 'store'])
        ->name('master-poin.store');
    Route::get('/master-poin/{id}/edit', [MasterPoinController::class, 'edit'])
        ->name('master-poin.edit');
    Route::put('/master-poin/{id}', [MasterPoinController::class, 'update'])
        ->name('master-poin.update');
    Route::post('/master-poin/{id}/nonaktif', [MasterPoinController::class, 'nonaktif'])
        ->name('master-poin.nonaktif');
    Route::post('/master-poin/{id}/aktif', [MasterPoinController::class, 'aktif'])
        ->name('master-poin.aktif');
    //manajemen user
    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])
        ->name('users.create');
    Route::post('/users', [UserController::class, 'store'])
        ->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])
        ->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])
        ->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy');
    //Manajemen Mahasiswa
    Route::resource('mahasiswa', MahasiswaController::class)->except('index');
    //bukti
    Route::get('/bukti', [AdminBuktiController::class, 'index'])
        ->name('bukti.index');
    //import masterpoin
    Route::get('/import/master-poin', [ImportController::class, 'masterPoinForm'])
        ->name('import.master-poin.form');
    Route::post('/import/master-poin', [ImportController::class, 'importMasterPoin'])
        ->name('import.master-poin.process');
    //relasi dosen
    Route::get('/dosen-wali', [DosenWaliController::class, 'index'])
        ->name('dosen-wali.index');
    Route::post('/users/{userId}/assign-dosen-wali', [DosenWaliController::class, 'assignSingle'])
        ->name('users.assign-dosen-wali');
    Route::resource('dosen-wali', DosenWaliController::class);
    //cetak laporan 
    Route::get('/laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])
        ->name('laporan.index');
    Route::post('/laporan/cetak', [\App\Http\Controllers\Admin\LaporanController::class, 'cetak'])
        ->name('laporan.cetak');
});
