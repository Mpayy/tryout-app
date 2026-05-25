<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                <i class="bi bi-card-checklist text-indigo-500 mr-2"></i> Kelola Soal Ujian
            </h2>
            <a href="{{ route('guru.paket-ujian.index') }}" class="btn btn-sm bg-gray-100 hover:bg-gray-200 border-none text-gray-700">
                Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- INFORMASI PAKET -->
            <div class="stats shadow-sm border border-gray-100 w-full bg-white rounded-2xl overflow-hidden">
                <div class="stat">
                    <div class="stat-figure text-indigo-500">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div class="stat-title font-bold text-gray-500">Nama Paket</div>
                    <div class="stat-value text-indigo-700 text-2xl mt-1">{{ $paketUjian->nama }}</div>
                    <div class="stat-desc mt-1 font-medium text-gray-400">Mapel: {{ $paketUjian->mataPelajaran->nama }}</div>
                </div>
                <div class="stat">
                    <div class="stat-title font-bold text-gray-500">Jadwal Ujian</div>
                    <div class="stat-value text-gray-700 text-xl mt-1">{{ \Carbon\Carbon::parse($paketUjian->tanggal_mulai)->format('d M Y, H:i') }}</div>
                    <div class="stat-desc mt-1 font-medium text-gray-400">Durasi: {{ $paketUjian->durasi }} Menit</div>
                </div>
                <div class="stat">
                    <div class="stat-title font-bold text-gray-500">Status & Publikasi</div>
                    <div class="mt-2">
                        <form action="{{ route('guru.paket-ujian.status', $paketUjian->id) }}" method="POST" class="flex gap-2">
                            @csrf @method('PATCH')
                            <select name="status" class="select select-bordered rounded-lg" onchange="this.form.submit()">
                                <option value="draft" {{ $paketUjian->status == 'draft' ? 'selected' : '' }}>Draft (Siswa tidak bisa lihat)</option>
                                <option value="aktif" {{ $paketUjian->status == 'aktif' ? 'selected' : '' }}>Aktif (Siap dikerjakan)</option>
                                <option value="selesai" {{ $paketUjian->status == 'selesai' ? 'selected' : '' }}>Selesai (Ditutup)</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>

            <!-- GRID DUA KOLOM -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- KOLOM KIRI: BANK SOAL -->
                <div class="card bg-white shadow-xl border border-gray-100 rounded-2xl">
                    <div class="card-body p-0">
                        <div class="bg-gray-50/50 p-5 border-b border-gray-100 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Pilih dari Bank Soal</h3>
                                <p class="text-xs text-gray-500 mt-1">Centang soal untuk dimasukkan ke paket.</p>
                            </div>
                            <span class="badge bg-indigo-100 text-indigo-700 font-bold border-none">{{ $bankSoal->count() }} Tersedia</span>
                        </div>
                        
                        <form action="{{ route('guru.paket-ujian.tambah-soal', $paketUjian->id) }}" method="POST">
                            @csrf
                            <div class="overflow-y-auto max-h-[500px] p-5">
                                @forelse($bankSoal as $soal)
                                <label class="flex items-start gap-4 p-4 border border-gray-100 rounded-xl hover:bg-indigo-50/30 cursor-pointer mb-3 transition">
                                    <input type="checkbox" name="soal_id[]" value="{{ $soal->id }}" class="checkbox checkbox-primary mt-1" />
                                    <div class="flex-1">
                                        <div class="text-sm text-gray-800 leading-relaxed font-medium">
                                            {{ Str::limit(strip_tags($soal->konten), 120) }}
                                        </div>
                                        <div class="mt-2 text-xs flex gap-2">
                                            <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded font-semibold">Kunci: {{ $soal->jawabanBenar->label ?? '-' }}. {{ $soal->jawabanBenar->konten }}</span>
                                            @if($soal->tingkat_kesulitan == 'sulit')
                                                <span class="bg-red-50 text-red-600 px-2 py-1 rounded font-semibold">Sulit</span>
                                            @elseif($soal->tingkat_kesulitan == 'mudah')
                                                <span class="bg-green-50 text-green-600 px-2 py-1 rounded font-semibold">Mudah</span>
                                            @else
                                                <span class="bg-yellow-50 text-yellow-600 px-2 py-1 rounded font-semibold">Sedang</span>
                                            @endif
                                        </div>
                                    </div>
                                </label>
                                @empty
                                <div class="text-center py-8 text-gray-400 text-sm">
                                    Semua soal di bank soal sudah dimasukkan ke paket ini,<br>atau Anda belum membuat soal di bank soal.
                                </div>
                                @endforelse
                            </div>
                            
                            @if($bankSoal->count() > 0)
                            <div class="p-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                                <button type="submit" class="btn btn-primary text-white border-none rounded-xl px-5">
                                    <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    Tambahkan ke Paket
                                </button>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>

                <!-- KOLOM KANAN: SOAL DI PAKET -->
                <div class="card bg-white shadow-xl border border-gray-100 rounded-2xl">
                    <div class="card-body p-0">
                        <div class="bg-indigo-50/50 p-5 border-b border-indigo-100 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-bold text-indigo-900">Soal di Dalam Paket Ini</h3>
                                <p class="text-xs text-indigo-600 mt-1">Daftar soal yang akan diujikan ke siswa.</p>
                            </div>
                            <span class="badge bg-indigo-600 text-white font-bold border-none">{{ $paketUjian->soal->count() }} Butir</span>
                        </div>
                        
                        <div class="overflow-y-auto max-h-[500px] p-5">
                            @forelse($paketUjian->soal as $index => $soalPaket)
                            <div class="flex items-start gap-4 p-4 border border-indigo-50 rounded-xl mb-3 bg-white hover:border-indigo-200 transition group">
                                <div class="w-8 h-8 shrink-0 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm text-gray-800 leading-relaxed font-medium">
                                        {{ Str::limit(strip_tags($soalPaket->konten), 120) }}
                                    </div>
                                    <div class="mt-2 text-xs text-gray-500">
                                        Kunci: <span class="font-bold text-indigo-600">{{ $soalPaket->jawabanBenar->label ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <form action="{{ route('guru.paket-ujian.hapus-soal', [$paketUjian->id, $soalPaket->id]) }}" method="POST" onsubmit="return confirm('Hapus soal ini dari paket?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-sm text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg opacity-0 group-hover:opacity-100 transition">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @empty
                            <div class="flex flex-col items-center justify-center py-12 space-y-3">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-2">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                    </svg>
                                </div>
                                <p class="text-gray-400 text-sm font-medium">Belum ada soal di paket ini.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
