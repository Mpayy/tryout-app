{{-- resources/views/layouts/ujian.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="tryout">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Meta CSRF Token sangat penting untuk AJAX request saat memilih jawaban --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Ujian Berlangsung') - {{ config('app.name', 'CBT System') }}</title>

    {{-- Bootstrap Icons (Karena kita menggunakan icon bi-flag, bi-check-circle di view ujian) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Menggunakan Vite untuk meload Tailwind & DaisyUI (Standard Laravel 11) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{--
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" /> --}}

    <style>
        /* Mencegah siswa menyeleksi teks untuk menyalin (copy-paste) soal */
        .no-select {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
    </style>
</head>

<body class="bg-base-200 min-h-screen font-sans text-base-content no-select antialiased">

    {{-- Navbar Minimalis --}}
    <div class="navbar bg-base-100 shadow-sm border-b border-base-200 sticky top-0 z-50 px-4 md:px-8">
        <div class="container mx-auto max-w-7xl flex justify-between items-center w-full">

            {{-- Navbar Start: Logo / Judul Aplikasi --}}
            <div class="flex items-center gap-3">
                <div class="avatar">
                    <div
                        class="w-10 h-10 bg-primary text-primary-content rounded-xl flex items-center justify-center shadow-md">
                        <i class="bi bi-laptop text-xl"></i>
                    </div>
                </div>
                <span class="text-lg font-bold hidden sm:block text-base-content tracking-tight">
                    {{ config('app.name', 'CBT System') }}
                </span>
            </div>

            {{-- Navbar End: Info Profil & Status Ujian --}}
            <div class="flex items-center gap-4">
                {{-- Status Mode Ujian --}}
                <div class="badge badge-error badge-outline font-bold gap-1.5 py-3.5 px-3 shadow-2xs hidden sm:flex">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-error opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-error"></span>
                    </span>
                    Mode Ujian Terkunci
                </div>

                {{-- Divider Vertikal (Hanya muncul jika mode ujian & teks profil sama-sama tampil) --}}
                <div class="divider divider-horizontal mx-0 hidden sm:flex"></div>

                {{-- Profil Komponen --}}
                <div class="flex items-center gap-3">
                    <div class="text-right hidden md:block">
                        <div class="text-sm font-bold text-base-content leading-tight">
                            {{ auth()->user()->name ?? 'Nama Siswa' }}
                        </div>
                        <div class="text-[11px] font-semibold text-base-content/50 uppercase tracking-wider mt-0.5">
                            Peserta Ujian
                        </div>
                    </div>

                    {{-- Avatar dengan Placeholder Huruf Depan --}}
                    <div class="avatar placeholder">
                        <div class="bg-neutral text-neutral-content rounded-full w-10 ring-2 ring-base-200 shadow-xs">
                            <span class="text-sm font-bold uppercase">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Main Content Area --}}
    <main class="py-6 min-h-[calc(100vh-4rem)] bg-base-200/40">
        <div class="container mx-auto max-w-7xl px-4">
            @yield('content')
        </div>
    </main>

    {{-- Area untuk menyisipkan script dari view (seperti script timer dan AJAX kita sebelumnya) --}}
    @stack('scripts')

    {{-- Script Keamanan Dasar (Opsional) --}}
    <script>
        // Mematikan Klik Kanan (Aman)
        document.addEventListener('contextmenu', event => event.preventDefault());

        // Mematikan Tombol DevTools (F12, Ctrl+Shift+I, dll) dengan standar modern
        document.addEventListener('keydown', function (e) {
            // 1. Matikan F12
            if (e.key === 'F12' || e.keyCode === 123) {
                e.preventDefault();
                e.stopPropagation();
                return;
            }

            // 2. Matikan Ctrl+Shift+I (Inspect), Ctrl+Shift+J (Console), Ctrl+Shift+C (Element Selector)
            if (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J' || e.key === 'C' || e.keyCode === 73 || e.keyCode === 74 || e.keyCode === 67)) {
                e.preventDefault();
                e.stopPropagation();
                return;
            }

            // 3. Matikan Ctrl+U (View Source)
            if (e.ctrlKey && (e.key === 'u' || e.key === 'U' || e.keyCode === 85)) {
                e.preventDefault();
                e.stopPropagation();
                return;
            }
        });
    </script>
</body>

</html>