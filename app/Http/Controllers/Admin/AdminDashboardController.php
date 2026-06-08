<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Soal;
use App\Models\PaketUjian;
use App\Models\SesiUjian;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Stat Cards
        $totalSiswa = User::role('siswa')->count();
        $totalGuru = User::role('guru')->count();
        $totalSoal = Soal::count();
        $totalPaket = PaketUjian::count();
        
        $ujianAktifHariIni = PaketUjian::where('status', 'aktif')
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->count();
            
        $siswaSedangUjian = SesiUjian::where('status', 'berlangsung')->count();

        // 2. Aktivitas & Monitoring (Ujian yang sedang berlangsung real-time)
        $monitoringUjian = PaketUjian::whereHas('sesiUjian', function($q) {
                $q->whereDate('created_at', today());
            })
            ->withCount([
                'sesiUjian as sedang_mengerjakan' => function($q) {
                    $q->where('status', 'berlangsung');
                },
                'sesiUjian as sudah_submit' => function($q) {
                    $q->where('status', 'selesai');
                }
            ])
            ->where('status', 'aktif')
            ->get()
            ->filter(function($paket) {
                return $paket->sedang_mengerjakan > 0 || $paket->sudah_submit > 0;
            });

        // 3. Rekap Cepat: 5 ujian terbaru selesai + rata-rata
        $rekapTerbaru = PaketUjian::whereHas('sesiUjian', function($q) {
                $q->where('status', 'selesai');
            })
            ->withAvg(['sesiUjian as rata_rata_nilai' => function($q) {
                $q->where('status', 'selesai');
            }], 'nilai')
            ->withCount(['sesiUjian as total_peserta' => function($q) {
                $q->where('status', 'selesai');
            }])
            ->latest('updated_at')
            ->take(5)
            ->get();

        // Siswa baru belum lengkap profilnya
        $siswaBaru = User::role('siswa')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSiswa', 'totalGuru', 'totalSoal', 'totalPaket', 
            'ujianAktifHariIni', 'siswaSedangUjian', 
            'monitoringUjian', 'rekapTerbaru', 'siswaBaru'
        ));
    }
}
