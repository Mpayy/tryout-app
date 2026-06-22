{{-- resources/views/layouts/ujian.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Ujian Berlangsung') — {{ config('app.name', 'Tryout App') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Mencegah siswa copy-paste teks soal */
        .no-select {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
    </style>
</head>

<body class="bg-base-200 min-h-screen font-sans text-base-content no-select antialiased">

    {{-- ===== NAVBAR ===== --}}
    <nav class="navbar bg-base-100 border-b border-base-200 sticky top-0 z-50 px-4 md:px-8 min-h-14">
        <div class="container mx-auto max-w-7xl flex justify-between items-center w-full">

            {{-- Kiri: Logo + nama app --}}
            <div class="flex items-center gap-3">
                <div
                    class="w-9 h-9 bg-primary text-primary-content rounded-xl flex items-center justify-center shrink-0">
                    {{-- Icon laptop / monitor (inline SVG, konsisten dengan layout app) --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" />
                    </svg>
                </div>
                <span class="text-base font-bold text-base-content tracking-tight hidden sm:block">
                    Tryout App
                </span>
            </div>

            {{-- Kanan: badge mode ujian + info siswa --}}
            <div class="flex items-center gap-3 sm:gap-4">

                {{-- Badge mode ujian (animasi ping = real-time feel) --}}
                <div class="badge badge-error badge-outline font-bold gap-2 py-3 px-3 hidden sm:flex items-center">
                    <span class="relative flex h-2 w-2 shrink-0">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-error opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-error"></span>
                    </span>
                    Mode Ujian
                </div>

                <div class="divider divider-horizontal mx-0 hidden sm:flex h-6 self-center"></div>

                {{-- Info siswa --}}
                <div class="flex items-center gap-2.5">
                    <div class="text-right hidden md:block leading-tight">
                        <div class="text-sm font-bold text-base-content">
                            {{ auth()->user()->name ?? 'Nama Siswa' }}
                        </div>
                        <div class="text-[10px] font-semibold text-base-content/40 uppercase tracking-wider mt-0.5">
                            Peserta Ujian
                        </div>
                    </div>

                    {{-- Avatar inisial — fix: pakai struktur DaisyUI placeholder yang benar --}}
                    <div class="avatar placeholder">
                        {{-- <div
                            class="bg-neutral text-neutral-content rounded-full w-9 ring-2 ring-offset-1 ring-base-300">
                            <span class="text-sm font-bold uppercase">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </span>
                        </div> --}}
                        <div class="avatar placeholder">
                            @if(auth()->user()->profileSiswa?->foto)
                                <div class="w-9 rounded-full">
                                    <img src="{{ asset('storage/' . auth()->user()->profileSiswa->foto) }}"
                                        alt="{{ auth()->user()->name }}" />
                                </div>
                            @else
                                <div class="skeleton h-9 w-9 shrink-0 rounded-full"></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </nav>

    {{-- ===== MAIN CONTENT ===== --}}
    <main class="py-6 min-h-[calc(100vh-3.5rem)]">
        <div class="container mx-auto max-w-7xl px-4">
            @yield('content')
        </div>
    </main>

    @stack('scripts')

    {{-- Script keamanan dasar --}}
    <script>
        // document.addEventListener('contextmenu', e => e.preventDefault());

        // document.addEventListener('keydown', function (e) {
        //     if (e.key === 'F12' || e.keyCode === 123) {
        //         e.preventDefault(); return;
        //     }
        //     if (e.ctrlKey && e.shiftKey && ['I','J','C'].includes(e.key.toUpperCase())) {
        //         e.preventDefault(); return;
        //     }
        //     if (e.ctrlKey && e.key.toUpperCase() === 'U') {
        //         e.preventDefault(); return;
        //     }
        // });
    </script>
</body>

</html>