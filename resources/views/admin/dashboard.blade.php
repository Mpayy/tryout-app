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
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                <i class="bi bi-speedometer2 text-indigo-500 mr-2"></i> Dashboard Admin
            </h2>
            <div class="text-sm text-gray-500 font-medium">
                Admin <span class="text-indigo-600">/</span> Dashboard
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- 1. Stat Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Siswa -->
                <div class="stat bg-white shadow-xl rounded-2xl border border-gray-100">
                    <div class="stat-figure text-indigo-500">
                        <i class="bi bi-people text-4xl"></i>
                    </div>
                    <div class="stat-title text-gray-500 font-semibold">Total Siswa</div>
                    <div class="stat-value text-gray-800">{{ $totalSiswa }}</div>
                    <div class="stat-desc text-gray-400">Terdaftar di sistem</div>
                </div>

                <!-- Total Guru -->
                <div class="stat bg-white shadow-xl rounded-2xl border border-gray-100">
                    <div class="stat-figure text-blue-500">
                        <i class="bi bi-person-badge text-4xl"></i>
                    </div>
                    <div class="stat-title text-gray-500 font-semibold">Total Guru</div>
                    <div class="stat-value text-gray-800">{{ $totalGuru }}</div>
                    <div class="stat-desc text-gray-400">Aktif mengajar</div>
                </div>

                <!-- Total Soal & Paket -->
                <div class="stat bg-white shadow-xl rounded-2xl border border-gray-100">
                    <div class="stat-figure text-emerald-500">
                        <i class="bi bi-collection text-4xl"></i>
                    </div>
                    <div class="stat-title text-gray-500 font-semibold">Bank Soal</div>
                    <div class="stat-value text-gray-800">{{ $totalSoal }}</div>
                    <div class="stat-desc text-gray-400">Dalam {{ $totalPaket }} Paket Ujian</div>
                </div>

                <!-- Ujian Aktif Hari Ini -->
                <div class="stat bg-white shadow-xl rounded-2xl border border-gray-100 md:col-span-1">
                    <div class="stat-figure text-orange-500">
                        <i class="bi bi-calendar-event text-4xl"></i>
                    </div>
                    <div class="stat-title text-gray-500 font-semibold">Ujian Hari Ini</div>
                    <div class="stat-value text-gray-800">{{ $ujianAktifHariIni }}</div>
                    <div class="stat-desc text-gray-400">Paket sedang berjalan</div>
                </div>

                <!-- Siswa Ujian Sekarang -->
                <div class="stat bg-white shadow-xl rounded-2xl border border-gray-100 md:col-span-2">
                    <div class="stat-figure text-error">
                        <span class="relative flex h-10 w-10">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-error opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-10 w-10 bg-error/20 flex items-center justify-center">
                              <i class="bi bi-laptop text-error text-xl"></i>
                          </span>
                        </span>
                    </div>
                    <div class="stat-title text-gray-500 font-semibold">Siswa Ujian Sekarang</div>
                    <div class="stat-value text-gray-800">{{ $siswaSedangUjian }}</div>
                    <div class="stat-desc text-gray-400">Real-time sessions</div>
                </div>
            </div>

            <!-- 2. Aktivitas & Monitoring -->
            <div class="card bg-white shadow-xl border border-gray-100 rounded-2xl">
                <div class="card-body p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="card-title text-xl font-bold text-gray-800">
                            <i class="bi bi-activity text-indigo-500"></i> Monitoring Ujian Live
                        </h2>
                        <a href="#" class="btn btn-sm btn-outline text-indigo-600 hover:bg-indigo-600 hover:text-white border-indigo-200">Semua Data</a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr class="bg-gray-50 text-gray-600">
                                    <th>Nama Paket Ujian</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Sedang Mengerjakan</th>
                                    <th>Sudah Submit</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($monitoringUjian as $paket)
                                <tr class="hover">
                                    <td class="font-semibold text-gray-800">{{ $paket->nama }}</td>
                                    <td>{{ $paket->mataPelajaran->nama ?? 'N/A' }}</td>
                                    <td>
                                        <div class="badge badge-warning gap-2 font-bold p-3">
                                            <span class="relative flex h-3 w-3">
                                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-warning opacity-75"></span>
                                              <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
                                            </span>
                                            {{ $paket->sedang_mengerjakan }} Siswa
                                        </div>
                                    </td>
                                    <td>
                                        <div class="badge badge-success text-white font-bold p-3">{{ $paket->sudah_submit }} Siswa</div>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-xs btn-ghost text-indigo-600">Detail</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-8 text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="bi bi-cup-hot text-4xl text-gray-300 mb-2"></i>
                                            <p>Tidak ada ujian yang sedang berlangsung saat ini.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 3. Rekap & Quick Actions Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Rekap Cepat -->
                <div class="card bg-white shadow-xl border border-gray-100 rounded-2xl lg:col-span-2">
                    <div class="card-body p-6">
                        <h2 class="card-title text-xl font-bold text-gray-800 mb-4">
                            <i class="bi bi-bar-chart-line text-emerald-500"></i> Rekap Nilai Terbaru
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="table w-full table-sm">
                                <thead>
                                    <tr class="bg-gray-50 text-gray-600">
                                        <th>Paket Ujian</th>
                                        <th>Peserta</th>
                                        <th>Rata-rata Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rekapTerbaru as $rekap)
                                    <tr class="hover">
                                        <td class="font-semibold text-gray-700 truncate max-w-xs">{{ $rekap->nama }}</td>
                                        <td>{{ $rekap->total_peserta }} Siswa</td>
                                        <td>
                                            @php $avg = round($rekap->rata_rata_nilai ?? 0, 1); @endphp
                                            <div class="flex items-center gap-2">
                                                <progress class="progress w-24 {{ $avg >= 70 ? 'progress-success' : 'progress-warning' }}" value="{{ $avg }}" max="100"></progress>
                                                <span class="font-bold {{ $avg >= 70 ? 'text-emerald-600' : 'text-orange-500' }}">{{ $avg }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-gray-500 py-4">Belum ada rekap ujian</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Info Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="card bg-indigo-600 text-white shadow-xl rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600">
                        <div class="card-body p-6">
                            <h2 class="card-title text-white mb-2">Aksi Cepat</h2>
                            <div class="grid grid-cols-2 gap-3">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-sm bg-white/20 hover:bg-white text-white hover:text-indigo-600 border-none justify-start">
                                    <i class="bi bi-person-plus"></i> Kelola User
                                </a>
                                <a href="{{ route('admin.mapels.index') }}" class="btn btn-sm bg-white/20 hover:bg-white text-white hover:text-indigo-600 border-none justify-start">
                                    <i class="bi bi-book"></i> Mapel
                                </a>
                                <a href="#" class="btn btn-sm bg-white/20 hover:bg-white text-white hover:text-indigo-600 border-none justify-start">
                                    <i class="bi bi-display"></i> Monitor
                                </a>
                                <a href="#" class="btn btn-sm bg-white/20 hover:bg-white text-white hover:text-indigo-600 border-none justify-start">
                                    <i class="bi bi-file-earmark-excel"></i> Laporan
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Siswa Baru -->
                    <div class="card bg-white shadow-xl border border-gray-100 rounded-2xl">
                        <div class="card-body p-6">
                            <h2 class="card-title text-base font-bold text-gray-800 mb-2">
                                <i class="bi bi-person-exclamation text-orange-500"></i> Profil Belum Lengkap
                            </h2>
                            <ul class="space-y-3 mt-2">
                                @forelse($siswaBaru as $siswa)
                                <li class="flex justify-between items-center text-sm border-b border-gray-50 pb-2 last:border-0 last:pb-0">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-gray-700">{{ $siswa->name }}</span>
                                        <span class="text-xs text-gray-400">{{ $siswa->email }}</span>
                                    </div>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-xs btn-outline btn-warning text-orange-500">Ingatkan</a>
                                </li>
                                @empty
                                <li class="text-sm text-gray-500 italic">Semua profil lengkap.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>