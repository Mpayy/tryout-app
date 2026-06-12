{{--
    PERUBAHAN DARI VERSI LAMA:
    1. Tambah shadow-sm + border-b agar navbar punya pemisah visual yang jelas dari konten
    2. Tampilkan nama & role user di kanan (sebelumnya navbar kosong di kanan)
    3. Tambah dropdown logout yang proper dengan avatar inisial
    4. Judul "Tryout App" hanya muncul di mobile (di desktop sudah ada di sidebar)
--}}
<nav class="navbar w-full bg-base-100 shadow-sm border-b border-base-200 sticky top-0 z-30 min-h-14">

    {{-- Tombol toggle sidebar (mobile) --}}
    <label for="my-drawer-4" aria-label="open sidebar" class="btn btn-square btn-ghost btn-sm mr-1 lg:hidden">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            class="inline-block h-5 w-5 stroke-current">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </label>

    {{-- Tombol toggle sidebar (desktop, collapse/expand) --}}
    <label for="my-drawer-4" aria-label="toggle sidebar" class="btn btn-square btn-ghost btn-sm mr-1 hidden lg:flex">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            class="inline-block h-5 w-5 stroke-current">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </label>

    {{-- Brand name — hanya tampil di mobile karena sidebar sudah ada logo --}}
    <div class="flex-1 lg:hidden">
        <span class="text-lg font-bold text-base-content">Tryout App</span>
    </div>
    <div class="flex-1 hidden lg:block">
        {{-- Bisa diisi breadcrumb di sini nanti --}}
    </div>

    {{-- Kanan: info user + dropdown --}}
    <div class="flex-none">
        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="flex items-center gap-2 cursor-pointer rounded-btn px-2 py-1 hover:bg-base-200 transition-colors">
                {{-- Avatar inisial --}}
                <div class="avatar placeholder">
                    <div class="bg-primary text-primary-content rounded-full w-8">
                        @if(Auth::user()->profileSiswa?->foto)
                            <img src="{{ asset('storage/' . Auth::user()->profileSiswa->foto) }}" alt="Foto Profil">
                        @elseif(Auth::user()->profileGuru?->foto)
                            <img src="{{ asset('storage/' . Auth::user()->profileGuru->foto) }}" alt="Foto Profil">
                        @else
                            <div class="skeleton h-8 w-8 shrink-0 rounded-full"></div>
                        @endif
                    </div>
                </div>
                {{-- Nama + role (hanya di desktop) --}}
                <div class="hidden sm:flex flex-col items-start leading-tight">
                    <span class="text-sm font-semibold text-base-content">{{ Auth::user()->name ?? 'User' }}</span>
                    <span class="text-xs text-base-content/50 capitalize">
                        {{ Auth::user()->roles->first()->name ?? '' }}
                    </span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="size-3 text-base-content/40 hidden sm:block">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </div>

            <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-50 w-52 p-2 shadow-lg border border-base-200 mt-1">
                <li class="menu-title px-3 py-1">
                    <span class="text-xs text-base-content/50">Masuk sebagai</span>
                    <span class="text-sm font-semibold text-base-content truncate">{{ Auth::user()->name ?? '' }}</span>
                </li>
                <div class="divider my-0.5"></div>
                @role(['guru', 'siswa'])
                <li>
                    <a href="{{ route('profile.edit') }}" class="text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                        Profil Saya
                    </a>
                </li>
                @endrole
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-error w-full text-left flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 12l-3 3m0 0 3 3m-3-3h11.25" />
                            </svg>
                            Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>