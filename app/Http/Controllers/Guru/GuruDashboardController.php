<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Soal;
use App\Models\PaketUjian;
use App\Models\SesiUjian;

class GuruDashboardController extends Controller
{
    public function index()
    {
        $guruId = auth()->id();

        // 1. Stat Cards
        $totalSoal = Soal::where('guru_id', $guruId)->count();
        $totalPaket = PaketUjian::where('guru_id', $guruId)->count();
        $paketAktif = PaketUjian::where('guru_id', $guruId)->where('status', 'aktif')->count();
        $totalSiswaIkut = SesiUjian::whereHas('paketUjian', function($q) use ($guruId) {
            $q->where('guru_id', $guruId);
        })->count();

        // 2. Paket Ujian Aktif
        $listPaketAktif = PaketUjian::where('guru_id', $guruId)
            ->where('status', 'aktif')
            ->withCount([
                'sesiUjian as total_peserta',
                'sesiUjian as selesai_count' => function($q) {
                    $q->where('status', 'selesai');
                }
            ])
            ->latest()
            ->get();

        // 3. Hasil Ujian Terbaru (5 terbaru)
        $hasilTerbaru = PaketUjian::where('guru_id', $guruId)
            ->whereHas('sesiUjian', function($q) {
                $q->where('status', 'selesai');
            })
            ->withCount(['sesiUjian as total_peserta' => function($q) {
                $q->where('status', 'selesai');
            }])
            ->withAvg(['sesiUjian as rata_rata_nilai' => function($q) {
                $q->where('status', 'selesai');
            }], 'nilai')
            ->withMax(['sesiUjian as nilai_tertinggi' => function($q) {
                $q->where('status', 'selesai');
            }], 'nilai')
            ->withMin(['sesiUjian as nilai_terendah' => function($q) {
                $q->where('status', 'selesai');
            }], 'nilai')
            ->latest('updated_at')
            ->take(5)
            ->get();

        // 4. Draft Paket
        $draftPaket = PaketUjian::where('guru_id', $guruId)
            ->where('status', 'draft')
            ->latest()
            ->get();

        return view('guru.dashboard', compact(
            'totalSoal', 'totalPaket', 'paketAktif', 'totalSiswaIkut',
            'listPaketAktif', 'hasilTerbaru', 'draftPaket'
        ));
    }
}
