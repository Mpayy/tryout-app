<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\PaketUjian;
use App\Models\SesiUjian;
use App\Models\JawabanSiswa;

class UjianController extends Controller
{
    public function index()
    {
        // Tampilkan daftar ujian yang aktif
        $paketUjian = PaketUjian::where('status', 'aktif')
            ->with(['mataPelajaran'])
            ->withCount('soal')
            ->latest()
            ->paginate(10);

        return view('siswa.ujian.index', compact('paketUjian'));
    }

    // app/Http/Controllers/Siswa/UjianController.php

    public function mulai(Request $request, PaketUjian $paket)
    {
        if ($paket->status !== 'aktif') {
            abort(403, 'Ujian ini sedang tidak aktif.');
        }

        // Cek apakah siswa sudah punya sesi aktif
        $sesiAktif = SesiUjian::where('siswa_id', auth()->id())
            ->where('paket_ujian_id', $paket->id)
            ->whereIn('status', ['menunggu', 'berlangsung'])
            ->first();

        if ($sesiAktif) {
            return redirect()->route('siswa.ujian.show', $sesiAktif->token);
        }

        // Cek apakah sudah pernah selesai
        $sesiSelesai = SesiUjian::where('siswa_id', auth()->id())
            ->where('paket_ujian_id', $paket->id)
            ->where('status', 'selesai')
            ->first();

        if ($sesiSelesai) {
            return redirect()->route('siswa.ujian.hasil', $sesiSelesai->token)
                ->with('info', 'Anda sudah menyelesaikan ujian ini.');
        }

        $sesi = SesiUjian::create([
            'siswa_id'       => auth()->id(),
            'paket_ujian_id' => $paket->id,
            'token'          => Str::uuid(),
            'waktu_mulai'    => now(),
            'status'         => 'berlangsung',
        ]);

        // Inisialisasi jawaban kosong untuk semua soal
        $soalQuery = $paket->soal();
        if ($paket->acak_soal) {
            $soalQuery->inRandomOrder();
        } else {
            $soalQuery->orderBy('paket_ujian_soal.nomor_urut');
        }
        
        $soalIds = $soalQuery->pluck('soal.id');
        $jawaban = $soalIds->map(fn($id) => [
            'sesi_ujian_id'     => $sesi->id,
            'soal_id'           => $id,
            'pilihan_jawaban_id' => null,
            'is_ragu'           => false,
            'created_at'        => now(),
            'updated_at'        => now(),
        ])->toArray();

        JawabanSiswa::insert($jawaban);

        return redirect()->route('siswa.ujian.show', $sesi->token);
    }

    public function show(string $token)
    {
        $sesi = SesiUjian::where('token', $token)
            ->where('siswa_id', auth()->id())
            ->with(['paketUjian.soal' => function ($q) {
                $q->orderBy('paket_ujian_soal.nomor_urut');
            }])
            ->firstOrFail();

        if ($sesi->status === 'selesai' || $sesi->status === 'timeout') {
            return redirect()->route('siswa.ujian.hasil', $token);
        }

        // Hitung waktu tersisa
        $durasi     = $sesi->paketUjian->durasi * 60; // konversi ke detik
        $sudahJalan = now()->diffInSeconds($sesi->waktu_mulai);
        $sisaWaktu  = max(0, $durasi - $sudahJalan);

        if ($sisaWaktu <= 0) {
            $this->prosesAutoSubmit($sesi);
            return redirect()->route('siswa.ujian.hasil', $token);
        }

        $soalList   = $sesi->paketUjian->soal;
        $jawabList  = JawabanSiswa::where('sesi_ujian_id', $sesi->id)
            ->get()
            ->keyBy('soal_id');

        return view('siswa.ujian.show', compact('sesi', 'soalList', 'jawabList', 'sisaWaktu'));
    }

