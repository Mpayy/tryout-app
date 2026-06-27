<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaketUjian;
use App\Models\Soal;
use App\Models\Kelas;
use App\Http\Requests\PaketUjianRequest;
use App\Services\PaketUjianService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Support\CacheKey;
use Illuminate\Validation\Rule;

class PaketUjianController extends Controller
{
    public function __construct(
        protected PaketUjianService $paketUjianService
    ) {}


    public function index()
    {
        $guru = Auth::user();
        $paketUjian = PaketUjian::where('guru_id', $guru->id)
            ->with(['mataPelajaran', 'kelas'])
            ->withCount('soal')
            ->paginate(5);

        $mataPelajaranGuru = Cache::remember(CacheKey::mataPelajaranGuru($guru->id), now()->addMinutes(CacheKey::TTL_LONG), fn() => $guru->load('profileGuru.mataPelajarans')->profileGuru->mataPelajarans);

        $daftarKelas = Cache::remember(CacheKey::ALL_KELAS, now()->addMinutes(CacheKey::TTL_LONG), fn() => Kelas::all());

        return view('guru.paket-ujian.index', compact('paketUjian', 'mataPelajaranGuru', 'daftarKelas'));
    }

    public function store(PaketUjianRequest $request)
    {
        $guruId = Auth::id();

        $data = $request->validated();

        $data['acak_soal']    = $request->boolean('acak_soal');
        $data['acak_jawaban'] = $request->boolean('acak_jawaban');

        $this->paketUjianService->createPaketUjian($data, $guruId);

        Cache::forget(CacheKey::STAT_TOTAL_PAKET);
        Cache::forget(CacheKey::guruStatPaket($guruId));
        Cache::forget(CacheKey::guruDraftPaket($guruId));


        return redirect()->route('guru.paket-ujian.index')->with('success', 'Paket ujian berhasil dibuat.');
    }

    public function update(PaketUjianRequest $request, PaketUjian $paketUjian)
    {
        $guruId = Auth::id();
        if ($paketUjian->guru_id !== $guruId) {
            abort(403, 'Akses ditolak.');
        }

        $data = $request->validated();
        $data['acak_soal']    = $request->boolean('acak_soal');
        $data['acak_jawaban'] = $request->boolean('acak_jawaban');

        $this->paketUjianService->updatePaketUjian($paketUjian, $data);

        Cache::forget(CacheKey::STAT_TOTAL_PAKET);
        Cache::forget(CacheKey::DASHBOARD_UJIAN_TERBARU);
        Cache::forget(CacheKey::rekapPaket($paketUjian->id));
        Cache::forget("rekap_belum_ikut_{$paketUjian->id}");
        Cache::forget(CacheKey::guruStatPaket($guruId));
        Cache::forget(CacheKey::guruDraftPaket($guruId));
        $tanggalAwal = $paketUjian->tanggal_mulai->toDateString();
        Cache::forget(CacheKey::ujianTersediaKelas($paketUjian->kelas_id, $tanggalAwal));

        return redirect()->route('guru.paket-ujian.index')->with('success', 'Konfigurasi paket ujian berhasil diperbarui.');
    }

    public function destroy(PaketUjian $paketUjian)
    {
        $guruId = Auth::id();
        if ($paketUjian->guru_id !== $guruId) {
            abort(403, 'Akses ditolak.');
        }

        $paketUjian->delete($paketUjian->id);

        Cache::forget(CacheKey::guruStatPaket($guruId));
        Cache::forget(CacheKey::guruDraftPaket($guruId));
        Cache::forget(CacheKey::guruHasilTerbaru($guruId));
        Cache::forget(CacheKey::STAT_TOTAL_PAKET);
        Cache::forget(CacheKey::DASHBOARD_UJIAN_TERBARU);
        Cache::forget(CacheKey::rekapPaket($paketUjian->id));
        Cache::forget("rekap_belum_ikut_{$paketUjian->id}");
        $tanggalAwal = $paketUjian->tanggal_mulai->toDateString();
        Cache::forget(CacheKey::ujianTersediaKelas($paketUjian->kelas_id, $tanggalAwal));

        return redirect()->route('guru.paket-ujian.index')->with('success', 'Paket ujian berhasil dihapus.');
    }

    public function show(PaketUjian $paketUjian)
    {
        $guruId = Auth::id();
        if ($paketUjian->guru_id !== $guruId) {
            abort(403, 'Akses ditolak.');
        }
        $paketUjian->load(['soal' => function ($query) {
            $query->with(['mataPelajaran', 'pilihanJawaban'])->orderBy('paket_ujian_soal.nomor_urut', 'asc');
        }]);

        $soalDiPaketId = $paketUjian->soal->pluck('id')->toArray();

        $bankSoal = Soal::where('guru_id', $guruId)
            ->where('mata_pelajaran_id', $paketUjian->mata_pelajaran_id)
            ->whereNotIn('id', $soalDiPaketId)
            ->with('pilihanJawaban')
            ->get();

        return view('guru.paket-ujian.show', compact('paketUjian', 'bankSoal'));
    }

    public function tambahSoal(Request $request, PaketUjian $paketUjian)
    {
        $guruId = Auth::id();
        if ($paketUjian->guru_id !== $guruId) {
            abort(403, 'Akses ditolak.');
        }

        $data = $request->validate([
            'soal_id'   => ['required', 'array', 'min:1'],
            'soal_id.*' => ['required', Rule::exists('soal', 'id')->where('guru_id', $guruId)],
        ]);

        $this->paketUjianService->addSoalToPaket($paketUjian, $data['soal_id']);
        Cache::forget(CacheKey::paketSoal($paketUjian->id));

        return redirect()->back()->with('success', 'Berhasil menambahkan soal terpilih ke dalam paket ujian.');
    }

    public function hapusSoal(PaketUjian $paketUjian, Soal $soal)
    {
        $guruId = Auth::id();
        if ($paketUjian->guru_id !== $guruId) {
            abort(403, 'Akses ditolak.');
        }

        $this->paketUjianService->deleteSoalFromPaket($paketUjian, $soal);
        Cache::forget(CacheKey::paketSoal($paketUjian->id));

        return redirect()->back()->with('success', 'Soal berhasil dihapus dari paket ujian ini.');
    }

    public function updateStatus(Request $request, PaketUjian $paketUjian)
    {
        $guruId = Auth::id();
        if ($paketUjian->guru_id !== $guruId) {
            abort(403, 'Akses ditolak.');
        }

        $data = $request->validate([
            'status' => ['required', 'in:draft,aktif,selesai']
        ]);

        $this->paketUjianService->updateStatusPaket($paketUjian, $data['status']);

        Cache::forget(CacheKey::guruDraftPaket($guruId));
        Cache::forget(CacheKey::guruHasilTerbaru($guruId));
        Cache::forget(CacheKey::DASHBOARD_UJIAN_TERBARU);
        $tanggalAwal = $paketUjian->tanggal_mulai->toDateString();
        Cache::forget(CacheKey::ujianTersediaKelas($paketUjian->kelas_id, $tanggalAwal));

        return redirect()->back()->with('success', 'Status akses paket ujian berhasil diubah menjadi ' . $data['status'] . '.');
    }
}
