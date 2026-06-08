<div class="drawer-side is-drawer-close:overflow-visible">
    <label for="my-drawer-4" aria-label="close sidebar" class="drawer-overlay"></label>
    <div class="flex min-h-full flex-col items-start is-drawer-close:w-16 is-drawer-open:w-64 bg-primary">
        <!-- Sidebar content here -->
        <ul class="menu w-full grow gap-1">
            <li>
                @role('admin')
                <a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'text-warning font-bold' : 'text-primary-content font-bold hover:bg-secondary' }} is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Homepage">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span class="is-drawer-close:hidden">Dashboard</span>
                </a>
                @endrole
                @role('guru')
                <a href="{{ route('guru.dashboard') }}"
                    class="{{ request()->routeIs('guru.dashboard') ? 'text-warning font-bold' : 'text-primary-content font-bold hover:bg-secondary' }} is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Homepage">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span class="is-drawer-close:hidden">Dashboard</span>
                </a>
                @endrole
                @role('siswa')
                <a href="{{ route('siswa.dashboard') }}"
                    class="{{ request()->routeIs('siswa.dashboard') ? 'text-warning font-bold' : 'text-primary-content font-bold hover:bg-secondary' }} is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Homepage">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span class="is-drawer-close:hidden">Dashboard</span>
                </a>
                @endrole
            </li>
            <!-- MENU KHUSUS ADMIN -->
            @role('admin')
            <li>
                <a href="{{ route('admin.users.index') }}"
                    class="{{ request()->routeIs('admin.users.index') ? 'text-warning font-bold' : 'text-primary-content font-bold hover:bg-secondary' }} is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Guru">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <span class="is-drawer-close:hidden">Guru</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.siswa.index') }}"
                    class="{{ request()->routeIs('admin.siswa.index') ? 'text-warning font-bold' : 'text-primary-content font-bold hover:bg-secondary' }} is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Siswa">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.26 10.174L12 6.329l7.74 3.845a.75.75 0 0 1 0 1.352L12 15.371l-7.74-3.845a.75.75 0 0 1 0-1.352Zm0 0v5.169c0 .734.406 1.411 1.059 1.748L12 21l6.681-3.71A2.003 2.003 0 0 0 19.74 15.56V10.174Z" />
                    </svg>
                    <span class="is-drawer-close:hidden">Siswa</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.mapels.index') }}"
                    class="{{ request()->routeIs('admin.mapels.index') ? 'text-warning font-bold' : 'text-primary-content font-bold hover:bg-secondary' }} is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Mata Pelajaran">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="is-drawer-close:hidden">Mata Pelajaran</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.kelas.index') }}"
                    class="{{ request()->routeIs('admin.kelas.index') ? 'text-warning font-bold' : 'text-primary-content font-bold hover:bg-secondary' }} is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Kelas">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                    <span class="is-drawer-close:hidden">Kelas</span>
                </a>
            </li>
            @endrole

            @role('guru')
            <li>
                <a href="{{ route('guru.soal.index') }}"
                    class="{{ request()->routeIs('guru.soal.index', 'guru.soal.create') ? 'text-warning font-bold' : 'text-primary-content font-bold hover:bg-secondary' }} is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Kelas">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span class="is-drawer-close:hidden">Bank Soal</span>
                </a>
            </li>
            <li>
                <a href="{{ route('guru.paket-ujian.index') }}"
                    class="{{ request()->routeIs('guru.paket-ujian.index') ? 'text-warning font-bold' : 'text-primary-content font-bold hover:bg-secondary' }} is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Kelas">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span class="is-drawer-close:hidden">Paket Tryout</span>
                </a>
            </li>
            @endrole

            <!-- MENU KHUSUS SISWA -->
            @role('siswa')
            <li>
                <a href="{{ route('siswa.ujian.index') }}"
                    class="{{ request()->routeIs('siswa.ujian.index') ? 'text-warning font-bold' : 'text-primary-content font-bold hover:bg-secondary' }} is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Kelas">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span class="is-drawer-close:hidden">Daftar Ujian</span>
                </a>
            </li>
            @endrole

            <!-- MENU LAPORAN / REKAP -->
            {{-- <div class="text-xs font-bold text-gray-400 px-4 pt-4 pb-1 uppercase tracking-wider">Analitik</div>
            <li>
                <a href="#" class="hover:bg-gray-100">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H3a2 2 0 01-2-2V5a2 2 0 012-2h4l2 2h4l2-2h4a2 2 0 012 2v14a2 2 0 01-2 2z" />
                    </svg>
                    Laporan Nilai
                </a>
            </li> --}}

            <!-- MENU EDIT PROFILE -->
            @role(['guru', 'siswa'])
            <li>
                <a href="{{ route('profile.edit') }}"
                    class="{{ request()->routeIs('profile.edit') ? 'text-warning font-bold' : 'text-primary-content font-bold hover:bg-secondary' }} is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Kelas">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span class="is-drawer-close:hidden">Profil Saya</span>
                </a>
            </li>
            @endrole
            <li class="mt-auto">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>

                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="text-error font-bold hover:bg-secondary is-drawer-close:tooltip is-drawer-close:tooltip-right"
                    data-tip="Logout">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 12l-3 3m0 0 3 3m-3-3h11.25" />
                    </svg>

                    <span class="is-drawer-close:hidden">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>