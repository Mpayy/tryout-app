<x-guest-layout>
    <div class="hidden lg:flex flex-col justify-between bg-primary p-10 text-primary-content">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-primary-content/20 rounded-xl flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" />
                </svg>
            </div>
            <span class="font-bold text-lg tracking-tight">Tryout App</span>
        </div>

        <div class="space-y-4">
            <h1 class="text-3xl font-bold leading-snug">
                Platform Ujian<br>Online Terpadu
            </h1>
            <p class="text-primary-content/70 text-sm leading-relaxed">
                Kelola soal, jadwal ujian, dan pantau hasil belajar siswa — semua dalam satu tempat.
            </p>
            <ul class="space-y-2.5 mt-6">
                @foreach([
                        ['icon' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 'text' => 'Bank soal terorganisir per mata pelajaran'],
                        ['icon' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 'text' => 'Jadwal ujian fleksibel dengan status real-time'],
                        ['icon' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 'text' => 'Acak soal & jawaban otomatis per siswa'],
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
        <p class="text-xs text-primary-content/40">© {{ date('Y') }} Tryout App. All rights reserved.</p>
    </div>
    <div class="flex flex-col justify-center p-8 lg:p-10">
        <div class="flex items-center gap-2 mb-8 lg:hidden">
            <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="size-4 text-primary-content">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" />
                </svg>
            </div>
            <span class="font-bold text-base-content">Tryout App</span>
        </div>

        <div class="mb-7">
            <h2 class="text-2xl font-bold text-base-content">Selamat Datang</h2>
            <p class="text-sm text-base-content/50 mt-1">Masuk ke akun kamu untuk melanjutkan.</p>
        </div>

        @if (session('status'))
            <div role="alert" class="alert alert-success alert-soft mb-5 py-2.5 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div class="form-control">
                <label class="floating-label">
                    <span class="label-text font-semibold text-base-content/70 text-sm">Email</span>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        placeholder="nama@email.com"
                        class="input input-primary w-full"
                        required autofocus autocomplete="username" />
                </label>
                <x-input-error :messages="$errors->get('email')" />
            </div>
            <div class="form-control">
                <label class="floating-label">
                    <span class="label-text font-semibold text-base-content/70 text-sm">Kata Sandi</span>
                    <input id="password" type="password" name="password"
                        placeholder="••••••••"
                        class="input input-primary w-full"
                        required autocomplete="current-password" />
                </label>
                <x-input-error :messages="$errors->get('password')" />
            </div>
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="checkbox checkbox-primary checkbox-sm rounded-md" />
                    <span class="text-sm text-base-content/60">Ingat saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-sm text-primary hover:underline font-medium">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>
            <button type="submit" class="btn btn-primary w-full font-semibold mt-2 gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                </svg>
                Masuk
            </button>
        </form>
        <p class="text-center text-sm text-base-content/50 mt-6">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-primary font-semibold hover:underline">Daftar sekarang</a>
        </p>
    </div>
</x-guest-layout>
