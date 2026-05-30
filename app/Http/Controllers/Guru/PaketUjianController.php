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
        // 1. Ambil paket ujian milik guru ini, plus eager load kelas & mapel
        $paketUjian = PaketUjian::where('guru_id', auth()->id())
            ->with(['mataPelajaran', 'kelas']) // Tambahkan 'kelas' di sini
            ->withCount('soal')
            ->get();
        
        // 2. Ambil mata pelajaran yang diajar oleh guru yang sedang login
        $mataPelajaranGuru = auth()->user()->load('profileGuru.mataPelajarans')->profileGuru->mataPelajarans;

        // 3. TAMBAHAN: Ambil semua data kelas untuk isi checkbox di modal
        $daftarKelas = Kelas::all();
        
        return view('guru.paket-ujian.index', compact('paketUjian', 'mataPelajaranGuru', 'daftarKelas'));
    }

    public function store(PaketUjianRequest $request)
    {
        $data = $request->validated();
        
        // Bungkus dengan transaksi database agar jika salah satu gagal, data tidak pincang
        // DB::transaction(function () use ($data) {
        //     // Simpan data paket ujian utama
        //     $paket = PaketUjian::create([
        //         'nama' => $data['nama'],
        //         'mata_pelajaran_id' => $data['mata_pelajaran_id'],
        //         'durasi' => $data['durasi'],
        //         'tanggal_mulai' => $data['tanggal_mulai'],
        //         'tanggal_selesai' => $data['tanggal_selesai'],
        //         'guru_id' => auth()->id(),
        //     ]);

        //     // Ikat data kelas ke tabel pivot kelas_paket_ujian
        //     $paket->kelas()->attach($data['kelas_ids']);
        // });

        $this->paketUjianService->createPaketUjian($data, auth()->id());

        return redirect()->route('guru.paket-ujian.index')->with('success', 'Paket ujian berhasil dibuat.');
    }

    public function update(PaketUjianRequest $request, PaketUjian $paketUjian)
    {
        // Security check ditaruh di paling atas
        if ($paketUjian->guru_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $data = $request->validated();

        // DB::transaction(function () use ($paket, $data) {
        //     // Update data paket ujian utama
        //     $paket->update([
        //         'nama' => $data['nama'],
        //         'mata_pelajaran_id' => $data['mata_pelajaran_id'],
        //         'durasi' => $data['durasi'],
        //         'tanggal_mulai' => $data['tanggal_mulai'],
        //         'tanggal_selesai' => $data['tanggal_selesai'],
        //     ]);

        //     // Gunakan sync() agar otomatis menghapus kelas lama dan memasukkan kelas baru yang dicentang
        //     $paket->kelas()->sync($data['kelas_ids']);
        // });

        $this->paketUjianService->updatePaketUjian($paketUjian, $data);

        return redirect()->route('guru.paket-ujian.index')->with('success', 'Konfigurasi paket ujian berhasil diperbarui.');
    }

    public function destroy(PaketUjian $paketUjian)
    {
        if ($paketUjian->guru_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        // Karena di migration kita menggunakan ->onDelete('cascade'), 
        // data di tabel pivot kelas_paket_ujian akan otomatis terhapus saat paket ini dihapus.
        $paketUjian->delete();
        
        return redirect()->route('guru.paket-ujian.index')->with('success', 'Paket ujian berhasil dihapus.');
    }

    public function show(PaketUjian $paketUjian)
    {
        // Security Check: Pastikan ini paket miliknya
        if ($paketUjian->guru_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        // 1. Ambil soal yang sudah nempel di paket ujian ini (Diurutkan berdasarkan nomor_urut pivot)
        $paketUjian->load(['soal' => function($query) {
            $query->with(['mataPelajaran', 'pilihanJawaban'])->orderBy('paket_ujian_soal.nomor_urut', 'asc');
        }]);
        
        $soalDiPaketId = $paketUjian->soal->pluck('id')->toArray();

        // 2. Ambil bank soal yang mapelnya SAMA, milik guru ini, dan BELUM dimasukkan ke paket
        $bankSoal = Soal::where('guru_id', auth()->id())
            ->where('mata_pelajaran_id', $paketUjian->mata_pelajaran_id)
            ->whereNotIn('id', $soalDiPaketId)
            ->with('pilihanJawaban')
            ->get();

        // Mengarah ke halaman detail manajemen soal di paket ujian
        return view('guru.paket-ujian.show', compact('paketUjian', 'bankSoal'));
    }

    public function tambahSoal(Request $request, PaketUjian $paketUjian)
    {
        if ($paketUjian->guru_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        // Sesuaikan 'soal,id' dengan nama tabel bank soalmu (misal: 'soals' atau 'soal')
        $data = $request->validate([
            'soal_id' => ['required', 'array', 'min:1'],
            'soal_id.*' => ['exists:soal,id'] 
        ]);

        // DB::transaction(function () use ($request, $paketUjian) {
        //     // Ambil nomor urut tertinggi saat ini di paket tersebut
        //     $lastOrder = DB::table('paket_ujian_soal')
        //         ->where('paket_ujian_id', $paketUjian->id)
        //         ->max('nomor_urut') ?? 0;

        //     // Tampung data sync/attach massal agar hemat query
        //     $attachData = [];
        //     foreach ($request->soal_id as $soalId) {
        //         $lastOrder++;
        //         $attachData[$soalId] = ['nomor_urut' => $lastOrder];
        //     }

        //     // Melakukan insert massal ke tabel pivot tanpa menghapus yang lama
        //     $paketUjian->soal()->attach($attachData);
        // });

        $this->paketUjianService->addSoalToPaket($paketUjian, $data['soal_id']);

        return redirect()->back()->with('success', 'Berhasil menambahkan soal terpilih ke dalam paket ujian.');
    }

    public function hapusSoal(PaketUjian $paketUjian, Soal $soal)
    {
        if ($paketUjian->guru_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        // DB::transaction(function () use ($paketUjian, $soal) {
        //     // 1. Lepaskan hubungan soal dari tabel pivot
        //     $paketUjian->soal()->detach($soal->id);

        //     // 2. OPTIONAL & BERMANFAAT: Susun ulang nomor_urut yang tersisa agar tidak bolong-bolong
        //     $soalTersisa = DB::table('paket_ujian_soal')
        //         ->where('paket_ujian_id', $paketUjian->id)
        //         ->orderBy('nomor_urut', 'asc')
        //         ->get();

        //     foreach ($soalTersisa as $index => $row) {
        //         DB::table('paket_ujian_soal')
        //             ->where('id', $row->id)
        //             ->update(['nomor_urut' => $index + 1]);
        //     }
        // });

        $this->paketUjianService->deleteSoalFromPaket($paketUjian,$soal);

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
        
        // $paketUjian->update([
        //     'status' => $request->status
        // ]);

        $this->paketUjianService->updateStatusPaket($paketUjian,$data['status']);

        return redirect()->back()->with('success', 'Status akses paket ujian berhasil diubah menjadi ' . $data['status'] . '.');
    }
}
