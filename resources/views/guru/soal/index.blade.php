{{-- <x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                <i class="bi bi-journal-text text-indigo-500 mr-2"></i> Bank Soal
            </h2>
            <div class="text-sm text-gray-500 font-medium">
                Manajemen <span class="text-indigo-600">/</span> Bank Soal
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- 1. STATISTIK RINGKAS (DAISYUI STATS) -->
            <div class="stats shadow-xl border border-gray-100 w-full bg-white rounded-2xl overflow-hidden">
                <div class="stat hover:bg-gray-50 transition duration-300">
                    <div class="stat-figure text-indigo-500">
                        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-title text-gray-500 font-semibold tracking-wide">Total Soal Terinput</div>
                    <div class="stat-value text-indigo-700 text-4xl mt-2 font-black">{{ $soals->total() }}</div>
                    <div class="stat-desc mt-2 text-indigo-500 font-medium flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        Target minimal: 30 soal
                    </div>
                </div>
                
                <div class="stat hover:bg-gray-50 transition duration-300">
                    <div class="stat-figure text-emerald-500">
                        <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-title text-gray-500 font-semibold tracking-wide">Mata Pelajaran</div>
                    <div class="stat-value text-emerald-700 text-2xl mt-2 truncate max-w-[250px]">
                        Semua Mapel
                    </div>
                    <div class="stat-desc mt-2 font-semibold text-emerald-500">
                        Total yang Anda ajar
                    </div>
                </div>
            </div>

            <!-- 2. AREA UTAMA DAFTAR SOAL -->
            <div class="card bg-white shadow-xl border border-gray-100 rounded-2xl overflow-hidden">
                <div class="card-body p-0">
                    
                    <!-- HEADER & TOMBOL AKSI -->
                    <div class="bg-gray-50/50 p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Daftar Butir Pertanyaan</h3>
                            <p class="text-sm text-gray-500 mt-1">Kumpulan soal yang siap digunakan dalam paket ujian.</p>
                        </div>
                        
                        <div class="flex gap-2">
                            <a href="{{ route('guru.soal.create') }}" class="btn bg-indigo-600 hover:bg-indigo-700 text-white border-none shadow-lg shadow-indigo-200 gap-2 rounded-xl">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Tambah Soal
                            </a>
                        </div>
                    </div>

                    <!-- AREA TABEL DAFTAR SOAL -->
                    <div class="overflow-x-auto w-full">
                        <table class="table w-full text-gray-700">
                            <thead class="bg-white text-gray-600 font-semibold text-sm border-b-2 border-gray-100">
                                <tr>
                                    <th class="w-16 text-center py-5">No</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Pertanyaan</th>
                                    <th class="w-32 text-center">Kunci</th>
                                    <th class="w-24 text-center">Kesulitan</th>
                                    <th class="w-32 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($soals as $index => $soal)
                                <tr class="hover:bg-indigo-50/30 transition duration-200">
                                    <td class="text-center font-medium text-gray-400">
                                        {{ $soals->firstItem() + $index }}
                                    </td>
                                    
                                    <td>
                                        <div class="badge badge-ghost font-medium px-3 py-3 rounded-lg bg-gray-100 text-gray-600 border-none">
                                            {{ $soal->mataPelajaran->nama ?? '-' }}
                                        </div>
                                    </td>

                                    <td class="font-medium text-gray-800 max-w-md">
                                        <div class="line-clamp-2 leading-relaxed">
                                            {{ strip_tags($soal->konten) }}
                                        </div>
                                    </td>
                                    
                                    <td class="text-center">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 font-bold border border-emerald-200 shadow-sm">
                                            {{ $soal->jawabanBenar->label ?? '-' }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        @if($soal->tingkat_kesulitan == 'mudah')
                                            <span class="badge bg-green-100 text-green-700 border-none font-semibold px-3 py-2 rounded-md">Mudah</span>
                                        @elseif($soal->tingkat_kesulitan == 'sulit')
                                            <span class="badge bg-red-100 text-red-700 border-none font-semibold px-3 py-2 rounded-md">Sulit</span>
                                        @else
                                            <span class="badge bg-yellow-100 text-yellow-700 border-none font-semibold px-3 py-2 rounded-md">Sedang</span>
                                        @endif
                                    </td>
                                    
                                    <td class="text-center">
                                        <div class="flex justify-center gap-2">
                                            <form action="{{ route('guru.soal.destroy', $soal->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus soal ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-circle btn-ghost btn-sm text-gray-400 hover:text-red-500 hover:bg-red-50 transition">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-12">
                                        <div class="flex flex-col items-center justify-center space-y-3">
                                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-2">
                                                <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <h4 class="text-lg font-bold text-gray-700">Belum ada soal</h4>
                                            <p class="text-gray-500 max-w-sm text-center">Anda belum membuat soal ujian. Silakan klik tombol "Tambah Soal" di kanan atas untuk mulai menyusun bank soal.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION -->
                    @if($soals->hasPages())
                    <div class="p-6 border-t border-gray-100 bg-gray-50/30">
                        {{ $soals->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}
<x-app-layout>
    <x-slot name="header">
        Manajemen Soal
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- 1. STATISTIK RINGKAS (DAISYUI STATS) -->
            <div class="stats shadow-xl border border-gray-100 w-full bg-white rounded-2xl overflow-hidden">
                <div class="stat hover:bg-gray-50 transition duration-300">
                    <div class="stat-figure text-indigo-500">
                        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-title text-gray-500 font-semibold tracking-wide">Total Soal Terinput</div>
                    <div class="stat-value text-indigo-700 text-4xl mt-2 font-black">{{ $totalSoal }}</div>
                    <div class="stat-desc mt-2 text-indigo-500 font-medium flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        Target minimal: 30 soal
                    </div>
                </div>
                
                <div class="stat hover:bg-gray-50 transition duration-300">
                    <div class="stat-figure text-emerald-500">
                        <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-title text-gray-500 font-semibold tracking-wide">Mata Pelajaran</div>
                    <div class="stat-value text-emerald-700 text-2xl mt-2 truncate max-w-[250px]">
                        {{ $mataPelajaranYangDiAjar->map(function($mapel) {
                            return $mapel->nama;
                        })->implode('', ', ') }}
                    </div>
                    <div class="stat-desc mt-2 font-semibold text-emerald-500">
                        Total yang Anda ajar
                    </div>
                </div>
            </div>
            
            <!-- 2. AREA UTAMA DAFTAR SOAL -->
            <div class="card bg-white shadow-sm border border-gray-100 rounded-xl overflow-hidden">
                <div class="card-body p-0">
                    
                    <!-- HEADER & TOMBOL AKSI -->
                    <div class="bg-gray-50/40 p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Daftar Butir Pertanyaan</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Kumpulan soal yang siap digunakan dalam paket ujian.</p>
                        </div>
                        
                        <div class="flex gap-2 w-full sm:w-auto">
                            <!-- Mengubah tombol mengarah ke bulk-create dengan warna utama Tema (Teal) -->
                            <a href="{{ route('guru.soal.create') }}" class="btn btn-primary sm:w-auto w-full gap-2 rounded-lg text-white font-semibold">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Tambah Soal (Bulk Mode)
                            </a>
                        </div>
                    </div>

                    <!-- AREA TABEL DAFTAR SOAL -->
                    <div class="overflow-x-auto w-full">
                        <table class="table w-full text-gray-700">
                            <thead class="bg-gray-50/70 text-gray-600 font-semibold text-sm border-b border-gray-100">
                                <tr>
                                    <th class="w-16 text-center py-4">No</th>
                                    <th class="w-48">Mata Pelajaran</th>
                                    <th>Pertanyaan</th>
                                    <th>Pilihan Jawaban</th>
                                    <th class="w-24 text-center">Kunci</th>
                                    {{-- <th class="w-28 text-center">Kesulitan</th> --}}
                                    <th class="w-24 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($soals as $index => $soal)
                                <tr class="hover:bg-gray-50/40 transition duration-200">
                                    <td class="text-center font-medium text-gray-400">
                                        {{ $index + 1 }}
                                    </td>
                                    
                                    <td>
                                        <div class="badge bg-gray-100 text-gray-600 font-semibold px-3 py-2.5 rounded-md border-none text-xs">
                                            {{ $soal->mataPelajaran->nama ?? '-' }}
                                        </div>
                                    </td>

                                    <td class="font-medium text-gray-800 max-w-md">
                                        <div class="line-clamp-2 leading-relaxed text-sm">
                                            {{ strip_tags($soal->konten) }}
                                        </div>
                                    </td>

                                    <td>
                                        @foreach($soal->pilihanJawaban as $pilihan)
                                            <div class="line-clamp-2 leading-relaxed text-sm">
                                                <span class="font-bold">{{ $pilihan->label }}</span>. {{ $pilihan->konten }}
                                            </div>
                                        @endforeach
                                    </td>
                                    
                                    <td class="text-center">
                                        <span class="line-clamp-2 leading-relaxed text-sm">
                                            {{ $soal->jawabanBenar->label ?? '-' }}. {{ $soal->jawabanBenar->konten }}
                                        </span>
                                    </td>

                                    {{-- <td class="text-center">
                                        @if($soal->tingkat_kesulitan == 'mudah')
                                            <span class="badge bg-green-50 text-green-700 border border-green-100 font-semibold px-2.5 py-2 rounded-md text-xs">Mudah</span>
                                        @elseif($soal->tingkat_kesulitan == 'sulit')
                                            <span class="badge bg-red-50 text-red-700 border border-red-100 font-semibold px-2.5 py-2 rounded-md text-xs">Sulit</span>
                                        @else
                                            <span class="badge bg-amber-50 text-amber-700 border border-amber-100 font-semibold px-2.5 py-2 rounded-md text-xs">Sedang</span>
                                        @endif
                                    </td> --}}
                                    
                                    <td class="text-center">
                                        <div class="flex justify-center gap-1">
                                            <form action="{{ route('guru.soal.destroy', $soal->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus soal ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-ghost btn-xs text-gray-400 hover:text-red-500 p-1 rounded-md transition">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-16">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-1">
                                                <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <h4 class="text-base font-bold text-gray-700">Belum Ada Soal Ujian</h4>
                                            <p class="text-gray-400 text-sm max-w-xs text-center">Silakan tekan tombol tambah soal di atas untuk merakit bank soal masal.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION -->
                    {{-- @if($soals->hasPages())
                    <div class="p-4 border-t border-gray-100 bg-gray-50/20">
                        {{ $soals->links() }}
                    </div>
                    @endif --}}
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>