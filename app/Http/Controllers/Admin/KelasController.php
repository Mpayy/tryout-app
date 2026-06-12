<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\KelasRequest;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\ProfileSiswa;
use App\Models\User;

class KelasController extends Controller
{
    public function index()
    {
        $daftarKelas = Kelas::withCount('profileSiswas as total_siswa')->get();
        
        return view('admin.kelas.index', compact('daftarKelas'));
    }

    public function store(KelasRequest $request)
    {
        $validated = $request->validated();

        Kelas::create($validated);

        return redirect()->route('admin.kelas.index');
    }

    public function update(KelasRequest $request, Kelas $kelas)
    {
        $validated = $request->validated();

        $kelas->update($validated);

        return redirect()->route('admin.kelas.index');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();

        return redirect()->route('admin.kelas.index');
    }

    public function anggota(Kelas $kelas)
    {
        $siswaTanpaKelas = User::role('siswa')->whereHas('profileSiswa', function($query){
            $query->whereNull('kelas_id');
        })
        ->with('profileSiswa')
        ->get();

        $siswaDiKelas = User::role('siswa')->whereHas('profileSiswa', function($query) use ($kelas) {
            $query->where('kelas_id', $kelas->id);
        })->with('profileSiswa')
        ->get();

        return view('admin.kelas.anggota', compact('kelas', 'siswaDiKelas', 'siswaTanpaKelas'));
    }

    public function tambahSiswa(Request $request, Kelas $kelas)
    {
        $request->validate([
            'siswa_id' => ['required', 'array', 'min: 1'],
            'siswa_id.*' => ['exists:profiles_siswa,id']
        ], [
            'siswa_id.required' => 'Minimal 1 siswa harus dipilih.',
        ]);

        ProfileSiswa::whereIn('id', $request->siswa_id)
        ->update([
            'kelas_id' => $kelas->id
        ]);

        return redirect()->back()->with('success', 'Siswa berhasil ditambahkan ke kelas.');

    }

    public function hapusSiswa(Kelas $kelas, ProfileSiswa $profileSiswa)
    {
        $profileSiswa->update([
            'kelas_id' => null
        ]);

        return redirect()->back()->with('success', 'Siswa berhasil dihapus dari kelas.');
    }
}
