<?php

// routes/web.php
// FIX #6: Tambah prefix('admin') untuk semua route admin agar URL konsisten
//         /admin/dashboard, /admin/users, /admin/mapels
//         (sebelumnya: /dashboard, /users, /mapels — tidak konsisten)

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\MataPelajaranController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Guru\GuruDashboardController;
use App\Http\Controllers\Guru\PaketUjianController;
use App\Http\Controllers\Guru\SoalController;
use App\Http\Controllers\Siswa\SiswaDashboardController;
use App\Http\Controllers\Siswa\UjianController;

// ── Halaman welcome ──────────────────────────────────────────
// Route::get('/', function () {
//     return view('welcome');
// });

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

    // ── Kelas ───────────────────────────────────
    Route::get('kelas', [KelasController::class, 'index'])->name('kelas.index');
    Route::post('kelas', [KelasController::class, 'store'])->name('kelas.store');
    Route::put('kelas/{kelas}', [KelasController::class, 'update'])->name('kelas.update');
    Route::delete('kelas/{kelas}', [KelasController::class, 'destroy'])->name('kelas.destroy');

    // ── Tambah Siswa Ke Kelas ───────────────────────────────────
    Route::get('kelas/{kelas}/anggota', [KelasController::class, 'anggota'])->name('kelas.anggota');
    Route::post('kelas/{kelas}/tambah-siswa', [KelasController::class, 'tambahSiswa'])->name('kelas.tambah-siswa');
    Route::delete('kelas/{kelas}/hapus-siswa/{profile_siswa}', [KelasController::class, 'hapusSiswa'])->name('kelas.hapus-siswa');

    // ── Siswa ───────────────────────────────────
    Route::get('siswa', [SiswaController::class, 'index'])->name('siswa.index');
    Route::post('siswa', [SiswaController::class, 'store'])->name('siswa.store');
    Route::put('siswa/{user}', [SiswaController::class, 'update'])->name('siswa.update');
    Route::delete('siswa/{user}', [SiswaController::class, 'destroy'])->name('siswa.destroy');

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
    // Route::resource('paket-ujian', \App\Http\Controllers\Guru\PaketUjianController::class);
    Route::get('paket-ujian', [PaketUjianController::class,'index'])->name('paket-ujian.index');
    Route::post('paket-ujian', [PaketUjianController::class,'store'])->name('paket-ujian.store');
    Route::get('paket-ujian/{paket_ujian}/show', [PaketUjianController::class,'show'])->name('paket-ujian.show');
    Route::put('paket-ujian/{paket_ujian}', [PaketUjianController::class,'update'])->name('paket-ujian.update');
    Route::delete('paket-ujian/{paket_ujian}', [PaketUjianController::class,'destroy'])->name('paket-ujian.destroy');

    Route::post('paket-ujian/{paket_ujian}/soal', [PaketUjianController::class, 'tambahSoal'])->name('paket-ujian.tambah-soal');
    Route::delete('paket-ujian/{paket_ujian}/soal/{soal}', [PaketUjianController::class, 'hapusSoal'])->name('paket-ujian.hapus-soal');
    Route::patch('paket-ujian/{paket_ujian}/status', [PaketUjianController::class, 'updateStatus'])->name('paket-ujian.status');
});

// ════════════════════════════════════════════════════════
// SISWA ROUTES
// ════════════════════════════════════════════════════════
Route::prefix('siswa')->name('siswa.')->middleware(['auth', 'role:siswa'])->group(function () {

    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');

    // ── Ujian ────
    Route::get('ujian', [UjianController::class, 'index'])->name('ujian.index');
    Route::post('ujian/{paket}/mulai', [UjianController::class, 'mulai'])->name('ujian.mulai');
    Route::get('ujian/{token}', [UjianController::class, 'show'])->name('ujian.show');
    Route::get('ujian/{token}/soal/{nomor}', [UjianController::class, 'soal'])->name('ujian.soal');
    Route::post('ujian/{token}/jawab', [UjianController::class, 'jawab'])->name('ujian.jawab');
    Route::post('ujian/{token}/ragu', [UjianController::class, 'tandaiRagu'])->name('ujian.ragu');
    Route::post('ujian/{token}/submit', [UjianController::class, 'submit'])->name('ujian.submit');
    Route::post('ujian/{token}/pelanggaran', [UjianController::class, 'catatPelanggaran'])->name('ujian.pelanggaran');
    Route::get('hasil/{token}', [UjianController::class, 'hasil'])->name('ujian.hasil');
});