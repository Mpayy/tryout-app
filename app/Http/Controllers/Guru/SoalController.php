<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MataPelajaran;
use App\Models\Soal;
use App\Models\PilihanJawaban;
use FontLib\Table\Type\name;
use App\Http\Requests\SoalRequest;
use App\Services\SoalService;

class SoalController extends Controller
{
    public function __construct(
        protected SoalService $soalService
    ) {}

    public function index()
    {
        $soals = Soal::where('guru_id', auth()->id())->with('mataPelajaran')->get();
        $guru = auth()->user()->load('profileGuru.mataPelajarans');

        return view('guru.soal.index', compact('soals', 'guru'));
    }
    /**
     * Tampilkan halaman input soal bulk.
     * Kirim daftar mata pelajaran untuk dropdown.
     */
    public function create()
    {
        $mataPelajaranGuru = auth()->user()->load('profileGuru.mataPelajarans')->profileGuru->mataPelajarans;
        return view('guru.soal.create', compact('mataPelajaranGuru'));
    }

    public function store(SoalRequest $request)
    {
        $validated = $request->validated();

        $allowedMapelIds = auth()->user()->profileGuru->mataPelajarans->pluck('id')->toArray();

        if (!in_array($validated['mapel_id'], $allowedMapelIds)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Anda tidak memiliki akses untuk membuat soal di mata pelajaran ini.');
        }

        $result = $this->soalService->createSoal(
            $validated['mapel_id'],
            $validated['soal']
        );

        return redirect()
            ->route('guru.soal.index')
            ->with('success', count($result) . ' butir soal berhasil disimpan ke bank soal.');
    }

    public function destroy(Soal $soal)
    {
        // Pastikan hanya guru pemilik yang bisa hapus
        if ($soal->guru_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak menghapus soal ini.');
        }

        $soal->delete();

        return redirect()->route('guru.soal.index')
            ->with('success', 'Soal berhasil dihapus.');
    }
}
