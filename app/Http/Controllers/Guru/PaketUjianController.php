<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaketUjian;
use App\Models\MataPelajaran;
use App\Models\Soal;
use App\Models\Kelas;
use App\Http\Requests\PaketUjianRequest;
use App\Services\PaketUjianService;
use Illuminate\Support\Facades\DB;

class PaketUjianController extends Controller
{
    public function __construct(
        protected PaketUjianService $paketUjianService
    ){}


    public function index()
    {
        $paketUjian = PaketUjian::where('guru_id', auth()->id())
            ->with(['mataPelajaran', 'kelas']) 
            ->withCount('soal')
            ->get();
        
        $mataPelajaranGuru = auth()->user()->load('profileGuru.mataPelajarans')->profileGuru->mataPelajarans;

        $daftarKelas = Kelas::all();
        
        return view('guru.paket-ujian.index', compact('paketUjian', 'mataPelajaranGuru', 'daftarKelas'));
    }

    public function store(PaketUjianRequest $request)
    {
        $data = $request->validated();

        $data['acak_soal']    = $request->boolean('acak_soal');
        $data['acak_jawaban'] = $request->boolean('acak_jawaban');

        $this->paketUjianService->createPaketUjian($data, auth()->id());

        return redirect()->route('guru.paket-ujian.index')->with('success', 'Paket ujian berhasil dibuat.');
    }

    public function update(PaketUjianRequest $request, PaketUjian $paketUjian)
    {
        if ($paketUjian->guru_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $data = $request->validated();
        $data['acak_soal']    = $request->boolean('acak_soal');
        $data['acak_jawaban'] = $request->boolean('acak_jawaban');

        $this->paketUjianService->updatePaketUjian($paketUjian, $data);

        return redirect()->route('guru.paket-ujian.index')->with('success', 'Konfigurasi paket ujian berhasil diperbarui.');
    }

    public function destroy(PaketUjian $paketUjian)
    {
        if ($paketUjian->guru_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $paketUjian->delete();
        
        return redirect()->route('guru.paket-ujian.index')->with('success', 'Paket ujian berhasil dihapus.');
    }

    public function show(PaketUjian $paketUjian)
    {
        if ($paketUjian->guru_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }
        $paketUjian->load(['soal' => function($query) {
            $query->with(['mataPelajaran', 'pilihanJawaban'])->orderBy('paket_ujian_soal.nomor_urut', 'asc');
        }]);
        
        $soalDiPaketId = $paketUjian->soal->pluck('id')->toArray();

        $bankSoal = Soal::where('guru_id', auth()->id())
            ->where('mata_pelajaran_id', $paketUjian->mata_pelajaran_id)
            ->whereNotIn('id', $soalDiPaketId)
            ->with('pilihanJawaban')
            ->get();

        return view('guru.paket-ujian.show', compact('paketUjian', 'bankSoal'));
    }

    public function tambahSoal(Request $request, PaketUjian $paketUjian)
    {
        if ($paketUjian->guru_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $data = $request->validate([
            'soal_id'   => ['required', 'array', 'min:1'],
            'soal_id.*' => [
                'required',
                \Illuminate\Validation\Rule::exists('soal', 'id')
                    ->where(fn($q) => $q->where('guru_id', auth()->id())),
            ],
        ]);

        $this->paketUjianService->addSoalToPaket($paketUjian, $data['soal_id']);

        return redirect()->back()->with('success', 'Berhasil menambahkan soal terpilih ke dalam paket ujian.');
    }

    public function hapusSoal(PaketUjian $paketUjian, Soal $soal)
    {
        if ($paketUjian->guru_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $this->paketUjianService->deleteSoalFromPaket($paketUjian, $soal);

        return redirect()->back()->with('success', 'Soal berhasil didepak dari paket ujian ini.');
    }

    public function updateStatus(Request $request, PaketUjian $paketUjian)
    {
        if ($paketUjian->guru_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $data = $request->validate([
            'status' => ['required', 'in:draft,aktif,selesai']
        ]);

        $this->paketUjianService->updateStatusPaket($paketUjian,$data['status']);

        return redirect()->back()->with('success', 'Status akses paket ujian berhasil diubah menjadi ' . $data['status'] . '.');
    }
}
