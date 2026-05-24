<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                <i class="bi bi-box-seam text-indigo-500 mr-2"></i> Paket Ujian
            </h2>
            <div class="text-sm text-gray-500 font-medium">
                Manajemen <span class="text-indigo-600">/</span> Paket Ujian
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="card bg-white shadow-xl border border-gray-100 rounded-2xl overflow-hidden">
                <div class="card-body p-0">
                    
                    <!-- HEADER & TOMBOL AKSI -->
                    <div class="bg-gray-50/50 p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Daftar Paket Ujian</h3>
                            <p class="text-sm text-gray-500 mt-1">Kelola dan jadwalkan paket ujian untuk siswa.</p>
                        </div>
                        
                        <div class="flex gap-2">
                            <a href="{{ route('guru.paket-ujian.create') }}" class="btn bg-indigo-600 hover:bg-indigo-700 text-white border-none shadow-lg shadow-indigo-200 gap-2 rounded-xl">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Buat Paket Baru
                            </a>
                        </div>
                    </div>

                    <!-- AREA TABEL -->
                    <div class="overflow-x-auto w-full">
                        <table class="table w-full text-gray-700">
                            <thead class="bg-white text-gray-600 font-semibold text-sm border-b-2 border-gray-100">
                                <tr>
                                    <th class="w-16 text-center py-5">No</th>
                                    <th>Nama Paket</th>
                                    <th>Mata Pelajaran</th>
                                    <th class="text-center">Total Soal</th>
                                    <th class="text-center">Status</th>
                                    <th class="w-48 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($pakets as $index => $paket)
                                <tr class="hover:bg-indigo-50/30 transition duration-200">
                                    <td class="text-center font-medium text-gray-400">
                                        {{ $pakets->firstItem() + $index }}
                                    </td>
                                    
                                    <td>
                                        <div class="font-bold text-gray-800">{{ $paket->nama }}</div>
                                        <div class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($paket->tanggal_mulai)->format('d M Y, H:i') }}</div>
                                    </td>

                                    <td>
                                        <div class="badge badge-ghost font-medium px-3 py-3 rounded-lg bg-gray-100 text-gray-600 border-none">
                                            {{ $paket->mataPelajaran->nama ?? '-' }}
                                        </div>
                                    </td>
                                    
                                    <td class="text-center">
                                        <span class="font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">
                                            {{ $paket->soal_count }} Butir
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        @if($paket->status == 'aktif')
                                            <span class="badge bg-green-100 text-green-700 border-none font-semibold px-3 py-2 rounded-md">Aktif</span>
                                        @elseif($paket->status == 'selesai')
                                            <span class="badge bg-gray-100 text-gray-700 border-none font-semibold px-3 py-2 rounded-md">Selesai</span>
                                        @else
                                            <span class="badge bg-yellow-100 text-yellow-700 border-none font-semibold px-3 py-2 rounded-md">Draft</span>
                                        @endif
                                    </td>
                                    
                                    <td class="text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('guru.paket-ujian.show', $paket->id) }}" class="btn btn-sm bg-indigo-50 text-indigo-600 hover:bg-indigo-100 border-none rounded-lg">
                                                Kelola Soal
                                            </a>
                                            <!-- Tambahkan tombol Hapus/Edit jika diperlukan -->
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-12">
                                        <div class="flex flex-col items-center justify-center space-y-3">
                                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-2">
                                                <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                                </svg>
                                            </div>
                                            <h4 class="text-lg font-bold text-gray-700">Belum ada paket ujian</h4>
                                            <p class="text-gray-500 max-w-sm text-center">Anda belum membuat paket ujian. Silakan klik tombol "Buat Paket Baru".</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION -->
                    @if($pakets->hasPages())
                    <div class="p-6 border-t border-gray-100 bg-gray-50/30">
                        {{ $pakets->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
