<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\PaketUjian;
use App\Models\SesiUjian;
use Illuminate\Support\Facades\Auth;
use App\Support\CacheKey;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load(['profileSiswa.kelas']);
        $siswaId = $user->id;
        $kelasId = $user->profileSiswa?->kelas_id;

        $sesisBerlangsung = SesiUjian::where('siswa_id', $siswaId)
            ->where('status', 'berlangsung')
            ->get()
            ->keyBy('paket_ujian_id');

        return view('siswa.dashboard', [

            'user' => $user,

            'statPribadi' => Cache::remember(
                CacheKey::siswaStats($siswaId),
                now()->addMinutes(CacheKey::TTL_MEDIUM),
                function () use ($siswaId) {
                    $stat = SesiUjian::where('siswa_id', $siswaId)
                        ->whereIn('status', ['selesai', 'timeout'])
                        ->selectRaw('
                            COUNT(*)    AS total_ujian,
                            ROUND(AVG(nilai), 1) AS rata_nilai,
                            MAX(nilai)  AS nilai_terbaik
                        ')
                        ->first();

                    return [
                        'totalUjianSelesai' => (int) ($stat->total_ujian  ?? 0),
                        'rataNilai'         => (float) ($stat->rata_nilai ?? 0),
                        'nilaiTerbaik'      => (float) ($stat->nilai_terbaik ?? 0),
                    ];
                }
            ),

            'ujianTersedia' => PaketUjian::with('mataPelajaran')
                ->where('status', 'aktif')
                ->where('tanggal_mulai', '<=', now())
                ->where('tanggal_selesai', '>=', now())
                ->whereDoesntHave('sesiUjian', fn($q) =>
                $q->where('siswa_id', $siswaId)
                    ->whereIn('status', ['selesai', 'timeout']))
                ->where(function ($q) use ($kelasId) {
                    $q->doesntHave('kelas');
                    if ($kelasId) {
                        $q->orWhereHas('kelas', fn($q2) =>
                        $q2->where('kelas.id', $kelasId));
                    }
                })
                ->withCount('soal as jumlah_soal')
                ->orderBy('tanggal_selesai')
                ->get()
                ->map(function ($paket) use ($sesisBerlangsung) {
                    $paket->sesi_berlangsung = $sesisBerlangsung->get($paket->id);
                    return $paket;
                }),

            'ujianAkanDatang' => PaketUjian::with('mataPelajaran')
                ->where('status', 'aktif')
                ->where('tanggal_mulai', '>', now())
                ->where(function ($q) use ($kelasId) {
                    $q->doesntHave('kelas');
                    if ($kelasId) {
                        $q->orWhereHas('kelas', fn($q2) =>
                        $q2->where('kelas.id', $kelasId));
                    }
                })
                ->orderBy('tanggal_mulai')
                ->limit(5)
                ->get(),

            'riwayatUjian' => Cache::remember(
                CacheKey::siswaRiwayat($siswaId),
                now()->addMinutes(CacheKey::TTL_MEDIUM),
                function () use ($siswaId) {
                    $sesiList = SesiUjian::where('siswa_id', $siswaId)
                        ->whereIn('status', ['selesai', 'timeout'])
                        ->with('paketUjian.mataPelajaran')
                        ->orderByDesc('waktu_selesai')
                        ->limit(5)
                        ->get();

                    $paketIds = $sesiList->pluck('paket_ujian_id');

                    $rankingData = SesiUjian::whereIn('paket_ujian_id', $paketIds)
                        ->whereIn('status', ['selesai', 'timeout'])
                        ->selectRaw('
                            paket_ujian_id,
                            COUNT(*) AS total_peserta
                        ')
                        ->groupBy('paket_ujian_id')
                        ->get()
                        ->keyBy('paket_ujian_id');

                    $siswaValues = $sesiList->pluck('nilai', 'paket_ujian_id');

                    $lebihTinggi = SesiUjian::whereIn('paket_ujian_id', $paketIds)
                        ->whereIn('status', ['selesai', 'timeout'])
                        ->where('siswa_id', '!=', $siswaId)
                        ->select('paket_ujian_id', DB::raw('COUNT(*) as jumlah'))
                        ->where(function ($q) use ($siswaValues) {
                            foreach ($siswaValues as $paketId => $nilai) {
                                $q->orWhere(function ($q2) use ($paketId, $nilai) {
                                    $q2->where('paket_ujian_id', $paketId)
                                        ->where('nilai', '>', $nilai ?? 0);
                                });
                            }
                        })
                        ->groupBy('paket_ujian_id')
                        ->get()
                        ->keyBy('paket_ujian_id');

                    return $sesiList->map(function ($sesi) use ($rankingData, $lebihTinggi) {
                        $sesi->ranking       = ($lebihTinggi->get($sesi->paket_ujian_id)?->jumlah ?? 0) + 1;
                        $sesi->total_peserta = $rankingData->get($sesi->paket_ujian_id)?->total_peserta ?? 1;
                        $sesi->lulus         = ($sesi->nilai ?? 0) >= 75;
                        return $sesi;
                    });
                }
            ),

            'chartTren' => Cache::remember(
                CacheKey::siswaChart($siswaId),
                now()->addMinutes(CacheKey::TTL_MEDIUM),
                fn() => self::getChartTren($siswaId)
            ),
        ]);
    }

    private static function getChartTren(int $siswaId): array
    {
        $sesiList = SesiUjian::where('siswa_id', $siswaId)
            ->whereIn('status', ['selesai', 'timeout'])
            ->whereNotNull('nilai')
            ->with('paketUjian:id,nama')
            ->orderByDesc('waktu_selesai')
            ->limit(10)
            ->get()
            ->reverse()
            ->values();

        return [
            'labels' => $sesiList->map(
                fn($s) =>
                Str::limit($s->paketUjian?->nama ?? 'Ujian', 15)
            )->toArray(),
            'data' => $sesiList->pluck('nilai')
                ->map(fn($n) => round($n, 1))
                ->toArray(),
        ];
    }
}
