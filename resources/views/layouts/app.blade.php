<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Tryout App') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-base-200 min-h-screen">
    <div class="drawer lg:drawer-open">
        <input id="my-drawer-4" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col min-h-screen">

            @include('layouts.navigation')

            <main class="flex-1 p-5 lg:p-7 overflow-y-auto">
                <div class="animate-fade-in">
                    {{ $slot }}
                </div>
            </main>
        </div>

        @include('layouts.sidebar')
    </div>

    @include('sweetalert2::index')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                title: 'Success',
                text: "{{ session('success') }}",
                icon: 'success',
                timer: 1000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                title: 'Error',
                text: "{{ session('error') }}",
                icon: 'error',
                timer: 1000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            // 1. Buat cetakan (mixin) Toast-nya dulu satu kali
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            // 2. Lakukan perulangan di sini. Setiap ada error, tembak satu Toast!
            @foreach ($errors->all() as $error)
                Toast.fire({
                    icon: 'error',
                    title: "{{ $error }}" // Mengambil teks error individu dari Laravel
                });
            @endforeach
        </script>
    @endif

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const drawerToggle = document.getElementById("my-drawer-4");
            const sidebarState = localStorage.getItem("sidebar-open");

            if (window.innerWidth >= 1024) {
                drawerToggle.checked = sidebarState !== "false";
            }

            drawerToggle.addEventListener("change", function () {
                localStorage.setItem("sidebar-open", drawerToggle.checked);
            });
        });
    </script>

</body>

</html>