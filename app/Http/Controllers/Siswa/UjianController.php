<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\PaketUjian;
use App\Models\SesiUjian;
use App\Models\JawabanSiswa;
use Carbon\Carbon;

class UjianController extends Controller
{
    public function index()
    {

        $user = auth()->user();
        $kelasId = $user->profileSiswa?->kelas_id;


        $now = Carbon::today()->toDateString();

        $paketUjian = PaketUjian::with(['mataPelajaran', 'guru', 'sesiSiswa'])
            ->withCount('soal')
            ->whereHas('kelas', function ($query) use ($kelasId) {
                $query->where('kelas_id', $kelasId);
            })
            ->where('status', 'aktif')
            ->whereDate('tanggal_mulai', '<=', $now)
            ->whereDate('tanggal_selesai', '>=', $now)
            ->latest()
            ->get();

        return view('siswa.ujian.index', compact('paketUjian'));
    }

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
            ->with('paketUjian')
            ->firstOrFail();

        if ($sesi->status === 'selesai' || $sesi->status === 'timeout') {
            return redirect()->route('siswa.ujian.hasil', $token);
        }

        // =========================================================================
        // PERBAIKAN TIMER: Menggunakan Hitungan Waktu Selesai yang Akurat
        // =========================================================================

        // 1. Pastikan waktu_mulai dibaca sebagai objek Carbon secara eksplisit
        $waktuMulai = \Carbon\Carbon::parse($sesi->waktu_mulai);

        // 2. Tentukan jam berapa ujian ini HARUS selesai (Waktu Mulai + Durasi Paket)
        $waktuSelesai = $waktuMulai->copy()->addMinutes($sesi->paketUjian->durasi);

        // 3. Hitung sisa waktu murni dalam detik dari SEKARANG menuju WAKTU SELESAI
        // Parameter false memastikan jika waktu lewat, hasilnya akan negatif
        $sisaWaktu = now()->diffInSeconds($waktuSelesai, false);

        // 4. Jika waktu habis (0 atau negatif), otomatis jalankan submit
        if ($sisaWaktu <= 0) {
            $this->prosesAutoSubmit($sesi);
            return redirect()->route('siswa.ujian.hasil', $token);
        }

        // =========================================================================

        // Ambil jawaban siswa (diurutkan berdasarkan id = urutan saat insert di mulai())
        $jawabList = JawabanSiswa::where('sesi_ujian_id', $sesi->id)
            ->orderBy('id')
            ->get()
            ->keyBy('soal_id');

        // Ambil daftar soal sesuai urutan jawaban
        $soalIds  = $jawabList->keys();
        $soalMap  = $sesi->paketUjian->soal()->whereIn('soal.id', $soalIds)->with('pilihanJawaban')->get()->keyBy('id');
        $soalList = $soalIds->map(fn($id) => $soalMap->get($id))->filter();

        return view('siswa.ujian.show', compact('sesi', 'soalList', 'jawabList', 'sisaWaktu'));
    }

    public function soal(string $token, int $nomor)
    {
        $sesi = SesiUjian::where('token', $token)
            ->where('siswa_id', auth()->id())
            ->with('paketUjian')
            ->firstOrFail();

        // Ambil daftar soal sesuai urutan yang sudah ditentukan saat mulai() (insert ke jawaban_siswa).
        // Urutan berdasarkan id jawaban_siswa menjamin konsistensi: sama antara show() dan soal().
        $soalIds = JawabanSiswa::where('sesi_ujian_id', $sesi->id)
            ->orderBy('id')
            ->pluck('soal_id');

        // Ambil soal ke-$nomor berdasarkan posisi di urutan tersebut
        $soalId = $soalIds->get($nomor - 1);
        if (!$soalId) abort(404);

        $soal = $sesi->paketUjian->soal()->with('pilihanJawaban')->find($soalId);
        if (!$soal) abort(404);

        $jawaban = JawabanSiswa::where('sesi_ujian_id', $sesi->id)
            ->where('soal_id', $soal->id)
            ->first();

        // ACAK JAWABAN: Jika paket mengaktifkan acak_jawaban, kocok urutan pilihan
        // menggunakan seed dari soal_id + sesi_id agar konsisten tiap reload halaman
        $pilihan = $soal->pilihanJawaban;
        if ($sesi->paketUjian->acak_jawaban) {
            // Seed deterministik agar urutan pilihan tidak berubah tiap refresh
            $seed = crc32($sesi->id . '-' . $soal->id);
            mt_srand($seed);
            $pilihan = $pilihan->shuffle();
        }

        return response()->json([
            'soal'    => [
                'id'     => $soal->id,
                'nomor'  => $nomor,
                'konten' => $soal->konten,
                'gambar' => $soal->gambar ? asset('storage/' . $soal->gambar) : null,
            ],
            'pilihan' => $pilihan->map(fn($p) => [
                'id'     => $p->id,
                'label'  => $p->label,
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
            ->firstOrFail();

        if ($sesi->status === 'berlangsung') {
            $this->prosesAutoSubmit($sesi);
        }

        return response()->json(['redirect' => route('siswa.ujian.hasil', $token)]);
    }

    private function prosesAutoSubmit(SesiUjian $sesi): void
    {
        if ($sesi->status === 'selesai') return;
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

    public function catatPelanggaran(Request $request, $token)
    {
        $sesi = SesiUjian::where('token', $token)
            ->where('siswa_id', auth()->id())
            ->firstOrFail();

        // Pastikan ujian masih berlangsung
        if ($sesi->status === 'berlangsung') {
            // Increment (tambah 1) jumlah pelanggaran di database          
            $sesi->increment('jumlah_pelanggaran');

            // Cek apakah sudah melewati batas (misal maksimal 3x toleransi, ke-3 diblokir)
            if ($sesi->jumlah_pelanggaran >= 3) {
                $this->prosesAutoSubmit($sesi);

                return response()->json([
                    'status' => 'blocked',
                    'message' => 'Ujian dihentikan karena melanggar batas aturan.'
                ]);
            }

            return response()->json([
                'status' => 'warned',
                'jumlah_pelanggaran' => $sesi->jumlah_pelanggaran,
                'message' => 'Pelanggaran tercatat.'
            ]);
        }

        return response()->json(['status' => 'ignored']);
    }
}
