<?php

// routes/web.php
// FIX #6: Tambah prefix('admin') untuk semua route admin agar URL konsisten
//         /admin/dashboard, /admin/users, /admin/mapels
//         (sebelumnya: /dashboard, /users, /mapels — tidak konsisten)

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\MataPelajaranController;
use App\Http\Controllers\Guru\GuruDashboardController;
use App\Http\Controllers\Guru\SoalController;
use App\Http\Controllers\Siswa\SiswaDashboardController;

// ── Halaman welcome ──────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
});

// ── Profile (bawaan Breeze, semua role bisa akses) ───────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// ════════════════════════════════════════════════════════
// ADMIN ROUTES
// FIX #6: Tambah prefix('admin') agar URL menjadi /admin/...
// ════════════════════════════════════════════════════════
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // ── Manajemen User ────────────────────────────────────
    Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('users', [AdminUserController::class, 'store'])->name('users.store');
    Route::put('users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // ── Mata Pelajaran ───────────────────────────────────
    Route::get('mapels', [MataPelajaranController::class, 'index'])->name('mapels.index');
    Route::post('mapels', [MataPelajaranController::class, 'store'])->name('mapels.store');
    Route::put('mapels/{mapel}', [MataPelajaranController::class, 'update'])->name('mapels.update');
    Route::delete('mapels/{mapel}', [MataPelajaranController::class, 'destroy'])->name('mapels.destroy');

    // ── Monitoring & Rekap (aktifkan saat controller sudah dibuat) ──
    // Route::get('monitoring', [MonitoringController::class, 'index'])->name('monitoring');
    // Route::get('rekap/{paket}', [RekapController::class, 'show'])->name('rekap.show');

    // ── Export ──────────────────────────────────────────
    // Route::get('export/excel/{paket}', [ExportController::class, 'excel'])->name('export.excel');
    // Route::get('export/pdf/{paket}', [ExportController::class, 'pdf'])->name('export.pdf');
});

// ════════════════════════════════════════════════════════
// GURU ROUTES
// ════════════════════════════════════════════════════════
Route::prefix('guru')->name('guru.')->middleware(['auth', 'role:guru'])->group(function () {

    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');

    // ── Bank Soal ────────────────────────────────────────
    Route::get('soal', [SoalController::class, 'index'])->name('soal.index');
    Route::get('soal/create', [SoalController::class, 'create'])->name('soal.create');
    Route::post('soal/store', [SoalController::class, 'store'])->name('soal.store');
    Route::delete('soal/{soal}', [SoalController::class, 'destroy'])->name('soal.destroy');

    // ── Paket Ujian ──
    Route::resource('paket-ujian', \App\Http\Controllers\Guru\PaketUjianController::class);
    Route::post('paket-ujian/{paket_ujian}/soal', [\App\Http\Controllers\Guru\PaketUjianController::class, 'tambahSoal'])->name('paket-ujian.tambah-soal');
    Route::delete('paket-ujian/{paket_ujian}/soal/{soal}', [\App\Http\Controllers\Guru\PaketUjianController::class, 'hapusSoal'])->name('paket-ujian.hapus-soal');
    Route::patch('paket-ujian/{paket_ujian}/status', [\App\Http\Controllers\Guru\PaketUjianController::class, 'updateStatus'])->name('paket-ujian.status');
});

// ════════════════════════════════════════════════════════
// SISWA ROUTES
// ════════════════════════════════════════════════════════
Route::prefix('siswa')->name('siswa.')->middleware(['auth', 'role:siswa'])->group(function () {

    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');

    // ── Ujian ────
    // Route::get('ujian', [\App\Http\Controllers\Siswa\UjianController::class, 'index'])->name('ujian.index');
    // Route::post('ujian/{paket}/mulai', [\App\Http\Controllers\Siswa\UjianController::class, 'mulai'])->name('ujian.mulai');
    // Route::get('ujian/{token}', [\App\Http\Controllers\Siswa\UjianController::class, 'show'])->name('ujian.show');
    // Route::get('ujian/{token}/soal/{nomor}', [\App\Http\Controllers\Siswa\UjianController::class, 'soal'])->name('ujian.soal');
    // Route::post('ujian/{token}/jawab', [\App\Http\Controllers\Siswa\UjianController::class, 'jawab'])->name('ujian.jawab');
    // Route::post('ujian/{token}/ragu', [\App\Http\Controllers\Siswa\UjianController::class, 'tandaiRagu'])->name('ujian.ragu');
    // Route::post('ujian/{token}/submit', [\App\Http\Controllers\Siswa\UjianController::class, 'submit'])->name('ujian.submit');
    // Route::get('hasil/{token}', [\App\Http\Controllers\Siswa\UjianController::class, 'hasil'])->name('ujian.hasil');
});