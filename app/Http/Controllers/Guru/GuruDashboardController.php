<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Soal;
use App\Models\PaketUjian;
use App\Models\SesiUjian;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Support\CacheKey;
use Carbon\Carbon;

class GuruDashboardController extends Controller
{
    public function index()
    {
        $guruId = Auth::id();

        return view('guru.dashboard', [

            'totalSoal' => Cache::remember(
                CacheKey::guruStatSoal($guruId),
                now()->addMinutes(CacheKey::TTL_MEDIUM),
                fn() => Soal::where('guru_id', $guruId)->count()
            ),

            'totalPaket' => Cache::remember(
                CacheKey::guruStatPaket($guruId),
                now()->addMinutes(CacheKey::TTL_MEDIUM),
                fn() => PaketUjian::where('guru_id', $guruId)->count()
            ),

            'paketAktif' => PaketUjian::where('guru_id', $guruId)
                ->where('status', 'aktif')
                ->where('tanggal_mulai', '<=', now())
                ->where('tanggal_selesai', '>=', now())
                ->count(),

            'totalSiswaIkut' => Cache::remember(
                CacheKey::guruStatSiswaIkut($guruId),
                now()->addMinutes(CacheKey::TTL_MEDIUM),
                fn() => SesiUjian::whereHas('paketUjian', fn($q) =>
                $q->where('guru_id', $guruId))
                    ->distinct('siswa_id')
                    ->count('siswa_id')
            ),

            'siswaSedangUjian' => SesiUjian::whereHas('paketUjian', fn($q) =>
            $q->where('guru_id', $guruId))
                ->where('status', 'berlangsung')
                ->count(),

            'listPaketAktif' => PaketUjian::where('guru_id', $guruId)
                ->where('status', 'aktif')
                ->with(['mataPelajaran', 'kelas'])
                ->withCount([
                    'sesiUjian as total_peserta',
                    'sesiUjian as sedang_mengerjakan' => fn($q) =>
                    $q->where('status', 'berlangsung'),
                    'sesiUjian as sudah_selesai' => fn($q) =>
                    $q->whereIn('status', ['selesai', 'timeout']),
                    'soal as jumlah_soal',
                ])
                ->orderBy('tanggal_selesai')
                ->get(),

            'hasilTerbaru' => $this->rememberWithLock(
                CacheKey::guruHasilTerbaru($guruId),
                CacheKey::TTL_MEDIUM,
                fn() => PaketUjian::where('guru_id', $guruId)
                    ->with('mataPelajaran')
                    ->whereHas('sesiUjian', fn($q) =>
                    $q->whereIn('status', ['selesai', 'timeout']))
                    ->withCount(['sesiUjian as jumlah_peserta' => fn($q) =>
                    $q->whereIn('status', ['selesai', 'timeout'])])
                    ->withAvg(['sesiUjian as rata_nilai' => fn($q) =>
                    $q->whereIn('status', ['selesai', 'timeout'])], 'nilai')
                    ->withMax(['sesiUjian as nilai_tertinggi' => fn($q) =>
                    $q->whereIn('status', ['selesai', 'timeout'])], 'nilai')
                    ->withMin(['sesiUjian as nilai_terendah' => fn($q) =>
                    $q->whereIn('status', ['selesai', 'timeout'])], 'nilai')
                    ->orderByDesc('tanggal_selesai')
                    ->limit(5)
                    ->get()
            ),

            'draftPaket' => $this->rememberWithLock(
                CacheKey::guruDraftPaket($guruId),
                CacheKey::TTL_MEDIUM,
                fn() => PaketUjian::where('guru_id', $guruId)
                    ->where('status', 'draft')
                    ->with('mataPelajaran')
                    ->withCount('soal as jumlah_soal')
                    ->orderByDesc('created_at')
                    ->get()
            ),
        ]);
    }
}
