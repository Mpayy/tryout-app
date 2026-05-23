{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}
<x-app-layout>
    <x-slot name="header">
        Dashboard Utama Admin
    </x-slot>

    <!-- Isi kotak stats atau card kamu di sini besok -->
    <div class="p-6 bg-white rounded-xl shadow-xs border border-gray-100">
        <p class="text-gray-600">Selamat datang, kodingan layout dan sidebar kamu sudah berhasil aktif!</p>
    </div>
</x-app-layout>