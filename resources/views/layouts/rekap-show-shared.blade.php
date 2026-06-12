{{--
    Dipakai oleh KEDUA view: admin.rekap.show dan guru.rekap.show
    Bisa share file ini sebagai komponen, atau copy ke masing-masing folder.
    Variable $isAdmin menentukan apakah tombol/kolom khusus admin ditampilkan.
--}}
<x-app-layout>
<div class="space-y-5">

    {{-- ═══════════════════════════════════════════════
         HEADER + TOMBOL KEMBALI
    ════════════════════════════════════════════════ --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-base-content">Detail Rekap Ujian</h1>
            <p class="text-sm text-base-content/50 mt-0.5 truncate max-w-md">{{ $paket->nama }}</p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            {{-- Tombol Export (placeholder — hubungkan ke route export jika sudah ada) --}}
            <button class="btn btn-ghost bg-base-200 hover:bg-base-300 btn-sm gap-2 font-medium"
                onclick="window.print()" title="Cetak / Export PDF">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                </svg>
                Cetak
            </button>
            <a href="{{ isset($isAdmin) && $isAdmin ? route('admin.rekap.index') : route('guru.rekap.index') }}"
                class="btn btn-ghost bg-base-200 hover:bg-base-300 btn-sm gap-2 font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2.5" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════
         INFO PAKET + STATISTIK UTAMA (2 baris card)
    ════════════════════════════════════════════════ --}}

    {{-- Baris 1: Info dasar paket ujian --}}
    <div class="grid grid-cols-1 md:grid-cols-3 bg-base-100 border border-base-200 shadow-sm rounded-xl
                divide-y md:divide-y-0 md:divide-x divide-base-200 overflow-hidden">

        {{-- Detail paket --}}
        <div class="p-5 flex items-start gap-4">
            <div class="p-2.5 bg-primary/10 text-primary rounded-xl shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" />
                </svg>
            </div>
            <div class="min-w-0">
                <span class="text-[10px] font-bold text-base-content/40 uppercase tracking-wider">Paket Ujian</span>
                <h3 class="text-sm font-bold text-base-content mt-0.5 leading-tight">{{ $paket->nama }}</h3>
                <p class="text-xs font-semibold text-primary mt-1">{{ $paket->mataPelajaran->nama ?? '-' }}</p>
                @if(isset($isAdmin) && $isAdmin)
                    <p class="text-xs text-base-content/50 mt-0.5">Guru: {{ $paket->guru->name ?? '-' }}</p>
                @endif
            </div>
        </div>

        {{-- Jadwal --}}
        <div class="p-5 flex items-start gap-4">
            <div class="p-2.5 bg-info/10 text-info rounded-xl shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
            </div>
            <div>
                <span class="text-[10px] font-bold text-base-content/40 uppercase tracking-wider">Jadwal & Durasi</span>
                <div class="mt-1.5 space-y-1 text-xs text-base-content/70">
                    <p>Mulai: <span class="text-base-content font-bold">{{ \Carbon\Carbon::parse($paket->tanggal_mulai)->format('d M Y') }}</span></p>
                    <p>Selesai: <span class="text-base-content font-bold">{{ \Carbon\Carbon::parse($paket->tanggal_selesai)->format('d M Y') }}</span></p>
                    <p class="mt-1 text-base-content/50">{{ $paket->durasi }} menit · {{ $paket->soal->count() ?? 0 }} soal</p>
                </div>
            </div>
        </div>

        {{-- Target kelas --}}
        <div class="p-5 flex items-start gap-4">
            <div class="p-2.5 bg-warning/10 text-warning rounded-xl shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                </svg>
            </div>
            <div>
                <span class="text-[10px] font-bold text-base-content/40 uppercase tracking-wider">Target Kelas</span>
                <div class="flex flex-wrap gap-1 mt-1.5">
                    @forelse($paket->kelas as $kelas)
                        <span class="badge badge-neutral badge-sm font-medium">{{ $kelas->nama }}</span>
                    @empty
                        <span class="text-xs text-base-content/40 italic">Semua kelas</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Baris 2: Statistik utama (6 tile) --}}
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3">

        @php
        $tiles = [
            ['label' => 'Total Peserta',   'value' => $statistik['total_peserta'],   'color' => 'text-base-content', 'bg' => 'bg-base-200/50'],
            ['label' => 'Lulus',            'value' => $statistik['total_lulus'],      'color' => 'text-success',      'bg' => 'bg-success/5'],
            ['label' => 'Tidak Lulus',      'value' => $statistik['total_tidak_lulus'],'color' => 'text-error',        'bg' => 'bg-error/5'],
            ['label' => 'Rata-rata',        'value' => $statistik['rata_rata'],        'color' => 'text-primary',      'bg' => 'bg-primary/5'],
            ['label' => 'Nilai Tertinggi',  'value' => $statistik['nilai_tertinggi'],  'color' => 'text-success',      'bg' => 'bg-success/5'],
            ['label' => 'Nilai Terendah',   'value' => $statistik['nilai_terendah'],   'color' => 'text-error',        'bg' => 'bg-error/5'],
        ];
        @endphp

        @foreach($tiles as $tile)
        <div class="card bg-base-100 border border-base-200 shadow-sm {{ $tile['bg'] }}">
            <div class="card-body p-4 items-center text-center">
                <span class="text-[10px] font-bold text-base-content/40 uppercase tracking-wider">{{ $tile['label'] }}</span>
                <span class="text-3xl font-bold {{ $tile['color'] }} mt-1">{{ $tile['value'] }}</span>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Kelulusan progress bar --}}
    <div class="card bg-base-100 border border-base-200 shadow-sm">
        <div class="card-body p-4 gap-2">
            <div class="flex justify-between items-center text-xs font-semibold">
                <span class="text-base-content/60">Tingkat Kelulusan</span>
                <span class="{{ $statistik['pct_lulus'] >= 75 ? 'text-success' : 'text-error' }} font-bold text-base">
                    {{ $statistik['pct_lulus'] }}%
                </span>
            </div>
            <progress class="progress {{ $statistik['pct_lulus'] >= 75 ? 'progress-success' : 'progress-error' }} w-full h-3"
                value="{{ $statistik['pct_lulus'] }}" max="100"></progress>
            <div class="flex justify-between text-xs text-base-content/40">
                <span>{{ $statistik['total_lulus'] }} lulus</span>
                <span>{{ $statistik['total_tidak_lulus'] }} tidak lulus</span>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════
         DISTRIBUSI NILAI + STATISTIK PER KELAS
    ════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">

        {{-- Distribusi Nilai --}}
        <div class="card bg-base-100 border border-base-200 shadow-sm">
            <div class="card-body p-5">
                <h3 class="text-sm font-bold text-base-content mb-4">Distribusi Nilai</h3>

                @php $maxCount = max($distribusi->values()->toArray() ?: [1]); @endphp

                <div class="space-y-2.5">
                    @foreach($distribusi as $rentang => $jumlah)
                    @php
                        $pct   = $maxCount > 0 ? round(($jumlah / $maxCount) * 100) : 0;
                        $isLulus = (int) explode('-', $rentang)[0] >= 75;
                        $barColor = $isLulus ? 'progress-success' : 'progress-error';
                        $textColor = $isLulus ? 'text-success' : 'text-error';
                    @endphp
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-mono font-bold text-base-content/50 w-14 shrink-0 text-right">{{ $rentang }}</span>
                        <div class="flex-1">
                            <progress class="progress {{ $barColor }} w-full h-2.5"
                                value="{{ $pct }}" max="100"></progress>
                        </div>
                        <span class="text-xs font-bold {{ $jumlah > 0 ? $textColor : 'text-base-content/30' }} w-6 text-right shrink-0">
                            {{ $jumlah }}
                        </span>
                    </div>
                    @endforeach
                </div>

                <div class="flex gap-3 mt-4 pt-4 border-t border-base-200 text-xs text-base-content/50">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-success inline-block"></span> Lulus (≥75)
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-error inline-block"></span> Tidak lulus (&lt;75)
                    </span>
                </div>
            </div>
        </div>

        {{-- Statistik Per Kelas --}}
        <div class="card bg-base-100 border border-base-200 shadow-sm">
            <div class="card-body p-5">
                <h3 class="text-sm font-bold text-base-content mb-4">Statistik per Kelas</h3>

                @if($perKelas->isNotEmpty())
                <div class="overflow-x-auto rounded-lg border border-base-200">
                    <table class="table table-sm w-full">
                        <thead class="bg-base-200/60">
                            <tr class="text-[10px] font-bold uppercase tracking-wide text-base-content/50">
                                <th>Kelas</th>
                                <th class="text-center">Peserta</th>
                                <th class="text-center">Rata-rata</th>
                                <th class="text-center">Tertinggi</th>
                                <th class="text-center">Lulus</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-base-200">
                            @foreach($perKelas as $kls)
                            @php $pctLulus = $kls['jumlah'] > 0 ? round(($kls['lulus'] / $kls['jumlah']) * 100) : 0; @endphp
                            <tr class="hover:bg-base-200/20">
                                <td class="font-bold text-base-content text-xs">{{ $kls['nama_kelas'] }}</td>
                                <td class="text-center text-xs text-base-content/60">{{ $kls['jumlah'] }}</td>
                                <td class="text-center">
                                    <span class="text-sm font-bold {{ $kls['rata_rata'] >= 75 ? 'text-success' : 'text-error' }}">
                                        {{ $kls['rata_rata'] }}
                                    </span>
                                </td>
                                <td class="text-center text-xs font-semibold text-success">{{ $kls['tertinggi'] }}</td>
                                <td class="text-center">
                                    <div class="flex flex-col items-center gap-0.5">
                                        <span class="text-xs font-bold text-base-content">{{ $kls['lulus'] }}/{{ $kls['jumlah'] }}</span>
                                        <span class="text-[10px] {{ $pctLulus >= 75 ? 'text-success' : 'text-error' }}">{{ $pctLulus }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <div class="flex flex-col items-center justify-center py-10 text-center">
                        <p class="text-sm text-base-content/40">Belum ada data per kelas.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════
         TABEL RANKING + FILTER KELAS
    ════════════════════════════════════════════════ --}}
    <div class="card bg-base-100 border border-base-200 shadow-sm">
        <div class="card-body p-0">

            {{-- Header tabel + filter kelas --}}
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 px-5 py-4 border-b border-base-200">
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-base-content">Peringkat Siswa</h3>
                    <p class="text-xs text-base-content/40 mt-0.5">{{ $ranking->count() }} peserta · diurutkan nilai tertinggi</p>
                </div>
                <form method="GET" class="flex items-center gap-2">
                    <select name="kelas" onchange="this.form.submit()"
                        class="select select-sm select-bordered focus:select-primary">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasOptions as $kls)
                            <option value="{{ $kls->id }}" {{ request('kelas') == $kls->id ? 'selected' : '' }}>
                                {{ $kls->nama }}
                            </option>
                        @endforeach
                    </select>
                    @if(request('kelas'))
                        <a href="{{ request()->url() }}" class="btn btn-sm btn-ghost bg-base-200 px-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="table table-sm w-full">
                    <thead class="bg-base-200/60">
                        <tr class="text-[10px] font-bold uppercase tracking-wide text-base-content/50">
                            <th class="w-14 text-center">Rank</th>
                            <th>Siswa</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Nilai</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Durasi</th>
                            <th class="text-center">Selesai</th>
                            <th class="text-center">Pelanggaran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-base-200">
                        @forelse($ranking as $sesi)
                        <tr class="hover:bg-base-200/20 align-middle transition-colors
                            {{ $sesi->ranking <= 3 ? 'bg-warning/3' : '' }}">

                            {{-- Rank dengan medali top 3 --}}
                            <td class="text-center">
                                @if($sesi->ranking === 1)
                                    <div class="flex items-center justify-center">
                                        <span class="text-lg" title="Peringkat 1">🥇</span>
                                    </div>
                                @elseif($sesi->ranking === 2)
                                    <div class="flex items-center justify-center">
                                        <span class="text-lg" title="Peringkat 2">🥈</span>
                                    </div>
                                @elseif($sesi->ranking === 3)
                                    <div class="flex items-center justify-center">
                                        <span class="text-lg" title="Peringkat 3">🥉</span>
                                    </div>
                                @else
                                    <span class="text-sm font-bold text-base-content/40">{{ $sesi->ranking }}</span>
                                @endif
                            </td>

                            {{-- Siswa --}}
                            <td>
                                <div class="flex items-center gap-2.5">
                                    <div class="avatar placeholder shrink-0">
                                        <div class="bg-base-200 text-base-content/60 rounded-full w-8 text-xs font-bold uppercase">
                                            {{ substr($sesi->siswa->name ?? 'U', 0, 2) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-base-content">{{ $sesi->siswa->name ?? '-' }}</div>
                                        <div class="text-xs text-base-content/40">
                                            {{ $sesi->siswa->profileSiswa->nis ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="text-center">
                                <span class="badge badge-neutral badge-sm font-medium">
                                    {{ $sesi->siswa->profileSiswa?->kelas?->nama ?? '-' }}
                                </span>
                            </td>

                            {{-- Nilai --}}
                            <td class="text-center">
                                <div class="flex flex-col items-center gap-0.5">
                                    <span class="text-base font-bold {{ $sesi->lulus ? 'text-success' : 'text-error' }}">
                                        {{ $sesi->nilai ?? '-' }}
                                    </span>
                                    <progress class="progress w-10 h-1 {{ $sesi->lulus ? 'progress-success' : 'progress-error' }}"
                                        value="{{ $sesi->nilai ?? 0 }}" max="100"></progress>
                                </div>
                            </td>

                            {{-- Status Lulus --}}
                            <td class="text-center">
                                @if($sesi->lulus)
                                    <span class="badge badge-success badge-sm font-semibold">Lulus</span>
                                @else
                                    <span class="badge badge-error badge-sm font-semibold">Tidak Lulus</span>
                                @endif
                                @if($sesi->status === 'timeout')
                                    <div class="badge badge-ghost badge-xs mt-0.5 block">Timeout</div>
                                @endif
                            </td>

                            {{-- Durasi --}}
                            <td class="text-center text-xs text-base-content/60">
                                {{ $sesi->durasi_menit ? $sesi->durasi_menit . ' mnt' : '—' }}
                            </td>

                            {{-- Waktu selesai --}}
                            <td class="text-center text-xs text-base-content/50">
                                {{ $sesi->waktu_selesai ? \Carbon\Carbon::parse($sesi->waktu_selesai)->format('d M · H:i') : '—' }}
                            </td>

                            {{-- Pelanggaran --}}
                            <td class="text-center">
                                @if(($sesi->jumlah_pelanggaran ?? 0) > 0)
                                    <span class="badge badge-error badge-outline badge-sm font-bold">
                                        {{ $sesi->jumlah_pelanggaran }}x
                                    </span>
                                @else
                                    <span class="text-base-content/25 text-xs">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-sm text-base-content/40">
                                Belum ada peserta yang menyelesaikan ujian ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ═══════════════════════════════════════════════
                 SISWA YANG BELUM IKUT
            ════════════════════════════════════════════════ --}}
            @if($belumIkut->isNotEmpty())
            <div class="border-t border-base-200">
                <div class="px-5 py-3 bg-warning/5 border-b border-warning/10 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" class="size-4 text-warning shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                    <p class="text-xs font-bold text-warning">
                        {{ $belumIkut->count() }} siswa belum mengikuti ujian ini
                    </p>
                </div>
                <div class="p-4">
                    <div class="flex flex-wrap gap-2">
                        @foreach($belumIkut as $siswa)
                            <div class="flex items-center gap-1.5 px-2.5 py-1.5 bg-base-200/50 border border-base-300 rounded-lg">
                                <div class="w-5 h-5 bg-warning/20 text-warning rounded-full flex items-center justify-center text-[9px] font-bold uppercase">
                                    {{ substr($siswa->name, 0, 1) }}
                                </div>
                                <span class="text-xs font-medium text-base-content/70">{{ $siswa->name }}</span>
                                <span class="text-[10px] text-base-content/40">
                                    · {{ $siswa->profileSiswa?->kelas?->nama ?? '-' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

</div>
</x-app-layout>