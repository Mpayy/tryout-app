<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                <i class="bi bi-display text-indigo-500 mr-2"></i> Daftar Ujian
            </h2>
            <div class="text-sm text-gray-500 font-medium">
                Akademik <span class="text-indigo-600">/</span> Ujian
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('info'))
                <div class="alert alert-info shadow-lg rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($paketUjian as $paket)
                <div class="card bg-white shadow-xl border border-gray-100 rounded-2xl hover:-translate-y-1 hover:shadow-2xl transition duration-300 group">
                    <div class="card-body p-6">
                        <div class="flex justify-between items-start mb-4">
                            {{-- <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition">
                                <i class="bi bi-journal-check text-xl"></i>
                            </div> --}}
                            <span class="badge bg-green-100 text-green-700 font-bold border-none px-3 py-2 rounded-md">Aktif</span>
                        </div>
                        
                        <h2 class="card-title text-xl font-bold text-gray-800 line-clamp-2 leading-tight h-14">
                            {{ $paket->nama }}
                        </h2>
                        
                        <p class="text-sm text-gray-500 font-medium mt-1">
                            Mapel: <span class="text-gray-700">{{ $paket->mataPelajaran->nama }}</span>
                        </p>
                        <p class="text-sm text-gray-500 font-medium mt-1">
                            Guru: <span class="text-gray-700">{{ $paket->guru->name }}</span>
                        </p>
                        
                        <div class="divider my-2"></div>
                        
                        <div class="flex flex-col gap-2 mb-6">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="bi bi-calendar-event w-6 text-gray-400"></i>
                                <span>{{ \Carbon\Carbon::parse($paket->tanggal_mulai)->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="bi bi-clock-history w-6 text-gray-400"></i>
                                <span>Durasi: {{ $paket->durasi }} Menit</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="bi bi-ui-checks-grid w-6 text-gray-400"></i>
                                <span>Total: {{ $paket->soal_count }} Soal</span>
                            </div>
                        </div>

                        <div class="card-actions justify-end mt-auto">
                            <form action="{{ route('siswa.ujian.mulai', $paket->id) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="btn bg-indigo-600 hover:bg-indigo-700 text-white w-full border-none shadow-lg shadow-indigo-200 rounded-xl" onclick="return confirm('Mulai ujian sekarang? Waktu akan langsung berjalan.')">
                                    Mulai Kerjakan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full">
                    <div class="flex flex-col items-center justify-center py-16 bg-white rounded-2xl shadow-sm border border-gray-100">
                        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                            <i class="bi bi-cup-hot text-4xl text-gray-300"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-700">Belum Ada Ujian</h3>
                        <p class="text-gray-500 mt-2 text-center max-w-sm">Saat ini tidak ada paket ujian yang sedang aktif atau dijadwalkan untuk Anda.</p>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- PAGINATION -->
            @if($paketUjian->hasPages())
            <div class="mt-6">
                {{ $paketUjian->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
