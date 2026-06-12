<x-app-layout>
    <div class="space-y-6">

        {{-- ═══════════════════════════════════════════
        HEADER
        ════════════════════════════════════════════ --}}
        <div>
            <h1 class="text-xl font-bold tracking-tight text-base-content">Dashboard Guru</h1>
            <p class="text-sm text-base-content/50 mt-0.5">
                Selamat datang, <span class="text-primary font-semibold">{{ Auth::user()->name }}</span> —
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </p>
        </div>

        {{-- ═══════════════════════════════════════════
        STAT CARDS (5 tile)
        ════════════════════════════════════════════ --}}
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4">
            @php
                $stats = [
                    ['label' => 'Total Soal', 'value' => $totalSoal, 'color' => 'bg-primary/10 text-primary', 'path' => 'M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z'],
                    ['label' => 'Total Paket', 'value' => $totalPaket, 'color' => 'bg-secondary/10 text-secondary', 'path' => 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z'],
                    ['label' => 'Paket Aktif', 'value' => $paketAktif, 'color' => 'bg-success/10 text-success', 'path' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
                    ['label' => 'Total Siswa Ikut', 'value' => $totalSiswaIkut, 'color' => 'bg-info/10 text-info', 'path' => 'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z'],
                    ['label' => 'Sedang Mengerjakan', 'value' => $siswaSedangUjian, 'color' => 'bg-warning/10 text-warning', 'path' => 'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
                ];
            @endphp
            @foreach($stats as $s)
                <div class="card bg-base-100 border border-base-200 shadow-sm">
                    <div class="card-body p-4 items-center text-center gap-2">
                        <div class="p-2.5 rounded-xl {{ $s['color'] }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $s['path'] }}" />
                            </svg>
                        </div>
                        <div class="text-2xl font-bold text-base-content">{{ $s['value'] }}</div>
                        <div class="text-[10px] font-bold uppercase tracking-wider text-base-content/40">{{ $s['label'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ═══════════════════════════════════════════
        PAKET UJIAN AKTIF
        ════════════════════════════════════════════ --}}
        @if($listPaketAktif->isNotEmpty())
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-bold text-base-content">Paket Ujian Aktif</h2>
                    <a href="{{ route('guru.paket-ujian.index') }}"
                        class="text-xs text-primary font-semibold hover:underline">Kelola semua →</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    @foreach($listPaketAktif as $paket)
                        @php
                            $pct = $paket->total_peserta > 0
                                ? round(($paket->sudah_selesai / $paket->total_peserta) * 100)
                                : 0;
                            $sisaHari = now()->diffInDays(\Carbon\Carbon::parse($paket->tanggal_selesai), false);
                        @endphp
                        <div
                            class="card bg-base-100 border border-base-200 shadow-sm hover:shadow-md hover:border-primary/20 transition-all duration-200">
                            <div class="card-body p-5 gap-3">

                                {{-- Header --}}
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-bold text-base-content leading-tight truncate">
                                            {{ $paket->nama }}</h3>
                                        <p class="text-xs font-semibold text-primary mt-0.5">
                                            {{ $paket->mataPelajaran->nama ?? '-' }}</p>
                                    </div>
                                    @if($sisaHari <= 1)
                                        <span class="badge badge-error badge-xs font-bold shrink-0">Segera Tutup</span>
                                    @elseif($sisaHari <= 3)
                                        <span class="badge badge-warning badge-xs font-bold shrink-0">{{ $sisaHari }}h lagi</span>
                                    @endif
                                </div>

                                {{-- Live indicator --}}
                                @if($paket->sedang_mengerjakan > 0)
                                    <div class="flex items-center gap-1.5 text-xs font-semibold text-info">
                                        <span class="relative flex h-1.5 w-1.5 shrink-0">
                                            <span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-info opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-info"></span>
                                        </span>
                                        {{ $paket->sedang_mengerjakan }} siswa sedang mengerjakan
                                    </div>
                                @endif

                                {{-- Stats row --}}
                                <div class="grid grid-cols-3 gap-1 text-center py-2 border-y border-base-200">
                                    <div>
                                        <div class="text-sm font-bold text-base-content">{{ $paket->total_peserta }}</div>
                                        <div class="text-[10px] text-base-content/40 uppercase tracking-wide">Peserta</div>
                                    </div>
                                    <div class="border-x border-base-200">
                                        <div class="text-sm font-bold text-success">{{ $paket->sudah_selesai }}</div>
                                        <div class="text-[10px] text-base-content/40 uppercase tracking-wide">Selesai</div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-base-content">{{ $paket->jumlah_soal }}</div>
                                        <div class="text-[10px] text-base-content/40 uppercase tracking-wide">Soal</div>
                                    </div>
                                </div>

                                {{-- Progress --}}
                                <div class="space-y-1">
                                    <div class="flex justify-between text-[10px] text-base-content/50">
                                        <span>Progress peserta</span>
                                        <span class="font-bold">{{ $pct }}%</span>
                                    </div>
                                    <progress class="progress progress-primary w-full h-1.5" value="{{ $pct }}"
                                        max="100"></progress>
                                </div>

                                {{-- Deadline + tombol --}}
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-[10px] text-base-content/40">
                                        Tutup {{ \Carbon\Carbon::parse($paket->tanggal_selesai)->format('d M Y') }}
                                    </span>
                                    <div class="flex gap-1.5">
                                        <a href="{{ route('guru.paket-ujian.show', $paket) }}"
                                            class="btn btn-xs btn-ghost bg-base-200 font-medium">Soal</a>
                                        <a href="{{ route('guru.rekap.show', $paket) }}"
                                            class="btn btn-xs btn-primary font-medium">Rekap</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════
        BARIS BAWAH: Hasil Terbaru + Draft Reminder
        ════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

            {{-- Hasil ujian terbaru (2/3) --}}
            <div class="xl:col-span-2 card bg-base-100 border border-base-200 shadow-sm">
                <div class="card-body p-0">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-base-200">
                        <div>
                            <h3 class="text-sm font-bold text-base-content">Hasil Ujian Terbaru</h3>
                            <p class="text-xs text-base-content/40 mt-0.5">5 paket yang baru selesai</p>
                        </div>
                        <a href="{{ route('guru.rekap.index') }}"
                            class="btn btn-xs btn-ghost text-primary font-semibold">Lihat semua →</a>
                    </div>

                    @if($hasilTerbaru->isEmpty())
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
                                    @foreach($hasilTerbaru as $paket)
                                        @php $rata = round($paket->rata_nilai ?? 0, 1); @endphp
                                        <tr class="hover:bg-base-200/20 align-middle">
                                            <td>
                                                <div class="text-sm font-bold text-base-content truncate max-w-44">
                                                    {{ $paket->nama }}</div>
                                                <div class="text-xs text-primary font-medium">
                                                    {{ $paket->mataPelajaran->nama ?? '-' }}</div>
                                            </td>
                                            <td class="text-center text-sm text-base-content/70">{{ $paket->jumlah_peserta }}
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="font-bold text-sm {{ $rata >= 75 ? 'text-success' : 'text-error' }}">{{ $rata }}</span>
                                            </td>
                                            <td class="text-center text-sm font-semibold text-success">
                                                {{ $paket->nilai_tertinggi ?? '—' }}</td>
                                            <td class="text-center text-sm font-semibold text-error">
                                                {{ $paket->nilai_terendah ?? '—' }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('guru.rekap.show', $paket) }}"
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

            {{-- Draft reminder (1/3) --}}
            <div class="card bg-base-100 border border-base-200 shadow-sm">
                <div class="card-body p-0">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-base-200">
                        <div>
                            <h3 class="text-sm font-bold text-base-content">Paket Draft</h3>
                            <p class="text-xs text-base-content/40 mt-0.5">Belum dipublikasikan</p>
                        </div>
                        @if($draftPaket->isNotEmpty())
                            <span class="badge badge-warning badge-sm font-bold">{{ $draftPaket->count() }}</span>
                        @endif
                    </div>

                    @if($draftPaket->isEmpty())
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <div class="text-2xl mb-2">📋</div>
                            <p class="text-sm font-medium text-base-content/50">Tidak ada draft paket</p>
                        </div>
                    @else
                        <div class="divide-y divide-base-200 max-h-80 overflow-y-auto">
                            @foreach($draftPaket as $draft)
                                <div class="px-5 py-3.5 hover:bg-base-200/20 transition-colors">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-bold text-base-content truncate">{{ $draft->nama }}</div>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span
                                                    class="text-xs text-primary font-medium">{{ $draft->mataPelajaran->nama ?? '-' }}</span>
                                                <span class="text-[10px] text-base-content/40">· {{ $draft->jumlah_soal }}
                                                    soal</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('guru.paket-ujian.show', $draft) }}"
                                            class="btn btn-xs btn-ghost text-warning font-semibold shrink-0">Edit</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="px-5 py-3 border-t border-base-200">
                            <a href="{{ route('guru.paket-ujian.index') }}"
                                class="btn btn-sm btn-warning w-full font-semibold gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                Publikasikan Sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</x-app-layout>