<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\PaketUjian;
use App\Models\SesiUjian;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        $user    = auth()->user()->load(['profileSiswa.kelas']);
        $siswaId = $user->id;
        $kelasId = $user->profileSiswa?->kelas_id;

        return view('siswa.dashboard', [

            'user' => $user,

            // ══════════════════════════════════════════════
            // 1. RINGKASAN PRIBADI (stat cards)
            // ══════════════════════════════════════════════

            // Total ujian yang sudah diselesaikan
            'totalUjianSelesai' => SesiUjian::where('siswa_id', $siswaId)
                ->whereIn('status', ['selesai', 'timeout'])
                ->count(),

            // Rata-rata nilai semua ujian (dibulatkan 1 desimal)
            'rataNilai' => round(
                SesiUjian::where('siswa_id', $siswaId)
                    ->whereIn('status', ['selesai', 'timeout'])
                    ->avg('nilai') ?? 0,
                1
            ),

            // Nilai terbaik yang pernah diraih
            'nilaiTerbaik' => SesiUjian::where('siswa_id', $siswaId)
                ->whereIn('status', ['selesai', 'timeout'])
                ->max('nilai') ?? 0,

            // ══════════════════════════════════════════════
            // 2. UJIAN YANG BISA DIKERJAKAN SEKARANG
            // Aktif + dalam jadwal + BELUM selesai dikerjakan siswa ini
            // ══════════════════════════════════════════════

            'ujianTersedia' => PaketUjian::with('mataPelajaran')
                ->where('status', 'aktif')
                ->where('tanggal_mulai', '<=', now())
                ->where('tanggal_selesai', '>=', now())
                // Jangan tampilkan yang sudah selesai dikerjakan
                ->whereDoesntHave('sesiUjian', fn($q) =>
                $q->where('siswa_id', $siswaId)
                    ->whereIn('status', ['selesai', 'timeout']))
                // Jika paket punya kelas target, hanya tampilkan ke kelas yang sesuai
                ->where(function ($q) use ($kelasId) {
                    $q->doesntHave('kelas');         // paket tanpa filter kelas = untuk semua
                    //   ->orWhen($kelasId, fn($q2) =>  // paket dengan kelas = cek apakah kelas siswa cocok
                    //       $q2->whereHas('kelas', fn($q3) =>
                    //           $q3->where('kelas.id', $kelasId)));
                    if ($kelasId) {
                        $q->orWhereHas('kelas', function ($q) use ($kelasId) {
                            $q->where('kelas.id', $kelasId);
                        });
                    }
                })
                ->withCount('soal as jumlah_soal')
                ->orderBy('tanggal_selesai') // deadline terdekat di atas
                ->get()
                // Cek apakah ada sesi berlangsung (ujian yang sempat dibuka tapi belum disubmit)
                ->map(function ($paket) use ($siswaId) {
                    $paket->sesi_berlangsung = SesiUjian::where('siswa_id', $siswaId)
                        ->where('paket_ujian_id', $paket->id)
                        ->where('status', 'berlangsung')
                        ->first();
                    return $paket;
                }),

            // ══════════════════════════════════════════════
            // 3. UJIAN AKAN DATANG (belum dibuka)
            // ══════════════════════════════════════════════

            'ujianAkanDatang' => PaketUjian::with('mataPelajaran')
                ->where('status', 'aktif')
                ->where('tanggal_mulai', '>', now())
                ->where(function ($q) use ($kelasId) {
                    $q->doesntHave('kelas');
                    //   ->orWhen($kelasId, fn($q2) =>
                    //       $q2->whereHas('kelas', fn($q3) =>
                    //           $q3->where('kelas.id', $kelasId)));
                    if ($kelasId) {
                        $q->orWhereHas('kelas', function ($q) use ($kelasId) {
                            $q->where('kelas.id', $kelasId);
                        });
                    }
                })
                ->orderBy('tanggal_mulai')
                ->limit(5)
                ->get(),

            // ══════════════════════════════════════════════
            // 4. RIWAYAT UJIAN (5 terakhir + ranking per ujian)
            // ══════════════════════════════════════════════

            'riwayatUjian' => SesiUjian::where('siswa_id', $siswaId)
                ->whereIn('status', ['selesai', 'timeout'])
                ->with('paketUjian.mataPelajaran')
                ->orderByDesc('waktu_selesai')
                ->limit(5)
                ->get()
                // Hitung ranking siswa ini di tiap ujian (PHP side, cukup untuk 5 data)
                ->map(function ($sesi) use ($siswaId) {
                    // Ranking = jumlah peserta lain dengan nilai LEBIH TINGGI + 1
                    $sesi->ranking = SesiUjian::where('paket_ujian_id', $sesi->paket_ujian_id)
                        ->whereIn('status', ['selesai', 'timeout'])
                        ->where('nilai', '>', $sesi->nilai ?? 0)
                        ->count() + 1;

                    // Total peserta di paket itu (untuk tampilkan "Rank 3 dari 45")
                    $sesi->total_peserta = SesiUjian::where('paket_ujian_id', $sesi->paket_ujian_id)
                        ->whereIn('status', ['selesai', 'timeout'])
                        ->count();

                    $sesi->lulus = ($sesi->nilai ?? 0) >= 75;

                    return $sesi;
                }),

            // ══════════════════════════════════════════════
            // 5. DATA TREN NILAI (untuk chart perkembangan)
            // Ambil 10 ujian terakhir, urutkan dari yang lama
            // ══════════════════════════════════════════════

            'chartTren' => self::getChartTren($siswaId),
        ]);
    }

    // Bangun data chart tren nilai siswa (10 ujian terakhir)
    // Return: ['labels' => [...], 'data' => [...]]
    private static function getChartTren(int $siswaId): array
    {
        $sesiList = SesiUjian::where('siswa_id', $siswaId)
            ->whereIn('status', ['selesai', 'timeout'])
            ->whereNotNull('nilai')
            ->with('paketUjian:id,nama')
            ->orderByDesc('waktu_selesai')
            ->limit(10)
            ->get()
            ->reverse()  // balik urutannya: terlama di kiri, terbaru di kanan
            ->values();

        return [
            'labels' => $sesiList->map(
                fn($s) =>
                \Str::limit($s->paketUjian?->nama ?? 'Ujian', 15)
            )->toArray(),
            'data' => $sesiList->pluck('nilai')->map(fn($n) => round($n, 1))->toArray(),
        ];
    }
}
