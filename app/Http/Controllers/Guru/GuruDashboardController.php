<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Soal;
use App\Models\PaketUjian;
use App\Models\SesiUjian;
use App\Models\User;

class GuruDashboardController extends Controller
{
    public function index()
    {
        $guruId = auth()->id();

        return view('guru.dashboard', [

            // ══════════════════════════════════════════════
            // 1. STAT CARDS
            // ══════════════════════════════════════════════

            // Total soal yang dibuat guru ini
            'totalSoal' => Soal::where('guru_id', $guruId)->count(),

            // Total semua paket ujian milik guru ini
            'totalPaket' => PaketUjian::where('guru_id', $guruId)->count(),

            // Paket yang sedang aktif
            'paketAktif' => PaketUjian::where('guru_id', $guruId)
                ->where('status', 'aktif')
                ->where('tanggal_mulai', '<=', now())
                ->where('tanggal_selesai', '>=', now())
                ->count(),

            // Total unik siswa yang pernah ikut ujian buatan guru ini
            // distinct() agar siswa yang ikut banyak paket dihitung 1x
            'totalSiswaIkut' => SesiUjian::whereHas('paketUjian', fn($q) =>
                    $q->where('guru_id', $guruId))
                ->distinct('siswa_id')
                ->count('siswa_id'),

            // Siswa sedang mengerjakan ujian guru ini (live)
            'siswaSedangUjian' => SesiUjian::whereHas('paketUjian', fn($q) =>
                    $q->where('guru_id', $guruId))
                ->where('status', 'berlangsung')
                ->count(),

            // ══════════════════════════════════════════════
            // 2. PAKET UJIAN AKTIF (kartu-kartu yang bisa diklik)
            // ══════════════════════════════════════════════

            'listPaketAktif' => PaketUjian::where('guru_id', $guruId)
                ->where('status', 'aktif')
                ->with(['mataPelajaran', 'kelas'])
                ->withCount([
                    // Total semua sesi
                    'sesiUjian as total_peserta',

                    // Sedang mengerjakan
                    'sesiUjian as sedang_mengerjakan' => fn($q) =>
                        $q->where('status', 'berlangsung'),

                    // Sudah selesai
                    'sesiUjian as sudah_selesai' => fn($q) =>
                        $q->whereIn('status', ['selesai', 'timeout']),

                    // Jumlah soal dalam paket ini
                    'soal as jumlah_soal',
                ])
                ->orderBy('tanggal_selesai')  // yang paling dekat deadline muncul duluan
                ->get(),

            // ══════════════════════════════════════════════
            // 3. HASIL UJIAN TERBARU (5 paket terakhir selesai)
            // ══════════════════════════════════════════════

            'hasilTerbaru' => PaketUjian::where('guru_id', $guruId)
                ->with('mataPelajaran')
                ->whereHas('sesiUjian', fn($q) =>
                    $q->whereIn('status', ['selesai', 'timeout']))
                ->withCount([
                    'sesiUjian as jumlah_peserta' => fn($q) =>
                        $q->whereIn('status', ['selesai', 'timeout']),
                ])
                ->withAvg(
                    ['sesiUjian as rata_nilai' => fn($q) =>
                        $q->whereIn('status', ['selesai', 'timeout'])],
                    'nilai'
                )
                ->withMax(
                    ['sesiUjian as nilai_tertinggi' => fn($q) =>
                        $q->whereIn('status', ['selesai', 'timeout'])],
                    'nilai'
                )
                ->withMin(
                    ['sesiUjian as nilai_terendah' => fn($q) =>
                        $q->whereIn('status', ['selesai', 'timeout'])],
                    'nilai'
                )
                ->orderByDesc('tanggal_selesai')
                ->limit(5)
                ->get(),

            // ══════════════════════════════════════════════
            // 4. PAKET DRAFT (pengingat yang belum dipublish)
            // ══════════════════════════════════════════════

            'draftPaket' => PaketUjian::where('guru_id', $guruId)
                ->where('status', 'draft')
                ->with('mataPelajaran')
                ->withCount('soal as jumlah_soal')
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }
}