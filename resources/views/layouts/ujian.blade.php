{{-- resources/views/layouts/ujian.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
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

    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />

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
    <div class="navbar bg-base-100 shadow-sm border-b border-base-300 sticky top-0 z-50">
        <div class="container mx-auto max-w-7xl px-4 flex justify-between">
            
            {{-- Logo / Judul Aplikasi --}}
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-primary text-primary-content rounded-lg flex items-center justify-center shadow-md">
                    <i class="bi bi-laptop text-xl"></i>
                </div>
                <span class="text-xl font-bold hidden sm:block text-base-content">
                    {{ config('app.name', 'CBT System') }}
                </span>
            </div>

            {{-- Info Profil & Status Ujian --}}
            <div class="flex items-center gap-3">
                <span class="badge badge-error badge-outline font-semibold py-3 px-3 shadow-sm hidden sm:inline-flex">
                    <i class="bi bi-shield-lock mr-2"></i> Mode Ujian
                </span>
                
                <div class="divider divider-horizontal mx-0"></div>
                
                <div class="flex items-center gap-3">
                    <div class="text-right hidden md:block">
                        <div class="text-sm font-bold">{{ auth()->user()->name ?? 'Nama Siswa' }}</div>
                        <div class="text-xs text-base-content/70">Peserta Ujian</div>
                    </div>
                    <div class="avatar placeholder">
                        <div class="bg-neutral text-neutral-content rounded-full w-10">
                            <span class="text-sm font-bold">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Main Content Area --}}
    <main class="pb-12 pt-4">
        @yield('content')
    </main>

    {{-- Area untuk menyisipkan script dari view (seperti script timer dan AJAX kita sebelumnya) --}}
    @stack('scripts')

    {{-- Script Keamanan Dasar (Opsional) --}}
    <script>
        // Mencegah klik kanan untuk meminimalisir inspeksi elemen atau copy-paste
        document.addEventListener('contextmenu', event => event.preventDefault());
        
        // Meringankan risiko siswa menekan F12 (Buka DevTools)
        document.onkeydown = function(e) {
            if(e.keyCode == 123) {
                return false;
            }
            if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
                return false;
            }
            if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
                return false;
            }
            if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
                return false;
            }
            if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
                return false;
            }
        }
    </script>
</body>
</html>