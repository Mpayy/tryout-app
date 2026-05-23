<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Guru\GuruDashboardController;
use App\Http\Controllers\Siswa\SiswaDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\MataPelajaranController;
use App\Http\Controllers\Guru\SoalController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    // Route::resource('users', AdminUserController::class);
    Route::get('users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::post('users/store', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::put('users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    // Route::resource('siswa', AdminSiswaController::class);
    // Route::resource('mata-pelajaran', MataPelajaranController::class);
    Route::get('mapels', [MataPelajaranController::class, 'index'])->name('admin.mapels.index');
    Route::post('mapels/store', [MataPelajaranController::class, 'store'])->name('admin.mapels.store');
    Route::put('mapels/{mapel}', [MataPelajaranController::class, 'update'])->name('admin.mapels.update');
    Route::delete('mapels/{mapel}', [MataPelajaranController::class, 'destroy'])->name('admin.mapels.destroy');
    // Route::get('monitoring', [MonitoringController::class, 'index'])->name('monitoring');
    // Route::get('rekap/{paket}', [RekapController::class, 'show'])->name('rekap.show');
    // Route::get('export/excel/{paket}', [ExportController::class, 'excel'])->name('export.excel');
    // Route::get('export/pdf/{paket}', [ExportController::class, 'pdf'])->name('export.pdf');
});

// Guru routes
Route::prefix('guru')->name('guru.')->middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
    Route::get('soal/bulk-input', [SoalController::class, 'index'])->name('soal.index');
    Route::post('soal/bulk-store', [SoalController::class, 'bulkStore'])->name('soal.bulk-store');
    // Route::resource('paket-ujian', PaketUjianController::class);
    // Route::post('paket-ujian/{paket}/soal', [PaketUjianController::class, 'tambahSoal'])->name('paket-ujian.tambah-soal');
    // Route::delete('paket-ujian/{paket}/soal/{soal}', [PaketUjianController::class, 'hapusSoal'])->name('paket-ujian.hapus-soal');
    // Route::patch('paket-ujian/{paket}/status', [PaketUjianController::class, 'updateStatus'])->name('paket-ujian.status');
    // Route::get('rekap/{paket}', [GuruRekapController::class, 'show'])->name('rekap.show');
    // Route::get('export/excel/{paket}', [GuruExportController::class, 'excel'])->name('export.excel');
    // Route::get('export/pdf/{paket}', [GuruExportController::class, 'pdf'])->name('export.pdf');
});

// Siswa routes
Route::prefix('siswa')->name('siswa.')->middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
    // Route::get('ujian', [UjianController::class, 'index'])->name('ujian.index');
    // Route::post('ujian/{paket}/mulai', [UjianController::class, 'mulai'])->name('ujian.mulai');
    // Route::get('ujian/{token}', [UjianController::class, 'show'])->name('ujian.show');
    // Route::get('ujian/{token}/soal/{nomor}', [UjianController::class, 'soal'])->name('ujian.soal');
    // Route::post('ujian/{token}/jawab', [UjianController::class, 'jawab'])->name('ujian.jawab');
    // Route::post('ujian/{token}/ragu', [UjianController::class, 'tandaiRagu'])->name('ujian.ragu');
    // Route::post('ujian/{token}/submit', [UjianController::class, 'submit'])->name('ujian.submit');
    // Route::get('hasil/{token}', [UjianController::class, 'hasil'])->name('ujian.hasil');
});