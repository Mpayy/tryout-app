<x-app-layout>
<div class="space-y-6">

    {{-- ═══════════════════════════════════════════════
         HEADER
    ════════════════════════════════════════════════ --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-base-content">Rekap Hasil Ujian</h1>
            <p class="text-sm text-base-content/50 mt-0.5">Pantau hasil ujian dari semua guru dan mata pelajaran.</p>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════
         FILTER BAR
    ════════════════════════════════════════════════ --}}
    <div class="card bg-base-100 border border-base-200 shadow-sm">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.rekap.index') }}"
                  class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-3 items-end">

                {{-- Search --}}
                <div class="xl:col-span-2">
                    <label class="label py-0 pb-1">
                        <span class="label-text text-xs font-semibold text-base-content/60">Cari Nama Paket</span>
                    </label>
                    <div class="join w-full">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Ketik nama paket ujian..."
                            class="input input-sm input-bordered join-item w-full focus:input-primary" />
                        <button type="submit" class="btn btn-sm btn-primary join-item px-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Filter Mata Pelajaran --}}
                <div>
                    <label class="label py-0 pb-1">
                        <span class="label-text text-xs font-semibold text-base-content/60">Mata Pelajaran</span>
                    </label>
                    <select name="mapel" class="select select-sm select-bordered w-full focus:select-primary">
                        <option value="">Semua Mapel</option>
                        @foreach($mapels as $mapel)
                            <option value="{{ $mapel->id }}" {{ request('mapel') == $mapel->id ? 'selected' : '' }}>
                                {{ $mapel->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Guru --}}
                <div>
                    <label class="label py-0 pb-1">
                        <span class="label-text text-xs font-semibold text-base-content/60">Guru</span>
                    </label>
                    <select name="guru" class="select select-sm select-bordered w-full focus:select-primary">
                        <option value="">Semua Guru</option>
                        @foreach($gurus as $guru)
                            <option value="{{ $guru->id }}" {{ request('guru') == $guru->id ? 'selected' : '' }}>
                                {{ $guru->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Status --}}
                <div>
                    <label class="label py-0 pb-1">
                        <span class="label-text text-xs font-semibold text-base-content/60">Status Paket</span>
                    </label>
                    <div class="flex gap-2">
                        <select name="status" class="select select-sm select-bordered w-full focus:select-primary">
                            <option value="">Semua Status</option>
                            <option value="aktif"   {{ request('status') == 'aktif'   ? 'selected' : '' }}>Aktif</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="draft"   {{ request('status') == 'draft'   ? 'selected' : '' }}>Draft</option>
                        </select>
                        @if(request()->hasAny(['search','mapel','guru','status']))
                            <a href="{{ route('admin.rekap.index') }}"
                                class="btn btn-sm btn-ghost bg-base-200 hover:bg-base-300 px-3 shrink-0"
                                title="Reset filter">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════
         TABEL DAFTAR PAKET
    ════════════════════════════════════════════════ --}}
    <div class="card bg-base-100 border border-base-200 shadow-sm">
        <div class="card-body p-0">

            {{-- Subheader tabel --}}
            <div class="flex items-center justify-between px-5 py-4 border-b border-base-200">
                <div>
                    <h2 class="text-sm font-bold text-base-content">Daftar Paket Ujian</h2>
                    <p class="text-xs text-base-content/40 mt-0.5">{{ $pakets->total() }} paket ditemukan</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table table-sm w-full">
                    <thead class="bg-base-200/60">
                        <tr class="text-xs font-bold uppercase tracking-wide text-base-content/60">
                            <th class="w-10 text-center">No</th>
                            <th>Paket Ujian</th>
                            <th>Mapel & Guru</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Peserta</th>
                            <th class="text-center">Rata-rata Nilai</th>
                            <th class="text-center">Periode</th>
                            <th class="text-center w-20">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-base-200">
                        @forelse($pakets as $paket)
                        <tr class="hover:bg-base-200/30 align-middle transition-colors">
                            <td class="text-center text-base-content/40 font-medium text-xs">
                                {{ ($pakets->currentPage() - 1) * $pakets->perPage() + $loop->iteration }}
                            </td>

                            <td>
                                <div class="font-bold text-base-content text-sm">{{ $paket->nama }}</div>
                                <div class="text-xs text-base-content/40 mt-0.5">
                                    {{ $paket->soal_count ?? 0 }} soal · {{ $paket->durasi }} menit
                                </div>
                            </td>

                            <td>
                                <div class="text-sm font-semibold text-primary">{{ $paket->mataPelajaran->nama ?? '-' }}</div>
                                <div class="text-xs text-base-content/50 mt-0.5">{{ $paket->guru->name ?? '-' }}</div>
                            </td>

                            <td class="text-center">
                                @if($paket->status === 'aktif')
                                    <span class="badge badge-success badge-sm font-semibold gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current inline-block"></span>Aktif
                                    </span>
                                @elseif($paket->status === 'selesai')
                                    <span class="badge badge-neutral badge-sm font-semibold gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current inline-block"></span>Selesai
                                    </span>
                                @else
                                    <span class="badge badge-warning badge-sm font-semibold gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current inline-block"></span>Draft
                                    </span>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="flex flex-col items-center gap-0.5">
                                    <span class="text-sm font-bold text-base-content">{{ $paket->total_peserta }}</span>
                                    <div class="flex gap-1">
                                        @if($paket->sedang_berlangsung > 0)
                                            <span class="badge badge-info badge-xs">{{ $paket->sedang_berlangsung }} aktif</span>
                                        @endif
                                        <span class="badge badge-ghost badge-xs">{{ $paket->total_selesai }} selesai</span>
                                    </div>
                                </div>
                            </td>

                            <td class="text-center">
                                @if($paket->rata_nilai !== null)
                                    @php $rata = round($paket->rata_nilai, 1); @endphp
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-lg font-bold {{ $rata >= 75 ? 'text-success' : 'text-error' }}">
                                            {{ $rata }}
                                        </span>
                                        <progress class="progress w-14 h-1.5 {{ $rata >= 75 ? 'progress-success' : 'progress-error' }}"
                                            value="{{ $rata }}" max="100"></progress>
                                    </div>
                                @else
                                    <span class="text-base-content/30 text-xs italic">—</span>
                                @endif
                            </td>

                            <td class="text-center text-xs text-base-content/60">
                                <div>{{ \Carbon\Carbon::parse($paket->tanggal_mulai)->format('d M') }}</div>
                                <div class="text-base-content/30">s/d</div>
                                <div>{{ \Carbon\Carbon::parse($paket->tanggal_selesai)->format('d M Y') }}</div>
                            </td>

                            <td class="text-center">
                                <a href="{{ route('admin.rekap.show', $paket) }}"
                                    class="btn btn-xs btn-primary font-medium gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" class="size-3">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5" />
                                    </svg>
                                    Rekap
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="flex flex-col items-center gap-3 py-14 text-center">
                                    <div class="w-14 h-14 bg-base-200 border border-base-300 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-base-content/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-base-content/60">Belum ada data rekap</p>
                                        <p class="text-xs text-base-content/40 mt-0.5">
                                            {{ request()->hasAny(['search','mapel','guru','status']) ? 'Coba ubah filter pencarian.' : 'Rekap akan muncul setelah ada siswa yang mengikuti ujian.' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($pakets->hasPages())
                <div class="px-5 py-4 border-t border-base-200">
                    {{ $pakets->links() }}
                </div>
            @endif

        </div>
    </div>

</div>
</x-app-layout>