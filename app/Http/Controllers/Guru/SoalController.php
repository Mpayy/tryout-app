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
        $soals = Soal::where('guru_id', auth()->id())->with('mataPelajaran')->paginate(5);
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'pertanyaan'    => 'required|string',
            'opsi_a'        => 'required|string',
            'opsi_b'        => 'required|string',
            'opsi_c'        => 'required|string',
            'opsi_d'        => 'required|string',
            'jawaban_benar' => 'required|string|in:A,B,C,D',
        ]);

        // 1. Cari data soal beserta relasi jawabannya
        $soal = Soal::with('pilihanJawaban')->findOrFail($id);

        // 2. Update tabel 'soals' terlebih dahulu
        $soal->update([
            'konten' => $request->pertanyaan,
        ]);

        // 3. Mapping input form menjadi struktur tabel jawaban terpisah
        $opsiData = [
            'A' => $request->opsi_a,
            'B' => $request->opsi_b,
            'C' => $request->opsi_c,
            'D' => $request->opsi_d,
        ];

        // 4. Update data di tabel 'jawabans' satu per satu
        foreach ($soal->pilihanJawaban as $jawaban) {
            // Ambil konten baru berdasarkan label jawaban saat ini (A/B/C/D)
            $kontenBaru = $opsiData[$jawaban->label];
            
            // Cek apakah label ini diset sebagai jawaban benar di form
            $apakahBenar = ($jawaban->label === $request->jawaban_benar) ? 1 : 0;

            // Eksekusi update baris tersebut
            $jawaban->update([
                'konten' => $kontenBaru,
                'is_correct' => $apakahBenar
            ]);
        }

        return redirect()->back()->with('success', 'Soal berhasil diperbarui!');
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
