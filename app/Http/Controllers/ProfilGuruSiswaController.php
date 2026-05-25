<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilGuruSiswaController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if($user->hasRole('guru')){
            $user->load('profileGuru');
            return view('profile.guru.index', compact('user'));
        }else if($user->hasRole('siswa')){
            $user->load('profileSiswa');
            return view('profile.siswa.index', compact('user'));
        }else{
            return view('profile.edit', compact('user'));
        }
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if($user->hasRole('guru')){
            $request->validate([
                'nip' => 'nullable|string|max:255|unique:profiles_guru,nip,' . $user->profileGuru?->id,
                'bidang_studi' => 'nullable|string|max:255',
            ]);

            $data = $request->only(['nip', 'bidang_studi']);

            if($request->hasFile('foto')){
                if($user->profileGuru?->foto){
                    Storage::delete($user->profileGuru->foto);
                }
                $data['foto'] = $request->file('foto')->store('foto-guru', 'public');
            }


            $user->profileGuru()->updateOrCreate(['user_id' => $user->id], $data);
        }else if($user->hasRole('siswa')){
            $request->validate([
                'nis' => 'required|string|max:255|unique:profiles_siswa,nis,' . $user->profileSiswa?->id,
                'kelas' => 'required|string|max:255',
                'jurusan' => 'nullable|string|max:255',
            ]);

            $data = $request->only(['nis', 'kelas', 'jurusan']);

            if($request->hasFile('foto')){
                if($user->profileSiswa?->foto){
                    Storage::delete($user->profileSiswa->foto);
                }
                $data['foto'] = $request->file('foto')->store('foto-siswa', 'public');
            }


            $user->profileSiswa()->updateOrCreate(['user_id' => $user->id], $data);
        }
        return redirect()->back()->with('success', 'Profil anda berhasil diperbarui!');
    }
}
