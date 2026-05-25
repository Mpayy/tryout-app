<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaketUjian;
use App\Models\SesiUjian;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load('profileSiswa');
        $siswaId = $user->id;

        // 1. Stat Pribadi
        $totalUjian = SesiUjian::where('siswa_id', $siswaId)->where('status', 'selesai')->count();
        $rataNilai = SesiUjian::where('siswa_id', $siswaId)->where('status', 'selesai')->avg('nilai');

        // 2. Ujian Tersedia (Aktif, hari ini, belum selesai dikerjakan siswa)
        $ujianTersedia = PaketUjian::with('mataPelajaran')
            ->where('status', 'aktif')
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->whereDoesntHave('sesiUjian', function($q) use ($siswaId) {
                $q->where('siswa_id', $siswaId)->where('status', 'selesai');
            })
            ->get();

        // 3. Ujian Akan Datang (Aktif, jadwal di masa depan)
        $ujianAkanDatang = PaketUjian::with('mataPelajaran')
            ->where('status', 'aktif')
            ->whereDate('tanggal_mulai', '>', now())
            ->orderBy('tanggal_mulai', 'asc')
            ->take(5)
            ->get();

        // 4. Riwayat Ujian Terbaru (5 terakhir)
        $riwayatUjian = SesiUjian::with('paketUjian.mataPelajaran')
            ->where('siswa_id', $siswaId)
            ->where('status', 'selesai')
            ->latest('waktu_selesai')
            ->take(5)
            ->get();

        return view('siswa.dashboard', compact(
            'user', 'totalUjian', 'rataNilai', 
            'ujianTersedia', 'ujianAkanDatang', 'riwayatUjian'
        ));
    }
}