    public function soal(string $token, int $nomor)
    {
        $sesi = SesiUjian::where('token', $token)
            ->where('siswa_id', auth()->id())
            ->firstOrFail();

        $soal = $sesi->paketUjian->soal()
            ->orderBy('paket_ujian_soal.nomor_urut')
            ->skip($nomor - 1)
            ->first();

        if (!$soal) abort(404);

        $jawaban = JawabanSiswa::where('sesi_ujian_id', $sesi->id)
            ->where('soal_id', $soal->id)
            ->first();

        return response()->json([
            'soal'    => [
                'id'      => $soal->id,
                'nomor'   => $nomor,
                'konten'  => $soal->konten,
                'gambar'  => $soal->gambar ? asset('storage/' . $soal->gambar) : null,
            ],
            'pilihan' => $soal->pilihanJawaban->map(fn($p) => [
                'id'    => $p->id,
                'label' => $p->label,
                'konten' => $p->konten,
            ]),
            'jawaban' => [
                'pilihan_id' => $jawaban?->pilihan_jawaban_id,
                'is_ragu'    => $jawaban?->is_ragu ?? false,
            ],
        ]);
    }

    public function jawab(Request $request, string $token)
    {
        $request->validate([
            'soal_id'            => 'required|integer|exists:soal,id',
            'pilihan_jawaban_id' => 'nullable|integer|exists:pilihan_jawaban,id',
        ]);

        $sesi = SesiUjian::where('token', $token)
            ->where('siswa_id', auth()->id())
            ->where('status', 'berlangsung')
            ->firstOrFail();

        JawabanSiswa::updateOrCreate(
            ['sesi_ujian_id' => $sesi->id, 'soal_id' => $request->soal_id],
            ['pilihan_jawaban_id' => $request->pilihan_jawaban_id]
        );

        return response()->json(['status' => 'ok']);
    }

    public function tandaiRagu(Request $request, string $token)
    {
        $request->validate([
            'soal_id' => 'required|integer|exists:soal,id',
            'is_ragu' => 'required|boolean',
        ]);

        $sesi = SesiUjian::where('token', $token)
            ->where('siswa_id', auth()->id())
            ->where('status', 'berlangsung')
            ->firstOrFail();

        JawabanSiswa::where('sesi_ujian_id', $sesi->id)
            ->where('soal_id', $request->soal_id)
            ->update(['is_ragu' => $request->is_ragu]);

        return response()->json(['status' => 'ok']);
    }

    public function submit(string $token)
    {
        $sesi = SesiUjian::where('token', $token)
            ->where('siswa_id', auth()->id())
            ->where('status', 'berlangsung')
            ->firstOrFail();

        $this->prosesAutoSubmit($sesi);

        return response()->json(['redirect' => route('siswa.ujian.hasil', $token)]);
    }

    private function prosesAutoSubmit(SesiUjian $sesi): void
    {
        DB::transaction(function () use ($sesi) {
            $jawaban = JawabanSiswa::where('sesi_ujian_id', $sesi->id)
                ->with('pilihanJawaban')
                ->get();

            $totalSoal  = $jawaban->count();
            $totalBenar = 0;
            $totalSalah = 0;
            $totalRagu  = $jawaban->where('is_ragu', true)->count();

            foreach ($jawaban as $j) {
                if ($j->pilihanJawaban && $j->pilihanJawaban->is_correct) {
                    $totalBenar++;
                } elseif ($j->pilihan_jawaban_id !== null) {
                    $totalSalah++;
                }
            }

            $nilai = $totalSoal > 0 ? round(($totalBenar / $totalSoal) * 100, 2) : 0;

            $sesi->update([
                'status'        => 'selesai',
                'waktu_selesai' => now(),
                'nilai'         => $nilai,
                'total_benar'   => $totalBenar,
                'total_salah'   => $totalSalah,
                'total_ragu'    => $totalRagu,
            ]);
        });
    }

    // Tambahkan ini di dalam class UjianController kamu

    public function hasil(string $token)
    {
        // 1. Cari sesi ujian yang sudah selesai berdasarkan token dan ID siswa
        $sesi = SesiUjian::where('token', $token)
            ->where('siswa_id', auth()->id())
            ->where('status', 'selesai') // Pastikan statusnya sudah selesai
            ->with(['paketUjian.mataPelajaran'])
            ->firstOrFail();

        // 2. Kembalikan ke halaman view hasil ujian
        return view('siswa.ujian.hasil', compact('sesi'));
    }
}
