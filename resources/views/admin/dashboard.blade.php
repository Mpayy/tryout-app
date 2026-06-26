<x-app-layout>
    <div class="space-y-6">

        {{-- ═══════════════════════════════════════════
        HEADER
        ════════════════════════════════════════════ --}}
        <div>
            <h1 class="text-xl font-bold tracking-tight text-base-content">Dashboard Admin</h1>
            <p class="text-sm text-base-content/50 mt-0.5">
                Selamat datang, <span class="text-primary font-semibold">{{ Auth::user()->name }}</span> —
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </p>
        </div>

        {{-- ═══════════════════════════════════════════
        STAT CARDS — baris 1 (4 kolom)
        ════════════════════════════════════════════ --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            @php
                $stats = [
                    [
                        'label' => 'Total Siswa',
                        'value' => $totalSiswa,
                        'color' => 'bg-primary/10 text-primary',
                        'icon' => 'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z',
                    ],
                    [
                        'label' => 'Total Guru',
                        'value' => $totalGuru,
                        'color' => 'bg-secondary/10 text-secondary',
                        'icon' => 'M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5',
                    ],
                    [
                        'label' => 'Total Soal',
                        'value' => $totalSoal,
                        'color' => 'bg-accent/10 text-accent',
                        'icon' => 'M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z',
                    ],
                    [
                        'label' => 'Total Paket Ujian',
                        'value' => $totalPaket,
                        'color' => 'bg-warning/10 text-warning',
                        'icon' => 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z',
                    ],
                ];
            @endphp

            @foreach($stats as $s)
                <div class="card bg-base-100 border border-base-200 shadow-sm">
                    <div class="card-body p-5 flex flex-row items-center gap-4">
                        <div class="p-3 rounded-xl {{ $s['color'] }} shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $s['icon'] }}" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-base-content">{{ number_format($s['value']) }}</div>
                            <div class="text-xs text-base-content/50 font-medium mt-0.5">{{ $s['label'] }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- STAT — baris 2: Live stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- Ujian aktif hari ini --}}
            <div class="card bg-success/10 border border-success/20 shadow-sm">
                <div class="card-body p-5 flex flex-row items-center gap-4">
                    <div class="p-3 bg-success/20 text-success rounded-xl shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-success">{{ $ujianAktifHariIni }}</div>
                        <div class="text-xs text-success/70 font-medium mt-0.5">Ujian Aktif Sekarang</div>
                    </div>
                </div>
            </div>

            {{-- Siswa sedang mengerjakan --}}
            <div class="card bg-info/10 border border-info/20 shadow-sm">
                <div class="card-body p-5 flex flex-row items-center gap-4">
                    <div class="p-3 bg-info/20 text-info rounded-xl shrink-0 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                        @if($siswaSedangUjian > 0)
                            <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-info opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-info"></span>
                            </span>
                        @endif
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-info">{{ $siswaSedangUjian ?? 0 }}</div>
                        <div class="text-xs text-info/70 font-medium mt-0.5">Siswa Sedang Mengerjakan</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════
        BARIS TENGAH: Monitoring + Chart
        ════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

            {{-- Monitoring real-time (2/3) --}}
            <div class="xl:col-span-2 card bg-base-100 border border-base-200 shadow-sm">
                <div class="card-body p-0">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-base-200">
                        <div>
                            <h3 class="text-sm font-bold text-base-content">Monitoring Ujian Berlangsung</h3>
                            <p class="text-xs text-base-content/40 mt-0.5">Paket ujian yang aktif saat ini</p>
                        </div>
                        @if($siswaSedangUjian > 0)
                            <span class="badge badge-success badge-sm gap-1.5 font-semibold">
                                <span class="relative flex h-1.5 w-1.5">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-current opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-current"></span>
                                </span>
                                Live
                            </span>
                        @endif
                    </div>

                    @if($monitoringUjian->isEmpty())
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <div
                                class="w-12 h-12 bg-base-200 rounded-full flex items-center justify-center mb-3 text-base-content/30">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-base-content/50">Tidak ada ujian aktif saat ini</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="table table-sm w-full">
                                <thead class="bg-base-200/60">
                                    <tr class="text-[10px] font-bold uppercase tracking-wide text-base-content/50">
                                        <th>Nama Paket</th>
                                        <th class="text-center">Sedang</th>
                                        <th class="text-center">Selesai</th>
                                        <th class="text-center">Total</th>
                                        <th>Progress</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-base-200">
                                    @foreach($monitoringUjian as $paket)
                                        @php
                                            $pct = $paket->total_peserta > 0
                                                ? round(($paket->sudah_submit / $paket->total_peserta) * 100)
                                                : 0;
                                        @endphp
                                        <tr class="hover:bg-base-200/20 align-middle">
                                            <td>
                                                <div class="text-sm font-bold text-base-content">{{ $paket->nama }}</div>
                                                <div class="text-xs text-primary font-medium">
                                                    {{ $paket->mataPelajaran->nama ?? '-' }}</div>
                                            </td>
                                            <td class="text-center">
                                                @if($paket->sedang_mengerjakan > 0)
                                                    <span
                                                        class="badge badge-info badge-sm font-bold">{{ $paket->sedang_mengerjakan }}</span>
                                                @else
                                                    <span class="text-base-content/25 text-xs">—</span>
                                                @endif
                                            </td>
                                            <td class="text-center text-sm font-bold text-success">{{ $paket->sudah_submit }}
                                            </td>
                                            <td class="text-center text-sm text-base-content/60">{{ $paket->total_peserta }}
                                            </td>
                                            <td class="min-w-28">
                                                <div class="flex items-center gap-2">
                                                    <progress class="progress progress-primary w-full h-1.5" value="{{ $pct }}"
                                                        max="100"></progress>
                                                    <span
                                                        class="text-[10px] font-bold text-base-content/50 w-7 shrink-0">{{ $pct }}%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Chart partisipasi 8 minggu (1/3) --}}
            <div class="card bg-base-100 border border-base-200 shadow-sm">
                <div class="card-body p-5">
                    <h3 class="text-sm font-bold text-base-content mb-1">Partisipasi Ujian</h3>
                    <p class="text-xs text-base-content/40 mb-4">8 minggu terakhir</p>
                    <div class="relative h-48">
                        <canvas id="chartPartisipasi"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════
        BARIS BAWAH: Ujian Terbaru + Siswa Belum Profil
        ════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

            {{-- Ujian terbaru (2/3) --}}
            <div class="xl:col-span-3 card bg-base-100 border border-base-200 shadow-sm">
                <div class="card-body p-0">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-base-200">
                        <div>
                            <h3 class="text-sm font-bold text-base-content">Ujian Selesai Terbaru</h3>
                            <p class="text-xs text-base-content/40 mt-0.5">5 paket dengan hasil terbaru</p>
                        </div>
                        <a href="{{ route('admin.rekap.index') }}"
                            class="btn btn-xs btn-ghost text-primary font-semibold">
                            Lihat semua →
                        </a>
                    </div>

                    @if($ujianTerbaru->isEmpty())
                        <div class="py-12 text-center text-sm text-base-content/40">Belum ada data hasil ujian.</div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="table table-sm w-full">
                                <thead class="bg-base-200/60">
                                    <tr class="text-[10px] font-bold uppercase tracking-wide text-base-content/50">
                                        <th>Paket Ujian</th>
                                        <th class="text-center">Peserta</th>
                                        <th class="text-center">Rata-rata</th>
                                        <th class="text-center">Tertinggi</th>
                                        <th class="text-center">Terendah</th>
                                        <th class="text-center w-16">Detail</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-base-200">
                                    @foreach($ujianTerbaru as $paket)
                                        @php $rata = round($paket->rata_nilai ?? 0, 1); @endphp
                                        <tr class="hover:bg-base-200/20 align-middle">
                                            <td>
                                                <div class="text-sm font-bold text-base-content truncate max-w-48">
                                                    {{ $paket->nama }}</div>
                                                <div class="text-xs text-primary font-medium">
                                                    {{ $paket->mataPelajaran->nama ?? '-' }}</div>
                                            </td>
                                            <td class="text-center text-sm text-base-content/70">{{ $paket->jumlah_peserta }}
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="text-sm font-bold {{ $rata >= 75 ? 'text-success' : 'text-error' }}">
                                                    {{ $rata }}
                                                </span>
                                            </td>
                                            <td class="text-center text-sm font-semibold text-success">
                                                {{ $paket->nilai_tertinggi ?? '—' }}</td>
                                            <td class="text-center text-sm font-semibold text-error">
                                                {{ $paket->nilai_terendah ?? '—' }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.rekap.show', $paket) }}"
                                                    class="btn btn-xs btn-primary font-medium">Rekap</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Siswa belum profil (1/3) --}}
            {{-- <div class="card bg-base-100 border border-base-200 shadow-sm">
                <div class="card-body p-0">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-base-200">
                        <div>
                            <h3 class="text-sm font-bold text-base-content">Profil Belum Lengkap</h3>
                            <p class="text-xs text-base-content/40 mt-0.5">Siswa tanpa data profil</p>
                        </div>
                        @if($siswaBelumLengkap->isNotEmpty())
                        <span class="badge badge-warning badge-sm font-bold">{{ $siswaBelumLengkap->count() }}</span>
                        @endif
                    </div>

                    @if($siswaBelumLengkap->isEmpty())
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="text-2xl mb-2">✅</div>
                        <p class="text-sm font-medium text-base-content/50">Semua profil sudah lengkap</p>
                    </div>
                    @else
                    <div class="divide-y divide-base-200 max-h-72 overflow-y-auto">
                        @foreach($siswaBelumLengkap as $siswa)
                        <div class="flex items-center gap-3 px-5 py-3 hover:bg-base-200/30 transition-colors">
                            <div class="avatar placeholder shrink-0">
                                <div class="bg-warning/20 text-warning rounded-full w-8 text-xs font-bold uppercase">
                                    {{ substr($siswa->name, 0, 2) }}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-bold text-base-content truncate">{{ $siswa->name }}</div>
                                <div class="text-xs text-base-content/40 truncate">{{ $siswa->email }}</div>
                            </div>
                            <a href="{{ route('admin.siswa.index') }}"
                                class="btn btn-xs btn-ghost text-warning font-semibold shrink-0">Edit</a>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div> --}}
        </div>

    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            const chartEl = document.getElementById('chartPartisipasi');
            if (chartEl) {
                const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
                const textColor = getComputedStyle(document.documentElement)
                    .getPropertyValue('--bc') || '#6b7280';

                new Chart(chartEl, {
                    type: 'bar',
                    data: {
                        labels: @json($chartData['labels']),
                        datasets: [{
                            label: 'Sesi Ujian',
                            data: @json($chartData['data']),
                            backgroundColor: 'rgba(79, 70, 229, 0.2)',
                            borderColor: 'rgba(79, 70, 229, 0.8)',
                            borderWidth: 2,
                            borderRadius: 6,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: ctx => ` ${ctx.parsed.y} sesi`
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 10 }, color: 'rgba(107,114,128,0.8)' }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    font: { size: 10 },
                                    color: 'rgba(107,114,128,0.8)'
                                },
                                grid: { color: 'rgba(107,114,128,0.1)' }
                            }
                        }
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>