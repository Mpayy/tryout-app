<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\JawabanSiswa;
use App\Models\PaketUjian;
use App\Models\SesiUjian;
use App\Support\CacheKey;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UjianController extends Controller
{
    public function index()
    {

        $user = Auth::user();
        $kelasId = $user->profileSiswa?->kelas_id;
        $siswaId = $user->id;

        $tanggalAwal = Carbon::today()->toDateString();
        $paketUjian = Cache::remember(
            CacheKey::ujianTersediaKelas($kelasId, $tanggalAwal),
            CacheKey::TTL_MEDIUM,
            fn() => PaketUjian::with(['mataPelajaran', 'guru', 'sesiSiswa'])
                ->withCount('soal')
                ->whereHas('kelas', function ($query) use ($kelasId) {
                    $query->where('kelas_id', $kelasId);
                })
                ->where('status', 'aktif')
                ->whereDate('tanggal_mulai', '<=', $tanggalAwal)
                ->whereDate('tanggal_selesai', '>=', $tanggalAwal)
                ->latest()
                ->get()
        );

        $paketIds = $paketUjian->pluck('id');

        $sesiMap = SesiUjian::where('siswa_id', $siswaId)
            ->whereIn('paket_ujian_id', $paketIds)
            ->get()
            ->keyBy('paket_ujian_id'); // O(1) lookup di bawah

        // ── Gabungkan: tempel sesi ke paket di PHP, tanpa query tambahan ─
        $paketUjian = $paketUjian->map(function ($paket) use ($sesiMap) {
            $paket->sesiSiswa = $sesiMap->get($paket->id); // null kalau belum punya sesi
            return $paket;
        });

        return view('siswa.ujian.index', compact('paketUjian'));
    }

    public function mulai(PaketUjian $paket)
    {
        $siswaId = Auth::id();
        $kelasId = Auth::user()->profileSiswa?->kelas_id;

        if (!$paket->kelas()->where('kelas_id', $kelasId)->exists()) {
            return redirect()->route('siswa.ujian.index')->with('error', 'Akses ditolak. Ujian ini bukan untuk kelas Anda.');
        }

        $sesiAktif = SesiUjian::where('siswa_id', $siswaId)
            ->where('paket_ujian_id', $paket->id)
            ->whereIn('status', ['menunggu', 'berlangsung'])
            ->first();

        if ($paket->status !== 'aktif' || now()->lt($paket->tanggal_mulai) || now()->gt($paket->tanggal_selesai)) {
            return redirect()->route('siswa.ujian.index')->with('error', 'Ujian ini sedang tidak aktif atau sudah selesai.');
        }

        if ($sesiAktif) {
            return redirect()->route('siswa.ujian.show', $sesiAktif->token);
        }

        // Cek apakah sudah pernah selesai
        $sesiSelesai = SesiUjian::where('siswa_id', $siswaId)
            ->where('paket_ujian_id', $paket->id)
            ->where('status', 'selesai')
            ->first();

        if ($sesiSelesai) {
            return redirect()->route('siswa.ujian.hasil', $sesiSelesai->token)
                ->with('info', 'Anda sudah menyelesaikan ujian ini.');
        }

        $sesi = DB::transaction(function () use ($siswaId, $paket) {

            // 1. Buat sesi
            $sesi = SesiUjian::create([
                'siswa_id'       => $siswaId,
                'paket_ujian_id' => $paket->id,
                'token'          => Str::uuid(),
                'waktu_mulai'    => now(),
                'status'         => 'berlangsung',
            ]);

            // 2. Ambil & susun soal
            $soalQuery = $paket->soal();
            if ($paket->acak_soal) {
                $soalQuery->inRandomOrder();
            } else {
                $soalQuery->orderBy('paket_ujian_soal.nomor_urut');
            }

            $soalIds = $soalQuery->pluck('soal.id');
            $jawaban = $soalIds->map(fn($id) => [
                'sesi_ujian_id'      => $sesi->id,
                'soal_id'            => $id,
                'pilihan_jawaban_id' => null,
                'is_ragu'            => false,
                'created_at'         => now(),
                'updated_at'         => now(),
            ])->toArray();

            // 3. Insert jawaban (Request lain tidak akan bisa mengintip sebelum baris ini selesai)
            JawabanSiswa::insert($jawaban);

            $berhasil = true;

            if ($berhasil) {
                Cache::forget(CacheKey::guruStatSiswaIkut($paket->guru_id));
            }

            return $sesi;
        });

        return redirect()->route('siswa.ujian.show', $sesi->token);
    }

    public function show(string $token)
    {
        $siswaId = Auth::id();

        $sesi = SesiUjian::where('token', $token)
            ->where('siswa_id', $siswaId)
            ->with('paketUjian')
            ->firstOrFail();

        if ($sesi->status === 'selesai' || $sesi->status === 'timeout') {
            return redirect()->route('siswa.ujian.hasil', $token);
        }

        $waktuMulai = \Carbon\Carbon::parse($sesi->waktu_mulai);

        $waktuSelesai = $waktuMulai->copy()->addMinutes($sesi->paketUjian->durasi);

        $sisaWaktu = now()->diffInSeconds($waktuSelesai, false);

        if ($sisaWaktu <= 0) {
            $this->prosesAutoSubmit($sesi);
            return redirect()->route('siswa.ujian.hasil', $token);
        }

        $jawabList = JawabanSiswa::where('sesi_ujian_id', $sesi->id)
            ->orderBy('id')
            ->get()
            ->keyBy('soal_id');

        $soalIds  = $jawabList->keys();
        // $soalMap  = $sesi->paketUjian->soal()->whereIn('soal.id', $soalIds)->with('pilihanJawaban')->get()->keyBy('id');
        $paketId = $sesi->paket_ujian_id;
        $soalMap = $this->rememberWithLock(
            CacheKey::paketSoal($paketId),
            CacheKey::TTL_LONG,
            fn() => $sesi->paketUjian->soal()->with('pilihanJawaban')->get()->keyBy('id')
        );

        $soalList = $soalIds->map(fn($id) => $soalMap->get($id))->filter();

        return view('siswa.ujian.show', compact('sesi', 'soalList', 'jawabList', 'sisaWaktu'));
    }

    public function soal(string $token, int $nomor)
    {
        $siswaId = Auth::id();

        $sesi = SesiUjian::where('token', $token)
            ->where('siswa_id', $siswaId)
            ->with('paketUjian')
            ->firstOrFail();

        $soalIds = JawabanSiswa::where('sesi_ujian_id', $sesi->id)
            ->orderBy('id')
            ->pluck('soal_id');

        $soalId = $soalIds->get($nomor - 1);
        if (!$soalId) abort(404);

        // $soal = $sesi->paketUjian->soal()->with('pilihanJawaban')->find($soalId);
        $soal = $this->rememberWithLock(
            CacheKey::soalLengkap($soalId),
            CacheKey::TTL_LONG,
            fn() => $sesi->paketUjian->soal()->with('pilihanJawaban')->find($soalId)
        );
        if (!$soal) abort(404);

        $jawaban = JawabanSiswa::where('sesi_ujian_id', $sesi->id)
            ->where('soal_id', $soal->id)
            ->first();

        $pilihan = $soal->pilihanJawaban;

        if ($sesi->paketUjian->acak_jawaban) {
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
        $siswaId = Auth::id();

        $request->validate([
            'soal_id'            => 'required|integer|exists:soal,id',
            'pilihan_jawaban_id' => 'nullable|integer|exists:pilihan_jawaban,id',
        ]);

        $sesi = SesiUjian::where('token', $token)
            ->where('siswa_id', $siswaId)
            ->where('status', 'berlangsung')
            ->firstOrFail();

        $jawaban = JawabanSiswa::where('sesi_ujian_id', $sesi->id)
            ->where('soal_id', $request->soal_id)
            ->firstOrFail();

        $jawaban->update([
            'pilihan_jawaban_id' => $request->pilihan_jawaban_id
        ]);

        return response()->json(['status' => 'ok']);
    }

    public function tandaiRagu(Request $request, string $token)
    {
        $siswaId = Auth::id();

        $request->validate([
            'soal_id' => 'required|integer|exists:soal,id',
            'is_ragu' => 'required|boolean',
        ]);

        $sesi = SesiUjian::where('token', $token)
            ->where('siswa_id', $siswaId)
            ->where('status', 'berlangsung')
            ->firstOrFail();

        $jawaban = JawabanSiswa::where('sesi_ujian_id', $sesi->id)
            ->where('soal_id', $request->soal_id)
            ->firstOrFail();

        $jawaban->update(['is_ragu' => $request->is_ragu]);

        return response()->json(['status' => 'ok']);
    }

    public function submit(string $token)
    {
        $siswaId = Auth::id();

        $sesi = SesiUjian::where('token', $token)
            ->where('siswa_id', $siswaId)
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

            $berhasil = true;

            if ($berhasil) {
                $sesi->loadMissing('paketUjian');
                $tanggalAwal = Carbon::today()->toDateString();

                Cache::forget(CacheKey::DASHBOARD_UJIAN_TERBARU);
                Cache::forget(CacheKey::rekapPaket($sesi->paket_ujian_id));
                Cache::forget("rekap_belum_ikut_{$sesi->paket_ujian_id}");
                Cache::forget(CacheKey::guruHasilTerbaru($sesi->paketUjian->guru_id));
                Cache::forget(CacheKey::guruStatSiswaIkut($sesi->paketUjian->guru_id));

                Cache::forget(CacheKey::siswaStats($sesi->siswa_id));
                Cache::forget(CacheKey::siswaRiwayat($sesi->siswa_id));
                Cache::forget(CacheKey::siswaChart($sesi->siswa_id));

                Cache::forget(CacheKey::ujianTersediaKelas($sesi->siswa_id, $tanggalAwal));
            }
        });
    }

    // Tambahkan ini di dalam class UjianController kamu

    public function hasil(string $token)
    {
        $siswaId = Auth::id();

        $sesi = SesiUjian::where('token', $token)
            ->where('siswa_id', $siswaId)
            ->where('status', 'selesai')
            ->with(['paketUjian.mataPelajaran'])
            ->firstOrFail();

        // 2. Kembalikan ke halaman view hasil ujian
        return view('siswa.ujian.hasil', compact('sesi'));
    }

    public function catatPelanggaran(Request $request, string $token)
    {
        $siswaId = Auth::id();

        $sesi = SesiUjian::where('token', $token)
            ->where('siswa_id', $siswaId)
            ->firstOrFail();

        if ($sesi->status === 'berlangsung') {
            $sesi->increment('jumlah_pelanggaran');

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
