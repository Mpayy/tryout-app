<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaketUjian;
use App\Models\MataPelajaran;
use App\Models\Soal;
use App\Http\Requests\PaketUjianRequest;

class PaketUjianController extends Controller
{
    public function index()
    {
        $pakets = PaketUjian::where('guru_id', auth()->id())
            ->with(['mataPelajaran'])
            ->withCount('soal')
            ->latest()
            ->paginate(15);

        return view('guru.paket-ujian.index', compact('pakets'));
    }

    public function create()
    {
        $mapels = MataPelajaran::orderBy('nama')->get();
        return view('guru.paket-ujian.create', compact('mapels'));
    }

    public function store(PaketUjianRequest $request)
    {
        $data = $request->validated();
        $data['guru_id'] = auth()->id();
        $data['status'] = $data['status'] ?? 'draft';
        $data['acak_soal'] = $request->has('acak_soal') ? 1 : 0;
        $data['acak_jawaban'] = $request->has('acak_jawaban') ? 1 : 0;

        $paket = PaketUjian::create($data);

        return redirect()->route('guru.paket-ujian.show', $paket->id)
            ->with('success', 'Paket ujian berhasil dibuat. Silakan tambahkan soal.');
    }

    public function show(PaketUjian $paketUjian)
    {
        if ($paketUjian->guru_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        // Ambil soal yang sudah ada di paket
        $paketUjian->load('soal.mataPelajaran', 'soal.pilihanJawaban');
        $soalDiPaketId = $paketUjian->soal->pluck('id')->toArray();

        // Ambil soal dari bank soal yang mapelnya sama dan BELUM ada di paket
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
            abort(403);
        }

        $request->validate([
            'soal_id' => 'required|array',
            'soal_id.*' => 'exists:soal,id'
        ]);

        // Cek urutan terakhir
        $lastOrder = \DB::table('paket_ujian_soal')
            ->where('paket_ujian_id', $paketUjian->id)
            ->max('nomor_urut') ?? 0;

        foreach ($request->soal_id as $soalId) {
            $lastOrder++;
            $paketUjian->soal()->attach($soalId, ['nomor_urut' => $lastOrder]);
        }

        return redirect()->back()->with('success', 'Berhasil menambahkan soal ke paket ujian.');
    }

    public function hapusSoal(PaketUjian $paketUjian, Soal $soal)
    {
        if ($paketUjian->guru_id !== auth()->id()) {
            abort(403);
        }

        $paketUjian->soal()->detach($soal->id);

        return redirect()->back()->with('success', 'Soal berhasil dihapus dari paket ujian.');
    }

    public function updateStatus(Request $request, PaketUjian $paketUjian)
    {
        if ($paketUjian->guru_id !== auth()->id()) {
            abort(403);
        }

        $request->validate(['status' => 'required|in:draft,aktif,selesai']);
        
        $paketUjian->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status paket ujian berhasil diperbarui.');
    }
}
