<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Soal;
use App\Models\PaketUjian;
use App\Models\SesiUjian;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [

            // ══════════════════════════════════════════════
            // 1. STAT CARDS
            // ══════════════════════════════════════════════

            'totalSiswa' => User::role('siswa')
                // ->where('is_active', true)
                ->count(),

            'totalGuru' => User::role('guru')
                // ->where('is_active', true)
                ->count(),

            'totalSoal' => Soal::count(),

            'totalPaket' => PaketUjian::count(),

            // Paket yang sedang aktif dalam rentang waktu sekarang
            'ujianAktifHariIni' => PaketUjian::where('status', 'aktif')
                ->where('tanggal_mulai', '<=', now())
                ->where('tanggal_selesai', '>=', now())
                ->count(),

            // Siswa yang SEDANG mengerjakan ujian saat ini
            'siswaSedangUjian' => SesiUjian::where('status', 'berlangsung')
                ->count(),

            // ══════════════════════════════════════════════
            // 2. MONITORING REAL-TIME
            // ══════════════════════════════════════════════

            'monitoringUjian' => PaketUjian::where('status', 'aktif')
                ->where('tanggal_mulai', '<=', now())
                ->where('tanggal_selesai', '>=', now())
                ->with('mataPelajaran')
                ->withCount([
                    'sesiUjian as sedang_mengerjakan' => fn($q) =>
                        $q->where('status', 'berlangsung'),
                    'sesiUjian as sudah_submit' => fn($q) =>
                        $q->whereIn('status', ['selesai', 'timeout']),
                    'sesiUjian as total_peserta',
                ])
                ->orderByDesc('sedang_mengerjakan')
                ->get(),

            // ══════════════════════════════════════════════
            // 3. REKAP CEPAT
            // ══════════════════════════════════════════════

            // 5 paket terbaru yang sudah punya hasil
            'ujianTerbaru' => PaketUjian::with('mataPelajaran')
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

            // Siswa yang belum punya profil (cek via doesntHave karena
            // kolom is_profile_complete belum ada di migration saat ini)
            'siswaBelumLengkap' => User::role('siswa')
                ->doesntHave('profileSiswa')
                ->orderByDesc('created_at')
                ->limit(8)
                ->get(),

            // ══════════════════════════════════════════════
            // 4. CHART — Partisipasi ujian 8 minggu terakhir
            // ══════════════════════════════════════════════

            'chartData' => self::getChartPartisipasi(),
        ]);
    }

    // Bangun data chart: jumlah sesi per minggu (8 minggu terakhir)
    private static function getChartPartisipasi(): array
    {
        $mulaiDari = Carbon::now()->subWeeks(7)->startOfWeek();

        $raw = DB::table('sesi_ujian')
            ->selectRaw('YEAR(waktu_mulai) as tahun, WEEK(waktu_mulai) as minggu, COUNT(*) as total')
            ->where('waktu_mulai', '>=', $mulaiDari)
            ->whereNotNull('waktu_mulai')
            ->groupByRaw('YEAR(waktu_mulai), WEEK(waktu_mulai)')
            ->get()
            ->keyBy(fn($r) => $r->tahun . '-' . $r->minggu);

        $labels = [];
        $data   = [];

        for ($i = 7; $i >= 0; $i--) {
            $tgl      = Carbon::now()->subWeeks($i)->startOfWeek();
            $key      = $tgl->year . '-' . $tgl->weekOfYear;
            $labels[] = $i === 0 ? 'Minggu ini' : $tgl->format('d M');
            $data[]   = isset($raw[$key]) ? (int) $raw[$key]->total : 0;
        }

        return compact('labels', 'data');
    }
}