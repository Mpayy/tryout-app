<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MataPelajaran;
use App\Models\Soal;
use App\Models\PilihanJawaban;
use App\Http\Requests\SoalRequest;
use App\Services\SoalService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Support\CacheKey;

class SoalController extends Controller
{
    public function __construct(
        protected SoalService $soalService
    ) {}

    public function index()
    {
        $guru = Auth::user();
        $soals = Soal::where('guru_id', $guru->id)->with('mataPelajaran')->paginate(5);
        return view('guru.soal.index', compact('soals'));
    }

    public function create()
    {
        $guru = Auth::user();
        $mataPelajaranGuru = Cache::remember(CacheKey::mataPelajaranGuru($guru->id), now()->addMinutes(CacheKey::TTL_LONG), fn() => $guru->load('profileGuru.mataPelajarans')->profileGuru->mataPelajarans);

        return view('guru.soal.create', compact('mataPelajaranGuru'));
    }

    public function store(SoalRequest $request)
    {
        $guru = Auth::user();
        $validated = $request->validated();

        $allowedMapelIds = $guru->profileGuru->mataPelajarans->pluck('id')->toArray();

        if (!in_array($validated['mapel_id'], $allowedMapelIds)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Anda tidak memiliki akses untuk membuat soal di mata pelajaran ini.');
        }

        $result = $this->soalService->createSoal(
            $validated['mapel_id'],
            $validated['soal'],
            $guru->id
        );

        Cache::forget(CacheKey::STAT_TOTAL_SOAL);
        Cache::forget(CacheKey::guruStatSoal($guru->id));


        return redirect()
            ->route('guru.soal.index')
            ->with('success', count($result) . ' butir soal berhasil disimpan ke bank soal.');
    }

    public function update(Request $request, Soal $soal)
    {
        $guruId = Auth::id();
        if ($soal->guru_id !== $guruId) {
            abort(403, 'Akses ditolak.');
        }

        $validate = $request->validate([
            'pertanyaan'    => 'required|string',
            'opsi_a'        => 'required|string',
            'opsi_b'        => 'required|string',
            'opsi_c'        => 'required|string',
            'opsi_d'        => 'required|string',
            'jawaban_benar' => 'required|string|in:A,B,C,D',
        ]);

        $this->soalService->updateSoal($soal, $validate);

        Cache::forget(CacheKey::soalLengkap($soal->id));


        return redirect()->back()->with('success', 'Soal berhasil diperbarui!');
    }

    public function destroy(Soal $soal)
    {
        $guruId = Auth::id();
        if ($soal->guru_id !== $guruId) {
            abort(403, 'Anda tidak berhak menghapus soal ini.');
        }

        $soal->delete($soal->id);

        Cache::forget(CacheKey::STAT_TOTAL_SOAL);
        Cache::forget(CacheKey::guruStatSoal($guruId));

        return redirect()->route('guru.soal.index')
            ->with('success', 'Soal berhasil dihapus.');
    }
}
