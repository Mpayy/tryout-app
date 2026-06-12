<x-app-layout>
<div class="space-y-6">

    {{-- ═══════════════════════════════════════════
         HEADER PERSONAL
    ════════════════════════════════════════════ --}}
    <div class="card bg-primary text-primary-content shadow-sm overflow-hidden">
        <div class="card-body p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center gap-4">
            {{-- Avatar --}}
            <div class="avatar placeholder shrink-0">
                <div class="bg-primary-content/20 text-primary-content rounded-2xl w-14 text-xl font-bold uppercase">
                    {{ substr($user->name, 0, 2) }}
                </div>
            </div>
            <div class="flex-1">
                <h1 class="text-lg font-bold leading-tight">Halo, {{ $user->name }}!</h1>
                <p class="text-sm text-primary-content/70 mt-0.5">
                    {{ $user->profileSiswa?->kelas?->nama ?? 'Belum masuk kelas' }}
                    @if($user->profileSiswa?->nis)
                        · NIS {{ $user->profileSiswa->nis }}
                    @endif
                </p>
            </div>
            <div class="text-right hidden sm:block">
                <div class="text-sm text-primary-content/60">{{ \Carbon\Carbon::now()->translatedFormat('l') }}</div>
                <div class="text-base font-bold">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         STAT CARDS (3 tile)
    ════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <div class="card bg-base-100 border border-base-200 shadow-sm">
            <div class="card-body p-5 flex flex-row items-center gap-4">
                <div class="p-3 bg-primary/10 text-primary rounded-xl shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-base-content">{{ $totalUjianSelesai }}</div>
                    <div class="text-xs text-base-content/50 mt-0.5">Ujian Diselesaikan</div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-200 shadow-sm">
            <div class="card-body p-5 flex flex-row items-center gap-4">
                <div class="p-3 {{ $rataNilai >= 75 ? 'bg-success/10 text-success' : 'bg-warning/10 text-warning' }} rounded-xl shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5" />
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold {{ $rataNilai >= 75 ? 'text-success' : 'text-warning' }}">
                        {{ $rataNilai }}
                    </div>
                    <div class="text-xs text-base-content/50 mt-0.5">Rata-rata Nilai</div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-200 shadow-sm">
            <div class="card-body p-5 flex flex-row items-center gap-4">
                <div class="p-3 bg-success/10 text-success rounded-xl shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0" />
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-success">{{ $nilaiTerbaik }}</div>
                    <div class="text-xs text-base-content/50 mt-0.5">Nilai Terbaik</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         UJIAN TERSEDIA SEKARANG
    ════════════════════════════════════════════ --}}
    <div>
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-bold text-base-content">Ujian Tersedia</h2>
            <a href="{{ route('siswa.ujian.index') }}" class="text-xs text-primary font-semibold hover:underline">
                Lihat semua →
            </a>
        </div>

        @if($ujianTersedia->isEmpty())
            <div class="card bg-base-100 border border-base-200 shadow-sm">
                <div class="card-body items-center text-center py-10">
                    <div class="w-12 h-12 bg-base-200 border border-base-300 rounded-full flex items-center justify-center mb-3 text-base-content/30">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-base-content/60">Tidak ada ujian aktif saat ini</p>
                    <p class="text-xs text-base-content/40 mt-0.5">Cek kembali nanti atau lihat jadwal ujian akan datang.</p>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($ujianTersedia as $paket)
                @php
                    $sisaHari = now()->diffInDays(\Carbon\Carbon::parse($paket->tanggal_selesai), false);
                    $sedang   = $paket->sesi_berlangsung;
                @endphp
                <div class="card bg-base-100 border {{ $sedang ? 'border-warning/40' : 'border-base-200' }} shadow-sm hover:shadow-md transition-all duration-200">
                    <div class="card-body p-5 gap-3">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-bold text-base-content leading-snug line-clamp-2">{{ $paket->nama }}</h3>
                                <p class="text-xs font-semibold text-primary mt-0.5">{{ $paket->mataPelajaran->nama }}</p>
                            </div>
                            @if($sedang)
                                <span class="badge badge-warning badge-sm font-semibold gap-1 shrink-0">
                                    <span class="relative flex h-1.5 w-1.5">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-current opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-current"></span>
                                    </span>
                                    Lanjutkan
                                </span>
                            @elseif($sisaHari <= 1)
                                <span class="badge badge-error badge-sm font-semibold shrink-0">Segera Tutup</span>
                            @else
                                <span class="badge badge-success badge-sm font-semibold shrink-0">Aktif</span>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div class="bg-base-200/60 border border-base-200 rounded-lg p-2 text-center">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-base-content/40">Durasi</div>
                                <div class="text-xs font-bold text-base-content mt-0.5">{{ $paket->durasi }} Menit</div>
                            </div>
                            <div class="bg-base-200/60 border border-base-200 rounded-lg p-2 text-center">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-base-content/40">Soal</div>
                                <div class="text-xs font-bold text-base-content mt-0.5">{{ $paket->jumlah_soal }} Butir</div>
                            </div>
                        </div>

                        <div class="text-xs text-base-content/40">
                            Tutup: {{ \Carbon\Carbon::parse($paket->tanggal_selesai)->translatedFormat('d M Y, H:i') }}
                        </div>

                        @if($sedang)
                            <a href="{{ route('siswa.ujian.show', $sedang->token) }}"
                                class="btn btn-warning w-full btn-sm font-bold gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
                                </svg>
                                Lanjutkan Ujian
                            </a>
                        @else
                            <form action="{{ route('siswa.ujian.mulai', $paket->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary w-full btn-sm font-bold gap-2"
                                    onclick="return confirm('Mulai ujian? Waktu langsung berjalan.')">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
                                    </svg>
                                    Mulai Kerjakan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════
         BARIS BAWAH: Riwayat + Chart Tren + Akan Datang
    ════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- Riwayat ujian + ranking (xl:2/3) --}}
        <div class="xl:col-span-2 card bg-base-100 border border-base-200 shadow-sm">
            <div class="card-body p-0">
                <div class="px-5 py-4 border-b border-base-200">
                    <h3 class="text-sm font-bold text-base-content">Riwayat Ujian</h3>
                    <p class="text-xs text-base-content/40 mt-0.5">5 ujian terakhir yang sudah diselesaikan</p>
                </div>

                @if($riwayatUjian->isEmpty())
                    <div class="py-12 text-center text-sm text-base-content/40">
                        Kamu belum menyelesaikan ujian apapun.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="table table-sm w-full">
                            <thead class="bg-base-200/60">
                                <tr class="text-[10px] font-bold uppercase tracking-wide text-base-content/50">
                                    <th>Ujian</th>
                                    <th class="text-center">Nilai</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Peringkat</th>
                                    <th class="text-center">Selesai</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-base-200">
                                @foreach($riwayatUjian as $sesi)
                                <tr class="hover:bg-base-200/20 align-middle">
                                    <td>
                                        <div class="text-sm font-bold text-base-content truncate max-w-44">
                                            {{ $sesi->paketUjian->nama ?? '-' }}
                                        </div>
                                        <div class="text-xs text-primary font-medium">
                                            {{ $sesi->paketUjian->mataPelajaran->nama ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="flex flex-col items-center gap-0.5">
                                            <span class="text-base font-bold {{ $sesi->lulus ? 'text-success' : 'text-error' }}">
                                                {{ $sesi->nilai }}
                                            </span>
                                            <progress class="progress w-10 h-1 {{ $sesi->lulus ? 'progress-success' : 'progress-error' }}"
                                                value="{{ $sesi->nilai }}" max="100"></progress>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($sesi->lulus)
                                            <span class="badge badge-success badge-xs font-bold">Lulus</span>
                                        @else
                                            <span class="badge badge-error badge-xs font-bold">Tidak Lulus</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="flex flex-col items-center">
                                            <span class="text-sm font-bold text-base-content">
                                                @if($sesi->ranking === 1) 🥇
                                                @elseif($sesi->ranking === 2) 🥈
                                                @elseif($sesi->ranking === 3) 🥉
                                                @else #{{ $sesi->ranking }}
                                                @endif
                                            </span>
                                            <span class="text-[10px] text-base-content/40">dari {{ $sesi->total_peserta }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center text-xs text-base-content/50">
                                        {{ \Carbon\Carbon::parse($sesi->waktu_selesai)->format('d M Y') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- Chart tren nilai --}}
                @if($riwayatUjian->isNotEmpty())
                <div class="px-5 pb-5 pt-4 border-t border-base-200">
                    <p class="text-xs font-bold text-base-content/50 uppercase tracking-wider mb-3">Tren Nilai (10 ujian terakhir)</p>
                    <div class="relative h-36">
                        <canvas id="chartTren"></canvas>
                    </div>
                </div>
                @endif

            </div>
        </div>

        {{-- Ujian akan datang (xl:1/3) --}}
        <div class="card bg-base-100 border border-base-200 shadow-sm">
            <div class="card-body p-0">
                <div class="px-5 py-4 border-b border-base-200">
                    <h3 class="text-sm font-bold text-base-content">Akan Datang</h3>
                    <p class="text-xs text-base-content/40 mt-0.5">Ujian yang segera dibuka</p>
                </div>

                @if($ujianAkanDatang->isEmpty())
                    <div class="flex flex-col items-center justify-center py-12 text-center px-4">
                        <p class="text-sm font-medium text-base-content/50">Tidak ada jadwal ujian</p>
                        <p class="text-xs text-base-content/30 mt-1">Jadwal akan muncul di sini.</p>
                    </div>
                @else
                    <div class="divide-y divide-base-200">
                        @foreach($ujianAkanDatang as $paket)
                        @php
                            $sisaHari = now()->diffInDays(\Carbon\Carbon::parse($paket->tanggal_mulai), false);
                        @endphp
                        <div class="px-5 py-3.5 hover:bg-base-200/20 transition-colors">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-bold text-base-content truncate">{{ $paket->nama }}</div>
                                    <div class="text-xs text-primary font-medium mt-0.5">{{ $paket->mataPelajaran->nama ?? '-' }}</div>
                                    <div class="text-[10px] text-base-content/40 mt-1">
                                        {{ \Carbon\Carbon::parse($paket->tanggal_mulai)->translatedFormat('d M Y, H:i') }}
                                    </div>
                                </div>
                                <div class="shrink-0 text-right">
                                    @if($sisaHari === 0)
                                        <span class="badge badge-error badge-xs font-bold">Hari ini</span>
                                    @elseif($sisaHari === 1)
                                        <span class="badge badge-warning badge-xs font-bold">Besok</span>
                                    @else
                                        <span class="badge badge-ghost badge-xs font-medium">{{ floor($sisaHari) }} hari</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const chartTrenEl = document.getElementById('chartTren');
    if (chartTrenEl) {
        const data = @json($chartTren['data']);
        new Chart(chartTrenEl, {
            type: 'line',
            data: {
                labels: @json($chartTren['labels']),
                datasets: [{
                    label: 'Nilai',
                    data: data,
                    borderColor: 'rgba(79, 70, 229, 0.9)',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: data.map(v => v >= 75 ? 'rgba(34,197,94,0.9)' : 'rgba(239,68,68,0.9)'),
                    pointBorderColor: 'white',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    tension: 0.35,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` Nilai: ${ctx.parsed.y}`,
                            afterLabel: ctx => ctx.parsed.y >= 75 ? '✓ Lulus' : '✗ Tidak Lulus'
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 9 }, color: 'rgba(107,114,128,0.7)', maxRotation: 30 }
                    },
                    y: {
                        min: 0,
                        max: 100,
                        ticks: { stepSize: 25, font: { size: 9 }, color: 'rgba(107,114,128,0.7)' },
                        grid: { color: 'rgba(107,114,128,0.08)' }
                    }
                }
            }
        });
    }
</script>
@endpush
</x-app-layout>