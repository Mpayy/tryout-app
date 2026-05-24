<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
        ]);

        $user = User::create([
            'name'                => $request->name,
            'email'               => $request->email,
            'password'            => Hash::make($request->password),
            'is_active'           => true,
            'is_profile_complete' => false,
            'is_approved'         => true,
        ]);

        // FIX #3a: Assign role siswa via Spatie Permission
        // Tanpa ini, siswa tidak bisa akses route manapun yang diproteksi middleware role:siswa
        $user->assignRole('siswa');

        event(new Registered($user));

        Auth::login($user);

        // FIX #3b: Redirect ke dashboard siswa, bukan route('dashboard') yang sudah dihapus
        return redirect()->route('siswa.dashboard')
            ->with('info', 'Selamat datang! Silakan lengkapi profil Anda.');
    }
}
