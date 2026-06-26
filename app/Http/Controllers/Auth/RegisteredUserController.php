<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ProfileSiswa;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * FIX #3a: Assign role 'siswa' setelah user dibuat.
     * FIX #3b: Redirect ke route siswa.dashboard, bukan route('dashboard') yang tidak ada.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'nis'      => ['required', 'string', 'max:255', 'unique:' . ProfileSiswa::class . ',nis'],
            'foto'     => ['nullable', 'image', 'max:2048'],
        ]);

        $user = User::create([
            'name'                => $request->name,
            'email'               => $request->email,
            'password'            => Hash::make($request->password),
        ]);

        $user->assignRole('siswa');

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('foto-profil', 'public');
        }

        ProfileSiswa::create([
            'user_id' => $user->id,
            'nis' => $request->nis,
            'foto' => $path,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('siswa.dashboard')->with('success', 'Pendaftaran berhasil!');
    }
}
