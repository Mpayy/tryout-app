<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                <i class="bi bi-house-door text-indigo-500 mr-2"></i> Dashboard Siswa
            </h2>
            <div class="text-sm text-gray-500 font-medium">
                Siswa <span class="text-indigo-600">/</span> Dashboard
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- 1. Ringkasan Pribadi (Welcome Card) -->
            <div class="card bg-white shadow-xl border border-gray-100 rounded-2xl overflow-hidden relative">
                <div class="absolute top-0 right-0 p-8 opacity-10">
                    <i class="bi bi-mortarboard-fill text-9xl text-indigo-500"></i>
                </div>
                <div class="card-body p-6 md:p-8 relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-1">Selamat datang, {{ $user->name }}!</h2>
                        <p class="text-gray-500 font-medium">
                            @if($user->profileSiswa)
                                Kelas: <span class="text-indigo-600">{{ $user->profileSiswa->kelas->nama ?? '-' }}</span> 
                                {{-- Jurusan: <span class="text-indigo-600">{{ $user->profileSiswa->jurusan ?? '-' }}</span> --}}
                            @else
                                <span class="text-orange-500"><i class="bi bi-exclamation-circle"></i> Profil belum lengkap</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="mt-6 md:mt-0 flex gap-4">
                        <div class="stat bg-indigo-50 rounded-xl p-4 min-w-[120px] text-center">
                            <div class="stat-title text-indigo-600 font-bold text-xs uppercase">Total Ujian</div>
                            <div class="stat-value text-indigo-700 text-3xl">{{ $totalUjian }}</div>
                        </div>
                        <div class="stat bg-emerald-50 rounded-xl p-4 min-w-[120px] text-center">
                            <div class="stat-title text-emerald-600 font-bold text-xs uppercase">Rata-rata</div>
                            <div class="stat-value text-emerald-700 text-3xl">{{ round($rataNilai ?? 0, 1) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                
                <!-- Kiri: Ujian Tersedia & Riwayat -->
                <div class="xl:col-span-2 space-y-6">
                    
                    <!-- 2. Ujian Tersedia Sekarang -->
                    <div class="card bg-white shadow-xl border border-indigo-100 rounded-2xl">
                        <div class="card-body p-6">
                            <h2 class="card-title text-xl font-bold text-gray-800 mb-4 border-b border-gray-100 pb-3">
                                <i class="bi bi-stars text-orange-500"></i> Ujian Tersedia Sekarang
                            </h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @forelse($ujianTersedia as $paket)
                                <div class="card bg-white border border-gray-100 shadow-sm hover:shadow-md transition">
                                    <div class="card-body p-5">
                                        <div class="flex justify-between items-start mb-2">
                                            <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold">
                                                <i class="bi bi-journal-text text-lg"></i>
                                            </div>
                                            <span class="badge badge-success text-white badge-sm">Aktif</span>
                                        </div>
                                        
                                        <h3 class="font-bold text-lg text-gray-800 line-clamp-2 leading-tight mb-1">
                                            {{ $paket->nama }}
                                        </h3>
                                        <p class="text-sm text-gray-500 mb-4">{{ $paket->mataPelajaran->nama ?? 'N/A' }}</p>
                                        
                                        <div class="space-y-2 text-sm text-gray-600 mb-6">
                                            <div class="flex items-center gap-2">
                                                <i class="bi bi-clock text-gray-400"></i> Durasi: {{ $paket->durasi }} Menit
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <i class="bi bi-calendar-x text-gray-400"></i> Tutup: {{ \Carbon\Carbon::parse($paket->tanggal_selesai)->format('d M H:i') }}
                                            </div>
                                        </div>
                                        
                                        <div class="card-actions justify-end mt-auto">
                                            <form action="{{ route('siswa.ujian.mulai', $paket->id) }}" method="POST" class="w-full">
                                                @csrf
                                                <button type="submit" class="btn btn-primary w-full shadow-lg shadow-indigo-200 rounded-xl" onclick="return confirm('Mulai ujian sekarang? Waktu akan langsung berjalan.')">
                                                    Mulai Ujian
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-span-full py-12 text-center text-gray-500 border border-dashed border-gray-200 rounded-xl">
                                    <i class="bi bi-cup-hot text-5xl mb-3 text-gray-300 block"></i>
                                    <p class="font-medium text-lg text-gray-600">Tidak ada ujian saat ini.</p>
                                    <p class="text-sm mt-1">Bagus! Kamu bisa bersantai sejenak.</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- 3. Riwayat Ujian Terbaru -->
                    <div class="card bg-white shadow-xl border border-gray-100 rounded-2xl">
                        <div class="card-body p-6">
                            <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
                                <h2 class="card-title text-xl font-bold text-gray-800">
                                    <i class="bi bi-clock-history text-indigo-500"></i> Riwayat Ujian Terbaru
                                </h2>
                                <a href="{{ route('siswa.ujian.index') }}" class="btn btn-sm btn-ghost text-indigo-600">Lihat Semua</a>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="table w-full">
                                    <thead>
                                        <tr class="bg-gray-50 text-gray-600">
                                            <th>Ujian</th>
                                            <th>Tanggal Selesai</th>
                                            <th>Nilai</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($riwayatUjian as $riwayat)
                                        <tr class="hover">
                                            <td>
                                                <div class="font-semibold text-gray-800">{{ $riwayat->paketUjian->nama ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-500">{{ $riwayat->paketUjian->mataPelajaran->nama ?? '' }}</div>
                                            </td>
                                            <td class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($riwayat->waktu_selesai)->translatedFormat('d M Y, H:i') }}</td>
                                            <td class="font-bold text-indigo-600 text-lg">{{ $riwayat->nilai }}</td>
                                            <td>
                                                @if($riwayat->nilai >= 70)
                                                    <span class="badge badge-success badge-sm text-white font-semibold">Lulus</span>
                                                @else
                                                    <span class="badge badge-error badge-sm text-white font-semibold">Tidak Lulus</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('siswa.ujian.hasil', $riwayat->token) }}" class="btn btn-xs btn-outline text-indigo-600">Detail</a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-6 text-gray-500">Belum ada riwayat ujian.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kanan: Sidebar & Akan Datang -->
                <div class="space-y-6">
                    <!-- 4. Ujian Akan Datang -->
                    <div class="card bg-white shadow-xl border border-gray-100 rounded-2xl">
                        <div class="card-body p-6">
                            <h2 class="card-title text-base font-bold text-gray-800 mb-4 border-b border-gray-100 pb-3">
                                <i class="bi bi-calendar-event text-blue-500"></i> Ujian Akan Datang
                            </h2>
                            <ul class="space-y-4 mt-2">
                                @forelse($ujianAkanDatang as $akanDatang)
                                <li class="flex gap-4 items-start border-b border-gray-50 pb-3 last:border-0 last:pb-0">
                                    <div class="bg-blue-50 text-blue-600 rounded-lg p-2 text-center min-w-[50px]">
                                        <div class="text-xs font-bold uppercase">{{ \Carbon\Carbon::parse($akanDatang->tanggal_mulai)->format('M') }}</div>
                                        <div class="text-xl font-black">{{ \Carbon\Carbon::parse($akanDatang->tanggal_mulai)->format('d') }}</div>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 text-sm line-clamp-1">{{ $akanDatang->nama }}</h4>
                                        <div class="text-xs text-gray-500 mt-1"><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($akanDatang->tanggal_mulai)->format('H:i') }} WIB</div>
                                        <div class="text-xs text-gray-500 mt-0.5"><i class="bi bi-journal"></i> {{ $akanDatang->mataPelajaran->nama ?? '' }}</div>
                                    </div>
                                </li>
                                @empty
                                <li class="text-sm text-gray-500 italic text-center py-4">Belum ada jadwal ujian mendatang.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <!-- 5. Grafik Mini / Quick Stats -->
                    <div class="card bg-indigo-600 text-white shadow-xl rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600">
                        <div class="card-body p-6 text-center">
                            <i class="bi bi-graph-up-arrow text-4xl mb-2 opacity-80"></i>
                            <h2 class="card-title text-white justify-center mb-1">Tetap Semangat!</h2>
                            <p class="text-sm opacity-90 mb-4">Setiap ujian adalah kesempatan untuk belajar lebih baik.</p>
                            @if(count($riwayatUjian) >= 2)
                                @php
                                    $terakhir = $riwayatUjian[0]->nilai;
                                    $sebelumnya = $riwayatUjian[1]->nilai;
                                    $naik = $terakhir > $sebelumnya;
                                @endphp
                                <div class="bg-white/20 rounded-xl p-3 text-sm">
                                    Nilai terakhirmu {{ $terakhir }}. 
                                    @if($naik)
                                        <br><span class="text-emerald-300 font-bold"><i class="bi bi-arrow-up"></i> Naik dari sebelumnya!</span>
                                    @else
                                        <br><span class="text-orange-200"><i class="bi bi-dash"></i> Terus tingkatkan belajarmu.</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
