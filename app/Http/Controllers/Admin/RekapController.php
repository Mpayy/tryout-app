<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaketUjian;
use App\Models\SesiUjian;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Support\CacheKey;

class RekapController extends Controller
{
    // ═══════════════════════════════════════════════════════
    // INDEX — Daftar semua paket ujian yang punya hasil
    // Admin bisa lihat rekap dari SEMUA guru
    // ═══════════════════════════════════════════════════════
    public function index(Request $request)
    {
        $query = PaketUjian::with(['guru', 'mataPelajaran'])
            ->withCount([
                // Total peserta yang sudah punya sesi (apapun statusnya)
                'sesiUjian as total_peserta',

                // Hanya yang sudah selesai (selesai + timeout)
                'sesiUjian as total_selesai' => fn($q) =>
                $q->whereIn('status', ['selesai', 'timeout']),

                // Yang masih berlangsung
                'sesiUjian as sedang_berlangsung' => fn($q) =>
                $q->where('status', 'berlangsung'),
            ])
            ->withAvg(
                ['sesiUjian as rata_nilai' => fn($q) =>
                $q->whereIn('status', ['selesai', 'timeout'])],
                'nilai'
            )
            // Hanya tampilkan paket yang minimal punya 1 sesi
            ->has('sesiUjian');

        // Filter by status paket
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by mata pelajaran
        if ($request->filled('mapel')) {
            $query->where('mata_pelajaran_id', $request->mapel);
        }

        // Filter by guru
        if ($request->filled('guru')) {
            $query->where('guru_id', $request->guru);
        }

        // Search nama paket
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $pakets = $query->orderByDesc('tanggal_selesai')->paginate(15)->withQueryString();

        // Data untuk filter dropdown
        $mapels = \App\Models\MataPelajaran::orderBy('nama')->get();
        $gurus  = \App\Models\User::role('guru')->orderBy('name')->get();

        return view('admin.rekap.index', compact('pakets', 'mapels', 'gurus'));
    }

    // ═══════════════════════════════════════════════════════
    // SHOW — Detail rekap 1 paket ujian
    // ═══════════════════════════════════════════════════════
    public function show(Request $request, PaketUjian $paket)
    {
        // Load relasi yang dibutuhkan
        $paket->load(['guru', 'mataPelajaran', 'kelas']);

        // ── Ambil semua sesi dari cache (TANPA filter kelas) ──
        // Key unik per paket agar paket lain tidak saling timpa
        $semuaSesiLengkap = $this->rememberWithLock(
            CacheKey::rekapPaket($paket->id),
            CacheKey::TTL_LONG,
            fn() => SesiUjian::where('paket_ujian_id', $paket->id)
                ->whereIn('status', ['selesai', 'timeout'])
                ->with([
                    'siswa',
                    'siswa.profileSiswa',
                    'siswa.profileSiswa.kelas',
                ])
                ->orderByDesc('nilai')
                ->orderBy('waktu_selesai')
                ->get()
        );

        // ── Filter kelas di PHP dari collection (bukan query ulang ke DB) ──
        // Ini kuncinya: data sudah di-cache, filter hanya memotong collection
        $semuaSesi = $request->filled('kelas')
            ? $semuaSesiLengkap->filter(
                fn($s) =>
                $s->siswa?->profileSiswa?->kelas_id == $request->kelas
            )->values()
            : $semuaSesiLengkap;

        // ── Ranking (dihitung dari collection yang sudah difilter) ──
        $ranking = $semuaSesi->values()->map(function ($sesi, $index) {
            $sesi->ranking      = $index + 1;
            $sesi->durasi_menit = $sesi->waktu_mulai && $sesi->waktu_selesai
                ? round($sesi->waktu_mulai->diffInSeconds($sesi->waktu_selesai) / 60, 1)
                : null;
            $sesi->lulus = $sesi->nilai !== null && $sesi->nilai >= 75;
            return $sesi;
        });

        // ── Statistik keseluruhan ──
        $totalPeserta   = $semuaSesi->count();
        $nilaiList      = $semuaSesi->pluck('nilai')->filter();

        $statistik = [
            'total_peserta'  => $totalPeserta,
            'total_lulus'    => $semuaSesi->where('lulus', true)->count(),
            'total_tidak_lulus' => $semuaSesi->where('lulus', false)->count(),
            'rata_rata'      => $nilaiList->avg() ? round($nilaiList->avg(), 2) : 0,
            'nilai_tertinggi' => $nilaiList->max() ?? 0,
            'nilai_terendah' => $nilaiList->min() ?? 0,
            'pct_lulus'      => $totalPeserta > 0
                ? round(($semuaSesi->where('lulus', true)->count() / $totalPeserta) * 100, 1)
                : 0,
        ];

        // ── Distribusi nilai (untuk chart/tabel) ──
        // Kelompokkan ke dalam rentang 10 poin: 0-9, 10-19, ..., 90-100
        $distribusi = collect(range(0, 9))->mapWithKeys(function ($i) use ($semuaSesi) {
            $min   = $i * 10;
            $max   = $i === 9 ? 100 : ($min + 9);
            $label = $i === 9 ? '90-100' : "{$min}-{$max}";
            $count = $semuaSesi->filter(
                fn($s) =>
                $s->nilai !== null && $s->nilai >= $min && $s->nilai <= $max
            )->count();
            return [$label => $count];
        });

        // ── Statistik per kelas ──
        $perKelas = $semuaSesi->groupBy(
            fn($s) =>
            $s->siswa->profileSiswa?->kelas?->nama ?? 'Tidak Ada Kelas'
        )->map(function ($group, $namaKelas) {
            $nilais = $group->pluck('nilai')->filter();
            return [
                'nama_kelas'  => $namaKelas,
                'jumlah'      => $group->count(),
                'rata_rata'   => $nilais->avg() ? round($nilais->avg(), 2) : 0,
                'tertinggi'   => $nilais->max() ?? 0,
                'terendah'    => $nilais->min() ?? 0,
                'lulus'       => $group->where('lulus', true)->count(),
            ];
        })->values();

        // ── Data kelas untuk filter dropdown ──
        $kelasOptions = Cache::remember(
            CacheKey::ALL_KELAS,
            now()->addMinutes(CacheKey::TTL_LONG),
            fn() => Kelas::orderBy('nama')->get()
        );

        // ── Juga sertakan data siswa yang BELUM ikut (jika paket punya kelas tertentu) ──
        $belumIkut = collect();
        if ($paket->kelas->isNotEmpty()) {
            $belumIkut = $this->rememberWithLock(
                "rekap_belum_ikut_{$paket->id}",
                CacheKey::TTL_LONG,
                function () use ($paket, $semuaSesiLengkap) {
                    // Pakai $semuaSesiLengkap (bukan $semuaSesi) agar
                    // siswa yang ada di kelas lain tidak ikut dianggap "belum ikut"
                    $sudahIkutIds = $semuaSesiLengkap->pluck('siswa_id');
                    return \App\Models\User::role('siswa')
                        ->whereHas('profileSiswa', fn($q) =>
                        $q->whereIn('kelas_id', $paket->kelas->pluck('id')))
                        ->whereNotIn('id', $sudahIkutIds)
                        ->with('profileSiswa.kelas')
                        ->get();
                }
            );
        }

        return view('admin.rekap.show', compact(
            'paket',
            'ranking',
            'statistik',
            'distribusi',
            'perKelas',
            'kelasOptions',
            'belumIkut',
        ));
    }
}
