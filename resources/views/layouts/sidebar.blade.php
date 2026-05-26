<div class="drawer-side">
    <label for="my-drawer-1" aria-label="close sidebar" class="drawer-overlay"></label>
    <ul class="menu rounded-box gap-1 bg-base-200 min-h-full w-64 p-4">
        <!-- Sidebar content here -->
        <!-- Menu Global / Dashboard (Bisa diakses semua role yang login) -->
        <li>
            @role('admin')
            <a href="{{ route('admin.dashboard') }}"
                class="{{ request()->routeIs('admin.dashboard') ? 'active bg-indigo-50 text-indigo-700 font-semibold' : '' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>
            @endrole
            @role('guru')
            <a href="{{ route('guru.dashboard') }}"
                class="{{ request()->routeIs('guru.dashboard') ? 'active bg-indigo-50 text-indigo-700 font-semibold' : '' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>
            @endrole
            @role('siswa')
            <a href="{{ route('siswa.dashboard') }}"
                class="{{ request()->routeIs('siswa.dashboard') ? 'active bg-indigo-50 text-indigo-700 font-semibold' : '' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>
            @endrole
        </li>
        <!-- MENU KHUSUS ADMIN -->
        @role('admin')
        <div class="text-xs font-bold text-gray-400 px-4 pt-4 pb-1 uppercase tracking-wider">Manajemen</div>
        <li>
            <a href="{{ route('admin.users.index') }}"
                class="{{ request()->routeIs('admin.users.index') ? 'active bg-indigo-50 text-indigo-700 font-semibold' : '' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Manajemen User
            </a>
        </li>
        <li>
            <a href="{{ route('admin.mapels.index') }}"
                class="{{ request()->routeIs('admin.mapels.index') ? 'active bg-indigo-50 text-indigo-700 font-semibold' : '' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Mata Pelajaran
            </a>
        </li>
        @endrole

        {{-- MENU KHUSUS GURU (Bank Soal & Paket) --}}
        @role('guru')
        <div class="text-xs font-bold text-gray-400 px-4 pt-4 pb-1 uppercase tracking-wider">Ujian</div>
        <li>
            <a href="{{ route('guru.soal.index') }}"
                class="{{ request()->routeIs('guru.soal.*') ? 'active bg-teal-50 text-teal-700 font-semibold' : '' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                Bank Soal
            </a>
        </li>
        <li>
            <a href="{{ route('guru.paket-ujian.index') }}"
                class="{{ request()->routeIs('guru.paket-ujian.*') ? 'active bg-teal-50 text-teal-700 font-semibold' : '' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                Paket Ujian
            </a>
        </li>
        @endrole

        <!-- MENU KHUSUS SISWA -->
        @role('siswa')
        <div class="text-xs font-bold text-gray-400 px-4 pt-4 pb-1 uppercase tracking-wider">Ujian</div>
        <li>
            <a href="{{ route('siswa.ujian.index') }}"
                class="{{ request()->routeIs('siswa.ujian.*') ? 'active bg-teal-50 text-teal-700 font-semibold' : '' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Daftar Ujian
            </a>
        </li>
        @endrole

        <!-- MENU LAPORAN / REKAP -->
        <div class="text-xs font-bold text-gray-400 px-4 pt-4 pb-1 uppercase tracking-wider">Analitik</div>
        <li>
            <a href="#" class="hover:bg-gray-100">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H3a2 2 0 01-2-2V5a2 2 0 012-2h4l2 2h4l2-2h4a2 2 0 012 2v14a2 2 0 01-2 2z" />
                </svg>
                Laporan Nilai
            </a>
        </li>

        <!-- MENU EDIT PROFILE -->
        @role(['guru', 'siswa'])
        <div class="text-xs font-bold text-gray-400 px-4 pt-4 pb-1 uppercase tracking-wider">Pengaturan</div>
        <li>
            <a href="{{ route('profil.index') }}" class="hover:bg-gray-100">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H3a2 2 0 01-2-2V5a2 2 0 012-2h4l2 2h4l2-2h4a2 2 0 012 2v14a2 2 0 01-2 2z" />
                </svg>
                Edit Profile
            </a>
        </li>
        @endrole
    </ul>
</div>