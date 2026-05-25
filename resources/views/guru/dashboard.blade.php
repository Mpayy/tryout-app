<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                <i class="bi bi-person-workspace text-indigo-500 mr-2"></i> Dashboard Guru
            </h2>
            <div class="text-sm text-gray-500 font-medium">
                Guru <span class="text-indigo-600">/</span> Dashboard
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- 1. Stat Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Total Soal -->
                <div class="stat bg-white shadow-xl rounded-2xl border border-gray-100">
                    <div class="stat-figure text-indigo-500">
                        <i class="bi bi-file-earmark-text text-4xl"></i>
                    </div>
                    <div class="stat-title text-gray-500 font-semibold">Total Soal</div>
                    <div class="stat-value text-gray-800">{{ $totalSoal }}</div>
                    <div class="stat-desc text-gray-400">Dibuat oleh Anda</div>
                </div>

                <!-- Total Paket -->
                <div class="stat bg-white shadow-xl rounded-2xl border border-gray-100">
                    <div class="stat-figure text-blue-500">
                        <i class="bi bi-box-seam text-4xl"></i>
                    </div>
                    <div class="stat-title text-gray-500 font-semibold">Total Paket</div>
                    <div class="stat-value text-gray-800">{{ $totalPaket }}</div>
                    <div class="stat-desc text-gray-400">Seluruh paket ujian</div>
                </div>

                <!-- Paket Aktif -->
                <div class="stat bg-white shadow-xl rounded-2xl border border-gray-100">
                    <div class="stat-figure text-emerald-500">
                        <i class="bi bi-play-circle text-4xl"></i>
                    </div>
                    <div class="stat-title text-gray-500 font-semibold">Paket Aktif</div>
                    <div class="stat-value text-gray-800">{{ $paketAktif }}</div>
                    <div class="stat-desc text-gray-400">Sedang berjalan</div>
                </div>

                <!-- Total Siswa Ikut -->
                <div class="stat bg-white shadow-xl rounded-2xl border border-gray-100">
                    <div class="stat-figure text-orange-500">
                        <i class="bi bi-people text-4xl"></i>
                    </div>
                    <div class="stat-title text-gray-500 font-semibold">Siswa Partisipan</div>
                    <div class="stat-value text-gray-800">{{ $totalSiswaIkut }}</div>
                    <div class="stat-desc text-gray-400">Mengerjakan ujian Anda</div>
                </div>
            </div>

            <!-- Draft Reminder -->
            @if($draftPaket->count() > 0)
            <div class="alert alert-warning shadow-lg rounded-2xl">
                <i class="bi bi-exclamation-triangle text-xl"></i>
                <div>
                    <h3 class="font-bold">Ada {{ $draftPaket->count() }} Paket Ujian masih Draft!</h3>
                    <div class="text-xs">Jangan lupa untuk mem-publish paket ujian agar bisa dikerjakan oleh siswa.</div>
                </div>
                <div class="flex-none">
                    <a href="{{ route('guru.paket-ujian.index') }}" class="btn btn-sm">Lihat Draft</a>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- 2. Paket Ujian Aktif -->
                <div class="xl:col-span-2 space-y-6">
                    <div class="card bg-white shadow-xl border border-gray-100 rounded-2xl">
                        <div class="card-body p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="card-title text-xl font-bold text-gray-800">
                                    <i class="bi bi-broadcast text-emerald-500"></i> Paket Ujian Aktif
                                </h2>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @forelse($listPaketAktif as $paket)
                                <div class="border border-gray-100 rounded-xl p-4 hover:shadow-md transition bg-gray-50/50 flex flex-col h-full">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="font-bold text-lg text-gray-800 line-clamp-1" title="{{ $paket->nama }}">{{ $paket->nama }}</h3>
                                        <span class="badge badge-success text-white badge-sm mt-1">Aktif</span>
                                    </div>
                                    <div class="text-sm text-gray-600 mb-3 space-y-1">
                                        <div><i class="bi bi-book text-gray-400 mr-1"></i> {{ $paket->mataPelajaran->nama ?? 'N/A' }}</div>
                                        <div><i class="bi bi-clock text-gray-400 mr-1"></i> {{ $paket->durasi }} Menit</div>
                                        <div><i class="bi bi-calendar-event text-gray-400 mr-1"></i> Tutup: {{ \Carbon\Carbon::parse($paket->tanggal_selesai)->format('d M Y, H:i') }}</div>
                                    </div>
                                    
                                    <div class="mt-auto pt-4 border-t border-gray-200">
                                        <div class="flex justify-between items-center text-sm mb-2">
                                            <span class="text-gray-500">Progress Peserta</span>
                                            <span class="font-bold text-indigo-600">{{ $paket->selesai_count }} / {{ $paket->total_peserta }} Selesai</span>
                                        </div>
                                        @php 
                                            $percent = $paket->total_peserta > 0 ? ($paket->selesai_count / $paket->total_peserta) * 100 : 0; 
                                        @endphp
                                        <progress class="progress progress-indigo w-full" value="{{ $percent }}" max="100"></progress>
                                    </div>

                                    <div class="mt-4 flex gap-2">
                                        <a href="#" class="btn btn-sm btn-outline text-indigo-600 hover:bg-indigo-600 hover:text-white flex-1">Monitoring</a>
                                        <a href="#" class="btn btn-sm btn-outline btn-success flex-1">Rekap Nilai</a>
                                    </div>
                                </div>
                                @empty
                                <div class="col-span-full py-8 text-center text-gray-500 border border-dashed border-gray-200 rounded-xl">
                                    <i class="bi bi-inbox text-4xl mb-2 text-gray-300 block"></i>
                                    Tidak ada paket ujian yang sedang aktif.
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- 3. Hasil Ujian Terbaru -->
                    <div class="card bg-white shadow-xl border border-gray-100 rounded-2xl">
                        <div class="card-body p-6">
                            <h2 class="card-title text-xl font-bold text-gray-800 mb-4">
                                <i class="bi bi-trophy text-orange-500"></i> Hasil Ujian Terbaru
                            </h2>
                            <div class="overflow-x-auto">
                                <table class="table w-full">
                                    <thead>
                                        <tr class="bg-gray-50 text-gray-600">
                                            <th>Paket Ujian</th>
                                            <th>Peserta</th>
                                            <th>Rata-rata</th>
                                            <th>Tertinggi</th>
                                            <th>Terendah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($hasilTerbaru as $hasil)
                                        <tr class="hover">
                                            <td class="font-semibold text-gray-800">{{ $hasil->nama }}</td>
                                            <td>{{ $hasil->total_peserta }}</td>
                                            <td class="font-bold text-indigo-600">{{ round($hasil->rata_rata_nilai ?? 0, 1) }}</td>
                                            <td class="text-emerald-600 font-medium">{{ round($hasil->nilai_tertinggi ?? 0, 1) }}</td>
                                            <td class="text-error font-medium">{{ round($hasil->nilai_terendah ?? 0, 1) }}</td>
                                            <td>
                                                <a href="#" class="btn btn-xs btn-ghost text-indigo-600">Detail</a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-6 text-gray-500">Belum ada hasil ujian yang selesai.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Quick Actions Sidebar -->
                <div class="space-y-6">
                    <div class="card bg-indigo-600 text-white shadow-xl rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600">
                        <div class="card-body p-6">
                            <h2 class="card-title text-white mb-4">Aksi Cepat Guru</h2>
                            <div class="grid gap-3">
                                <a href="{{ route('guru.soal.create') }}" class="btn bg-white/20 hover:bg-white text-white hover:text-indigo-600 border-none justify-start">
                                    <i class="bi bi-plus-circle text-lg mr-2"></i> Buat Soal Baru
                                </a>
                                <a href="{{ route('guru.paket-ujian.create') }}" class="btn bg-white/20 hover:bg-white text-white hover:text-indigo-600 border-none justify-start">
                                    <i class="bi bi-folder-plus text-lg mr-2"></i> Buat Paket Ujian
                                </a>
                                <a href="{{ route('guru.soal.index') }}" class="btn bg-white/20 hover:bg-white text-white hover:text-indigo-600 border-none justify-start">
                                    <i class="bi bi-collection text-lg mr-2"></i> Bank Soal Saya
                                </a>
                                <a href="{{ route('guru.paket-ujian.index') }}" class="btn bg-white/20 hover:bg-white text-white hover:text-indigo-600 border-none justify-start">
                                    <i class="bi bi-box-seam text-lg mr-2"></i> Kelola Paket Ujian
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>