<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <div class="drawer">
            <input id="my-drawer-1" type="checkbox" class="drawer-toggle" />
            <div class="drawer-content">
                <!-- 1. BARIS ATAS: Navigation Bar Utama (Bawaan Breeze yang sudah kamu perbaiki tadi) -->
                @include('layouts.navigation')

                <!-- 2. BARIS BAWAH: Pembagian Sidebar (Kiri) dan Konten (Kanan) -->
                {{-- <div class="flex flex-1"> --}}
                    <!-- Main Content Area -->
                    <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
                        <!-- Header Halaman Dinamis (Jika ada) -->
                        @if (isset($header))
                            <header class="mb-6">
                                <div class="text-2xl font-bold text-gray-800 tracking-tight">
                                    {{ $header }}
                                </div>
                            </header>
                        @endif

                        <!-- Konten Halaman Utama (Isi CRUD / Dashboard akan masuk ke sini) -->
                        <div class="animate-fade-in">
                            {{ $slot }}
                        </div>
                    </main>
                    {{--
                </div> --}}
            </div>
            <!-- Memanggil Sidebar Komponen -->
            @include('layouts.sidebar')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</body>

</html>