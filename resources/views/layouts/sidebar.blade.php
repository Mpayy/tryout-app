{{--
PERUBAHAN DARI VERSI LAMA:
1. Tambah area logo/brand di atas sidebar (sebelumnya langsung menu)
2. FIX BUG: semua menu item pakai icon "home" yang sama — sekarang tiap menu punya icon yang sesuai
3. Tambah divider + label section untuk grup menu
4. Logout dipindah ke navbar dropdown, tapi tetap ada di sidebar sebagai fallback
5. Active state lebih jelas: pakai bg-primary-content/10 + teks warning
6. Hover state lebih halus: hover:bg-primary-content/10
--}}
<div class="drawer-side z-40">
    <label for="my-drawer-4" aria-label="close sidebar" class="drawer-overlay"></label>

    <div
        class="flex min-h-full flex-col bg-primary is-drawer-close:w-16 is-drawer-open:w-64 transition-all duration-200">

        {{-- ===== LOGO / BRAND AREA ===== --}}
        <div class="flex items-center gap-3 px-4 py-4 border-b border-primary-content/10 min-h-16">
            {{-- Icon brand --}}
            <div class="shrink-0 w-8 h-8 bg-primary-content/20 rounded-lg flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="size-5 text-primary-content">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                </svg>
            </div>
            <span class="is-drawer-close:hidden text-primary-content font-bold text-base tracking-tight">Tryout
                App</span>
        </div>

        {{-- ===== NAVIGATION MENU ===== --}}
        <ul class="menu w-full grow gap-0.5 px-2 py-3">

            {{-- Dashboard — semua role --}}
            <li>
                @role('admin')
                <a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'bg-primary-content/15 text-warning font-semibold' : 'text-primary-content/80 hover:bg-primary-content/10 hover:text-primary-content font-medium' }} rounded-lg is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span class="is-drawer-close:hidden">Dashboard</span>
                </a>
                @endrole
                @role('guru')
                <a href="{{ route('guru.dashboard') }}"
                    class="{{ request()->routeIs('guru.dashboard') ? 'bg-primary-content/15 text-warning font-semibold' : 'text-primary-content/80 hover:bg-primary-content/10 hover:text-primary-content font-medium' }} rounded-lg is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span class="is-drawer-close:hidden">Dashboard</span>
                </a>
                @endrole
                @role('siswa')
                <a href="{{ route('siswa.dashboard') }}"
                    class="{{ request()->routeIs('siswa.dashboard') ? 'bg-primary-content/15 text-warning font-semibold' : 'text-primary-content/80 hover:bg-primary-content/10 hover:text-primary-content font-medium' }} rounded-lg is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span class="is-drawer-close:hidden">Dashboard</span>
                </a>
                @endrole
            </li>

            {{-- ===== MENU ADMIN ===== --}}
            @role('admin')
            {{-- Label seksi — hanya tampil saat sidebar terbuka --}}
            <li class="is-drawer-close:hidden mt-3 mb-1 px-1">
                <span class="text-[10px] font-bold uppercase tracking-widest text-primary-content/40">Manajemen</span>
            </li>
            <li class="is-drawer-close:block is-drawer-open:hidden">
                <div class="divider my-1 border-primary-content/10"></div>
            </li>

            <li>
                <a href="{{ route('admin.users.index') }}"
                    class="{{ request()->routeIs('admin.users.index') ? 'bg-primary-content/15 text-warning font-semibold' : 'text-primary-content/80 hover:bg-primary-content/10 hover:text-primary-content font-medium' }} rounded-lg is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Guru">
                    {{-- Icon: orang dengan topi mortarboard = guru --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                    </svg>
                    <span class="is-drawer-close:hidden">Guru</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.siswa.index') }}"
                    class="{{ request()->routeIs('admin.siswa.index') ? 'bg-primary-content/15 text-warning font-semibold' : 'text-primary-content/80 hover:bg-primary-content/10 hover:text-primary-content font-medium' }} rounded-lg is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Siswa">
                    {{-- Icon: orang dengan buku = siswa --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <span class="is-drawer-close:hidden">Siswa</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.mapels.index') }}"
                    class="{{ request()->routeIs('admin.mapels.index') ? 'bg-primary-content/15 text-warning font-semibold' : 'text-primary-content/80 hover:bg-primary-content/10 hover:text-primary-content font-medium' }} rounded-lg is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Mata Pelajaran">
                    {{-- Icon: buku = mata pelajaran --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="is-drawer-close:hidden">Mata Pelajaran</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.kelas.index') }}"
                    class="{{ request()->routeIs('admin.kelas.index') ? 'bg-primary-content/15 text-warning font-semibold' : 'text-primary-content/80 hover:bg-primary-content/10 hover:text-primary-content font-medium' }} rounded-lg is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Kelas">
                    {{-- Icon: grup orang = kelas --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                    <span class="is-drawer-close:hidden">Kelas</span>
                </a>
            </li>

            <li class="is-drawer-close:hidden mt-3 mb-1 px-1">
                <span class="text-[10px] font-bold uppercase tracking-widest text-primary-content/40">Rekap</span>
            </li>

            <li>
                <a href="{{ route('admin.rekap.index') }}"
                    class="{{ request()->routeIs('admin.rekap.index', 'admin.rekap.show') ? 'bg-primary-content/15 text-warning font-semibold' : 'text-primary-content/80 hover:bg-primary-content/10 hover:text-primary-content font-medium' }} rounded-lg is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Rekap Nilai">
                    {{-- Icon: clipboard list = rekap nilai --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                    </svg>
                    <span class="is-drawer-close:hidden">Rekap Nilai</span>
                </a>
            </li>
            @endrole

            {{-- ===== MENU GURU ===== --}}
            @role('guru')
            <li class="is-drawer-close:hidden mt-3 mb-1 px-1">
                <span class="text-[10px] font-bold uppercase tracking-widest text-primary-content/40">Konten</span>
            </li>

            <li>
                <a href="{{ route('guru.soal.index') }}"
                    class="{{ request()->routeIs('guru.soal.index', 'guru.soal.create') ? 'bg-primary-content/15 text-warning font-semibold' : 'text-primary-content/80 hover:bg-primary-content/10 hover:text-primary-content font-medium' }} rounded-lg is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Bank Soal">
                    {{-- Icon: dokumen dengan tanda tanya = soal --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                    </svg>
                    <span class="is-drawer-close:hidden">Bank Soal</span>
                </a>
            </li>

            <li>
                <a href="{{ route('guru.paket-ujian.index') }}"
                    class="{{ request()->routeIs('guru.paket-ujian.index') ? 'bg-primary-content/15 text-warning font-semibold' : 'text-primary-content/80 hover:bg-primary-content/10 hover:text-primary-content font-medium' }} rounded-lg is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Paket Tryout">
                    {{-- Icon: clipboard list = paket ujian --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                    </svg>
                    <span class="is-drawer-close:hidden">Paket Tryout</span>
                </a>
            </li>

            <li class="is-drawer-close:hidden mt-3 mb-1 px-1">
                <span class="text-[10px] font-bold uppercase tracking-widest text-primary-content/40">Rekap Nilai</span>
            </li>

            <li>
                <a href="{{ route('guru.rekap.index') }}"
                    class="{{ request()->routeIs('guru.rekap.index') ? 'bg-primary-content/15 text-warning font-semibold' : 'text-primary-content/80 hover:bg-primary-content/10 hover:text-primary-content font-medium' }} rounded-lg is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Rekap Nilai">
                    {{-- Icon: clipboard list = paket ujian --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                    </svg>
                    <span class="is-drawer-close:hidden">Rekap Nilai</span>
                </a>
            </li>
            @endrole

            {{-- ===== MENU SISWA ===== --}}
            @role('siswa')
            <li class="is-drawer-close:hidden mt-3 mb-1 px-1">
                <span class="text-[10px] font-bold uppercase tracking-widest text-primary-content/40">Ujian</span>
            </li>

            <li>
                <a href="{{ route('siswa.ujian.index') }}"
                    class="{{ request()->routeIs('siswa.ujian.index') ? 'bg-primary-content/15 text-warning font-semibold' : 'text-primary-content/80 hover:bg-primary-content/10 hover:text-primary-content font-medium' }} rounded-lg is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Daftar Ujian">
                    {{-- Icon: pensil di kertas = ujian --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    <span class="is-drawer-close:hidden">Daftar Ujian</span>
                </a>
            </li>
            @endrole

            {{-- ===== PROFIL (guru & siswa) ===== --}}
            @role(['guru', 'siswa'])
            <li class="is-drawer-close:hidden mt-3 mb-1 px-1">
                <span class="text-[10px] font-bold uppercase tracking-widest text-primary-content/40">Akun</span>
            </li>
            <li>
                <a href="{{ route('profile.edit') }}"
                    class="{{ request()->routeIs('profile.edit') ? 'bg-primary-content/15 text-warning font-semibold' : 'text-primary-content/80 hover:bg-primary-content/10 hover:text-primary-content font-medium' }} rounded-lg is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Profil Saya">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <span class="is-drawer-close:hidden">Profil Saya</span>
                </a>
            </li>
            @endrole

            {{-- ===== LOGOUT (di bawah, push ke bawah dengan mt-auto) ===== --}}
            <li class="mt-auto pt-2 border-t border-primary-content/10">
                <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();"
                    class="text-primary-content/60 hover:bg-error/20 hover:text-error rounded-lg font-medium is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Keluar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 12l-3 3m0 0 3 3m-3-3h11.25" />
                    </svg>
                    <span class="is-drawer-close:hidden">Keluar</span>
                </a>
            </li>
        </ul>
    </div>
</div>