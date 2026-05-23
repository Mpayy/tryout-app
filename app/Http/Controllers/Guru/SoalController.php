<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MataPelajaran;
use App\Models\Soal;

class SoalController extends Controller
{

    public function index()
    {
        // 1. Ambil data Mata Pelajaran (untuk ditampilkan namanya di header halaman)
        $mapels = MataPelajaran::all();

        return view('guru.soal.index', compact('mapels'));
    }

    public function bulkStore(Request $request, Soal $soal)
    {
        $mapel = MataPelajaran::findOrFail($request->mapel_id);

        // 1. Validasi struktur array kiriman form bulk
        $request->validate([
            'soal' => 'required|array',
            'soal.*.pertanyaan' => 'required|string',
            'soal.*.opsi' => 'required|array|min:4',
            'soal.*.opsi.*' => 'required|string',
            'soal.*.jawaban_benar' => 'required|string|in:A,B,C,D',
        ]);

        // 2. Jalankan Transaction Database demi keamanan data berelasi
        DB::transaction(function () use ($request, $mapel) {

            foreach ($request->soal as $item) {
                // A. Simpan data Soal terlebih dahulu
                $soal = Soal::create([
                    'mata_pelajaran_id' => $mapel->id,
                    'pertanyaan' => $item['pertanyaan'],
                    'kunci_jawaban' => $item['jawaban_benar'], // Menyimpan string pilihan yang benar (misal: 'A')
                ]);

                // B. Simpan pilihan opsinya ke tabel anak (hasMany)
                foreach ($item['opsi'] as $key => $textOpsi) {
                    // Sesuai penamaan model relasi hasMany pilihan jawaban kamu
                    $soal->soal()->create([
                        'label_opsi' => $key,       // Menyimpan huruf 'A', 'B', 'C', atau 'D'
                        'teks_opsi' => $textOpsi,   // Menyimpan isi jawaban teksnya
                    ]);
                }
            }
        });

        return redirect()->route('guru.soal.index')
            ->with('success', 'Hebat! Semua butir bank soal berhasil ditambahkan sekaligus!');
    }
}
