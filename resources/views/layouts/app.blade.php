<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="tryout">

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

<body class="font-sans antialiased bg-base-200">
    <div class="drawer lg:drawer-open">
        <input id="my-drawer-4" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content">
            @include('layouts.navigation')
            <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
                {{-- @if (isset($header))
                    <header class="mb-6">
                        <div class="text-2xl font-bold text-gray-800 tracking-tight">
                            {{ $header }}
                        </div>
                    </header>
                @endif --}}

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
            if (sidebarState === "false") {
                drawerToggle.checked = false;
            } else {
                drawerToggle.checked = true;
            }
            drawerToggle.addEventListener("change", function () {
                localStorage.setItem("sidebar-open", drawerToggle.checked);
            });
        });
    </script>
</body>

</html>