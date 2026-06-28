<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Soal;
use App\Models\PaketUjian;
use App\Models\SesiUjian;
use App\Support\CacheKey;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalSiswa' => Cache::remember(
                CacheKey::STAT_TOTAL_SISWA,
                now()->addMinutes(CacheKey::TTL_MEDIUM),
                fn() => User::role('siswa')->count()
            ),

            'totalGuru' => Cache::remember(
                CacheKey::STAT_TOTAL_GURU,
                now()->addMinutes(CacheKey::TTL_MEDIUM),
                fn() => User::role('guru')->count()
            ),

            'totalSoal' => Cache::remember(
                CacheKey::STAT_TOTAL_SOAL,
                now()->addMinutes(CacheKey::TTL_MEDIUM),
                fn() => Soal::count()
            ),

            'totalPaket' => Cache::remember(
                CacheKey::STAT_TOTAL_PAKET,
                now()->addMinutes(CacheKey::TTL_MEDIUM),
                fn() => PaketUjian::count()
            ),

            'ujianAktifHariIni' => PaketUjian::where('status', 'aktif')
                ->where('tanggal_mulai', '<=', now())
                ->where('tanggal_selesai', '>=', now())
                ->count(),
            
            'siswaSedangUjian' => SesiUjian::where('status', 'berlangsung')
                ->count(),

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

            'ujianTerbaru' => $this->rememberWithLock(
                CacheKey::DASHBOARD_UJIAN_TERBARU,
                CacheKey::TTL_MEDIUM,
                fn() => PaketUjian::with('mataPelajaran')
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
                ->get()
            ),

            'chartData' => $this->rememberWithLock(
                CacheKey::CHART_PARTISIPASI,
                CacheKey::TTL_LONG,
                fn() => self::getChartPartisipasi()
            ),

        ]);
    }

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
