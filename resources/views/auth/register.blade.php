<x-guest-layout>
    {{-- ===== PANEL KIRI — Branding (identik dengan login) ===== --}}
    <div class="hidden lg:flex flex-col justify-between bg-primary p-10 text-primary-content">
        {{-- Logo & Brand --}}
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-primary-content/20 rounded-xl flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                    class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" />
                </svg>
            </div>
            <span class="font-bold text-lg tracking-tight">Tryout App</span>
        </div>

        {{-- Tagline --}}
        <div class="space-y-4">
            <h1 class="text-3xl font-bold leading-snug">
                Bergabung &amp;<br>Mulai Belajar
            </h1>
            <p class="text-primary-content/70 text-sm leading-relaxed">
                Daftarkan akunmu untuk mengikuti tryout, memantau perkembangan nilai, dan bersaing sehat dengan teman sekelas.
            </p>
            
            {{-- Feature list --}}
            <ul class="space-y-2.5 mt-6">
                @foreach([
                    ['icon' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 'text' => 'Akses ujian sesuai kelas secara otomatis'],
                    ['icon' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 'text' => 'Riwayat nilai & peringkat tersimpan rapi'],
                    ['icon' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 'text' => 'Notifikasi jadwal ujian akan datang'],
                ] as $item)
                    <li class="flex items-center gap-3 text-sm text-primary-content/80">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="size-4 shrink-0 text-primary-content/60">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                        </svg>
                        {{ $item['text'] }}
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Footer brand panel --}}
        <p class="text-xs text-primary-content/40">© {{ date('Y') }} Tryout App. All rights reserved.</p>
    </div>

    {{-- ===== PANEL KANAN — Form Register ===== --}}
    <div class="flex flex-col justify-center p-8 lg:p-10 max-h-screen overflow-y-auto">

        {{-- Header mobile: brand (hanya tampil di mobile) --}}
        <div class="flex items-center gap-2 mb-6 lg:hidden">
            <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="size-4 text-primary-content">
                    <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" />
                    </svg>
                </div>
                <span class="font-bold text-base-content">Tryout App</span>
            </div>

            <div class="mb-6">
                <h2 class="text-2xl font-bold text-base-content">Buat Akun Baru</h2>
                <p class="text-sm text-base-content/50 mt-1">Lengkapi data diri untuk mendaftar sebagai siswa.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                {{-- ─── Foto Profil (upload + live preview) ─── --}}
                <div class="flex items-center gap-4">
                    <div class="relative shrink-0">
                        <div class="avatar">
                            <div class="w-16 h-16 rounded-full ring-2 ring-primary/20 ring-offset-2 ring-offset-base-100 overflow-hidden bg-base-200">
                                <img id="avatar_preview"
                                    src="https://ui-avatars.com/api/?name=?&background=EEF2FF&color=4F46E5"
                                    alt="Pratinjau Foto"
                                    class="object-cover w-full h-full" />
                            </div>
                        </div>
                        <label for="foto_input"
                            class="absolute bottom-0 right-0 w-6 h-6 bg-primary hover:bg-primary/80
                                   text-primary-content rounded-full shadow-md flex items-center justify-center
                                   cursor-pointer transition-colors"
                            title="Unggah foto profil">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2.5" stroke="currentColor" class="size-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                            </svg>
                        </label>
                        <input type="file" id="foto_input" name="foto" class="hidden"
                            accept="image/*" onchange="previewFoto(this)" />
                    </div>
                    <div>
                        <span class="text-sm font-semibold text-base-content/70 block">Foto Profil</span>
                        <span class="text-xs text-base-content/40">Opsional · JPG/PNG, maks. 2MB</span>
                        @error('foto')
                            <span class="text-xs text-error block mt-0.5">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- ─── Nama & NIS (2 kolom) ─── --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="floating-label">
                            <span class="label-text font-semibold text-base-content/70 text-sm">Nama Lengkap</span>
                            <input id="name" type="text" name="name" value="{{ old('name') }}"
                                placeholder="Nama lengkap"
                                class="input input-primary w-full" required autofocus autocomplete="name" />
                        </label>
                        <x-input-error :messages="$errors->get('name')" />
                    </div>

                    <div class="form-control">
                        <label class="floating-label">
                            <span class="label-text font-semibold text-base-content/70 text-sm">NIS</span>
                            <input id="nis" type="text" name="nis" value="{{ old('nis') }}"
                                placeholder="Nomor Induk Siswa"
                                class="input input-primary w-full" required autocomplete="off" />
                        </label>
                        <x-input-error :messages="$errors->get('nis')" />
                    </div>
                </div>

                {{-- ─── Email ─── --}}
                <div class="form-control">
                    <label class="floating-label">
                        <span class="label-text font-semibold text-base-content/70 text-sm">Email</span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            class="input input-primary w-full" required autocomplete="username" />
                    </label>
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                {{-- ─── Password & Konfirmasi (2 kolom) ─── --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="floating-label">
                            <span class="label-text font-semibold text-base-content/70 text-sm">Kata Sandi</span>
                            <input id="password" type="password" name="password"
                                placeholder="Minimal 8 karakter"
                                class="input input-primary w-full" required autocomplete="new-password" />
                        </label>
                        <x-input-error :messages="$errors->get('password')" />
                    </div>

                    <div class="form-control">
                        <label class="floating-label">
                            <span class="label-text font-semibold text-base-content/70 text-sm">Konfirmasi Sandi</span>
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                placeholder="Ulangi kata sandi"
                                class="input input-primary w-full"
                                required autocomplete="new-password" />
                        </label>
                        <x-input-error :messages="$errors->get('password_confirmation')" />
                    </div>
                </div>

                {{-- ─── Submit ─── --}}
                <button type="submit" class="btn btn-primary w-full font-semibold mt-2 gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                    </svg>
                    Daftar Sekarang
                </button>
            </form>

            {{-- Link ke login --}}
            <p class="text-center text-sm text-base-content/50 mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">Masuk di sini</a>
            </p>
        </div>
    </div>
</x-guest-layout>
