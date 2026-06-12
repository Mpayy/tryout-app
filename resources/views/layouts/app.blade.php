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

{{-- PERUBAHAN: Tambah min-h-screen agar konten pendek tidak kelihatan menggantung --}}
<body class="font-sans antialiased bg-base-200 min-h-screen">
    <div class="drawer lg:drawer-open">
        <input id="my-drawer-4" type="checkbox" class="drawer-toggle" />

        {{-- Drawer Content (main area) --}}
        <div class="drawer-content flex flex-col min-h-screen">
            @include('layouts.navigation')

            {{-- PERUBAHAN: Tambah max-w dan mx-auto agar konten tidak terlalu lebar di layar besar --}}
            <main class="flex-1 p-5 lg:p-7 overflow-y-auto">
                <div class="animate-fade-in">
                    {{ $slot }}
                </div>
            </main>
        </div>

        @include('layouts.sidebar')
    </div>

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