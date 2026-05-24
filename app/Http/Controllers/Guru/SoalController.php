<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MataPelajaran;
use App\Models\Soal;
use App\Models\PilihanJawaban;

class SoalController extends Controller
{
    public function index()
    {
        $soals = Soal::where('guru_id', auth()->id())
            ->with(['mataPelajaran', 'pilihanJawaban'])
            ->latest()
            ->paginate(20);

        return view('guru.soal.index', compact('soals'));
    }
    /**
     * Tampilkan halaman input soal bulk.
     * Kirim daftar mata pelajaran untuk dropdown.
     */
    public function create()
    {
        $mapels = MataPelajaran::orderBy('nama')->get();

        return view('guru.soal.create', compact('mapels'));
    }

    /**
     * Simpan banyak soal sekaligus (bulk insert).
     *
     * FIX #1f: Hilangkan `Soal $soal` dari signature — tidak ada route binding yang dibutuhkan.
     */
    public function store(Request $request) // FIX #1f: hapus Soal $soal
    {
        // Pastikan mata pelajaran yang dipilih ada di database
        $mapel = MataPelajaran::findOrFail($request->mapel_id);

        // Validasi struktur array dari form
        $request->validate([
            'mapel_id'             => 'required|exists:mata_pelajaran,id',
            'soal'                 => 'required|array|min:1',
            'soal.*.pertanyaan'    => 'required|string',
            'soal.*.opsi'          => 'required|array|min:4',
            'soal.*.opsi.*'        => 'required|string',
            'soal.*.jawaban_benar' => 'required|string|in:A,B,C,D',
            'tingkat_kesulitan'    => 'nullable|in:mudah,sedang,sulit',
        ]);

        DB::transaction(function () use ($request, $mapel) {

            foreach ($request->soal as $item) {

                // FIX #1b: Field yang benar adalah 'konten', bukan 'pertanyaan'
                // FIX #1c: Hapus 'kunci_jawaban', tidak ada di tabel soal
                // FIX #1e: Tambah guru_id dari user yang sedang login
                $soal = Soal::create([
                    'mata_pelajaran_id' => $mapel->id,
                    'guru_id'           => auth()->id(),              // FIX #1e
                    'konten'            => $item['pertanyaan'],       // FIX #1b
                    'tingkat_kesulitan' => $request->tingkat_kesulitan ?? 'sedang',
                ]);

                // FIX #1a: Relasi yang benar adalah pilihanJawaban(), bukan soal()
                // FIX #1d: Nama field yang benar adalah 'label' dan 'konten'
                foreach ($item['opsi'] as $labelHuruf => $teksOpsi) {
                    $soal->pilihanJawaban()->create([          // FIX #1a
                        'label'      => $labelHuruf,          // FIX #1d (was: label_opsi)
                        'konten'     => $teksOpsi,            // FIX #1d (was: teks_opsi)
                        'is_correct' => ($item['jawaban_benar'] === $labelHuruf),
                    ]);
                }
            }
        });

        return redirect()->route('guru.soal.index')
            ->with('success', 'Berhasil! ' . count($request->soal) . ' soal berhasil disimpan.');
    }

    /**
     * Tampilkan daftar soal milik guru ini.
     */
    public function list()
    {
        $soal = Soal::where('guru_id', auth()->id())
            ->with(['mataPelajaran', 'pilihanJawaban'])
            ->latest()
            ->paginate(20);

        return view('guru.soal.list', compact('soal'));
    }

    /**
     * Hapus soal beserta semua pilihan jawabannya.
     * Cascade delete sudah diset di migration.
     */
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
