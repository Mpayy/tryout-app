<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // $request->user()->fill($request->validated());

        // if ($request->user()->isDirty('email')) {
        //     $request->user()->email_verified_at = null;
        // }

        // $request->user()->save();

        // return Redirect::route('profile.edit')->with('status', 'profile-updated');
        $user = $request->user();

        // 1. Validasi Input Utama & Dinamis Berdasarkan Role
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'], // Maksimal 2MB
        ];

        if ($user->hasRole('guru')) {
            $rules['nip'] = ['nullable', 'string', 'max:50'];
        } else {
            $rules['nis'] = ['nullable', 'string', 'max:50'];
        }

        $validated = $request->validate($rules);

        // 2. Update Data Utama User (Nama & Email)
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // 3. Proses Logika File Foto Profil (Jika ada upload baru)
        if ($request->hasFile('foto')) {
            // Ambil path foto lama berdasarkan role untuk dihapus dari storage
            $profileModel = $user->hasRole('guru') ? $user->profileGuru : $user->profileSiswa;
            $oldFoto = $profileModel?->foto;

            // Simpan foto baru ke folder public/foto-profil
            $newFotoPath = $request->file('foto')->store('foto-profil', 'public');

            // Hapus file foto lama jika ada di storage agar tidak memenuhi server
            if ($oldFoto && Storage::disk('public')->exists($oldFoto)) {
                Storage::disk('public')->delete($oldFoto);
            }

            // Masukkan path foto baru ke array data yang akan disimpan ke tabel pivot/relasi
            $validated['foto'] = $newFotoPath;
        }

        // 4. Update ke Tabel Relasi Spesifik (profile_gurus atau profile_siswas)
        if ($user->hasRole('guru')) {
            // Gunakan updateOrCreate untuk menghindari error jika data relasi kosong saat pertama kali
            $user->profileGuru()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nip' => $validated['nip'] ?? null,
                    'foto' => $validated['foto'] ?? ($user->profileGuru?->foto)
                ]
            );
        } else {
            $user->profileSiswa()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nis' => $validated['nis'] ?? null,
                    'foto' => $validated['foto'] ?? ($user->profileSiswa?->foto)
                ]
            );
        }

        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
