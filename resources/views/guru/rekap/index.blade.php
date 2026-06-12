<x-app-layout>
    <div class="space-y-6">

        {{-- ═══════════════════════════════════════════════
        HEADER
        ════════════════════════════════════════════════ --}}
        <div>
            <h1 class="text-xl font-bold tracking-tight text-base-content">Rekap Hasil Ujian</h1>
            <p class="text-sm text-base-content/50 mt-0.5">Pantau hasil paket ujian yang Anda buat.</p>
        </div>

        {{-- ═══════════════════════════════════════════════
        FILTER BAR (lebih simpel dari admin)
        ════════════════════════════════════════════════ --}}
        <div class="card bg-base-100 border border-base-200 shadow-sm">
            <div class="card-body p-4">
                {{-- <div>
                    <h1 class="text-xl font-bold tracking-tight text-base-content">Rekap Hasil Ujian</h1>
                    <p class="text-sm text-base-content/50 mt-0.5">Pantau hasil paket ujian yang Anda buat.</p>
                </div> --}}
                <form method="GET" action="{{ route('guru.rekap.index') }}"
                    class="flex flex-col sm:flex-row gap-3 items-end">

                    {{-- Search --}}
                    <div class="flex-1 w-full">
                        <label class="label py-0 pb-1">
                            <span class="label-text text-xs font-semibold text-base-content/60">Cari Nama Paket</span>
                        </label>
                        <div class="join w-full">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Ketik nama paket ujian..."
                                class="input input-sm input-bordered join-item w-full focus:input-primary" />
                            <button type="submit" class="btn btn-sm btn-primary join-item px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Filter Status --}}
                    <div class="w-full sm:w-48">
                        <label class="label py-0 pb-1">
                            <span class="label-text text-xs font-semibold text-base-content/60">Status Paket</span>
                        </label>
                        <div class="flex gap-2">
                            <select name="status" onchange="this.form.submit()"
                                class="select select-sm select-bordered w-full focus:select-primary">
                                <option value="">Semua Status</option>
                                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                            @if(request()->hasAny(['search', 'status']))
                                <a href="{{ route('guru.rekap.index') }}"
                                    class="btn btn-sm btn-ghost bg-base-200 hover:bg-base-300 px-3 shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════
        CARD GRID PAKET
        (guru pakai card grid, lebih personal vs admin yg pakai tabel)
        ════════════════════════════════════════════════ --}}
        @if($pakets->isEmpty())
            <div class="card bg-base-100 border border-base-200 shadow-sm">
                <div class="card-body items-center text-center py-16">
                    <div
                        class="w-14 h-14 bg-base-200 border border-base-300 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-base-content/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5" />
                        </svg>
                    </div>
                    <p class="font-bold text-base-content/60">Belum ada rekap tersedia</p>
                    <p class="text-xs text-base-content/40 mt-0.5">
                        {{ request()->hasAny(['search', 'status']) ? 'Coba ubah filter pencarian.' : 'Rekap muncul setelah ada siswa yang menyelesaikan ujian.' }}
                    </p>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($pakets as $paket)
                    @php $rata = $paket->rata_nilai ? round($paket->rata_nilai, 1) : null; @endphp

                    <div
                        class="card bg-base-100 border border-base-200 shadow-sm hover:shadow-md hover:border-primary/30 transition-all duration-200">
                        <div class="card-body p-5">

                            {{-- Header card --}}
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-base-content text-sm leading-tight truncate">
                                        {{ $paket->nama }}
                                    </h3>
                                    <p class="text-xs font-semibold text-primary mt-0.5">
                                        {{ $paket->mataPelajaran->nama ?? '-' }}
                                    </p>
                                </div>
                                @if($paket->status === 'aktif')
                                    <span class="badge badge-success badge-sm font-semibold shrink-0 gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Aktif
                                    </span>
                                @elseif($paket->status === 'selesai')
                                    <span class="badge badge-neutral badge-sm font-semibold shrink-0">Selesai</span>
                                @else
                                    <span class="badge badge-warning badge-sm font-semibold shrink-0">Draft</span>
                                @endif
                            </div>

                            {{-- Stats grid --}}
                            <div class="grid grid-cols-3 gap-2 py-3 border-y border-base-200 mb-3">
                                <div class="text-center">
                                    <div class="text-lg font-bold text-base-content">{{ $paket->total_peserta }}</div>
                                    <div class="text-[10px] text-base-content/40 font-semibold uppercase tracking-wider mt-0.5">
                                        Peserta</div>
                                </div>
                                <div class="text-center border-x border-base-200">
                                    <div class="text-lg font-bold text-base-content">{{ $paket->total_selesai }}</div>
                                    <div class="text-[10px] text-base-content/40 font-semibold uppercase tracking-wider mt-0.5">
                                        Selesai</div>
                                </div>
                                <div class="text-center">
                                    @if($rata !== null)
                                        <div class="text-lg font-bold {{ $rata >= 75 ? 'text-success' : 'text-error' }}">{{ $rata }}
                                        </div>
                                    @else
                                        <div class="text-lg font-bold text-base-content/30">—</div>
                                    @endif
                                    <div class="text-[10px] text-base-content/40 font-semibold uppercase tracking-wider mt-0.5">
                                        Rata-rata</div>
                                </div>
                            </div>

                            {{-- Progress rata-rata --}}
                            @if($rata !== null)
                                <div class="mb-3">
                                    <progress
                                        class="progress w-full h-1.5 {{ $rata >= 75 ? 'progress-success' : 'progress-error' }}"
                                        value="{{ $rata }}" max="100"></progress>
                                </div>
                            @endif

                            {{-- Sedang berlangsung badge --}}
                            @if($paket->sedang_berlangsung > 0)
                                <div class="flex items-center gap-1.5 text-xs text-info font-semibold mb-3">
                                    <span class="relative flex h-2 w-2">
                                        <span
                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-info opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-info"></span>
                                    </span>
                                    {{ $paket->sedang_berlangsung }} siswa sedang mengerjakan
                                </div>
                            @endif

                            {{-- Periode + tombol --}}
                            <div class="flex items-center justify-between mt-auto">
                                <span class="text-xs text-base-content/40">
                                    {{ \Carbon\Carbon::parse($paket->tanggal_mulai)->format('d M') }} —
                                    {{ \Carbon\Carbon::parse($paket->tanggal_selesai)->format('d M Y') }}
                                </span>
                                <a href="{{ route('guru.rekap.show', $paket) }}"
                                    class="btn btn-xs btn-primary font-medium gap-1 shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" class="size-3">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5" />
                                    </svg>
                                    Lihat Rekap
                                </a>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($pakets->hasPages())
                <div>{{ $pakets->links() }}</div>
            @endif
        @endif

    </div>
</x-app-layout>