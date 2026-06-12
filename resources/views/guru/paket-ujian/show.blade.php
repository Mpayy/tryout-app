<x-app-layout>
    <div class="space-y-6">

        {{-- 1. HEADER --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-bold tracking-tight text-base-content">Kelola Soal Ujian</h1>
                <p class="text-sm text-base-content/60 mt-0.5">Rakit dan kustomisasi butir soal untuk paket ujian
                    terpilih.</p>
            </div>
            <a href="{{ route('guru.paket-ujian.index') }}"
                class="btn btn-ghost bg-base-200 hover:bg-base-300 text-base-content btn-sm md:btn-md font-semibold gap-2 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
        </div>

        {{-- 2. STATS BAR 3 KOLOM --}}
        <div
            class="grid grid-cols-1 md:grid-cols-3 bg-base-100 border border-base-200 shadow-sm rounded-xl divide-y md:divide-y-0 md:divide-x divide-base-200 overflow-hidden">

            {{-- Detail Paket --}}
            <div class="p-5 flex items-start gap-4">
                <div class="p-2.5 bg-primary/10 text-primary rounded-xl shrink-0">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="text-[10px] font-bold text-base-content/40 uppercase tracking-wider">Nama Paket</span>
                    <h3 class="text-sm font-bold text-base-content mt-0.5 leading-tight truncate">
                        {{ $paketUjian->nama }}</h3>
                    <p class="text-xs font-semibold text-primary mt-1">{{ $paketUjian->mataPelajaran->nama }}</p>
                </div>
            </div>

            {{-- Jadwal --}}
            <div class="p-5 flex items-start gap-4">
                <div class="p-2.5 bg-success/10 text-success rounded-xl shrink-0">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-base-content/40 uppercase tracking-wider">Jadwal</span>
                    <div class="mt-1 space-y-0.5">
                        <p class="text-xs text-base-content/70">Mulai: <span
                                class="text-base-content font-bold">{{ \Carbon\Carbon::parse($paketUjian->tanggal_mulai)->format('d M Y') }}</span>
                        </p>
                        <p class="text-xs text-base-content/70">Selesai: <span
                                class="text-base-content font-bold">{{ \Carbon\Carbon::parse($paketUjian->tanggal_selesai)->format('d M Y') }}</span>
                        </p>
                        <p class="text-xs text-base-content/50 mt-1">Durasi: {{ $paketUjian->durasi }} Menit</p>
                    </div>
                </div>
            </div>

            {{-- Status / Publikasi --}}
            <div class="p-5 flex items-start gap-4">
                <div class="p-2.5 bg-warning/10 text-warning rounded-xl shrink-0">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <span class="text-[10px] font-bold text-base-content/40 uppercase tracking-wider">Status
                        Publikasi</span>
                    <div class="mt-2">
                        {{--
                        PERUBAHAN: Hapus emoji dari option value karena emoji tidak konsisten
                        di semua OS dan bisa merusak tampilan. Ganti dengan teks bersih + badge
                        di halaman index sudah cukup untuk visual status.
                        --}}
                        <form action="{{ route('guru.paket-ujian.status', $paketUjian->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                class="select select-bordered select-sm w-full font-medium select-primary">
                                <option value="draft" {{ $paketUjian->status == 'draft' ? 'selected' : '' }}>
                                    Draft — Sembunyikan
                                </option>
                                <option value="aktif" {{ $paketUjian->status == 'aktif' ? 'selected' : '' }}>
                                    Aktif — Buka Ujian
                                </option>
                                <option value="selesai" {{ $paketUjian->status == 'selesai' ? 'selected' : '' }}>
                                    Selesai — Arsipkan
                                </option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. TWO-COLUMN PANEL --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- KIRI: Bank Soal --}}
            <div
                class="card bg-base-100 border border-base-200 shadow-sm rounded-xl overflow-hidden flex flex-col h-[620px]">
                <div class="p-5 border-b border-base-200 bg-base-200/30 flex justify-between items-center shrink-0">
                    <div>
                        <h3 class="text-sm font-bold text-base-content">Pilih dari Bank Soal</h3>
                        <p class="text-xs text-base-content/50 mt-0.5">Soal yang sesuai dengan mapel paket ini.</p>
                    </div>
                    <span class="badge badge-neutral badge-sm font-bold px-2.5">
                        {{ $bankSoal->count() }} Tersedia
                    </span>
                </div>

                <form action="{{ route('guru.paket-ujian.tambah-soal', $paketUjian->id) }}" method="POST"
                    class="flex flex-col flex-1 overflow-hidden">
                    @csrf

                    @if($bankSoal->count() > 0)
                        <div class="px-5 py-2.5 bg-base-200/50 border-b border-base-200 shrink-0">
                            <label class="flex items-center gap-3 cursor-pointer select-none">
                                <input type="checkbox" id="select_all_bank"
                                    class="checkbox checkbox-primary checkbox-sm rounded-md" />
                                <span class="text-xs font-bold text-base-content/60">Pilih Semua Soal</span>
                            </label>
                        </div>
                    @endif

                    <div class="flex-1 overflow-y-auto p-4 space-y-2.5">
                        @forelse($bankSoal as $soal)
                            <label
                                class="flex items-start gap-3 p-3.5 border border-base-200 rounded-xl bg-base-100 hover:border-primary/40 hover:bg-primary/5 cursor-pointer transition duration-150 has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                <input type="checkbox" name="soal_id[]" value="{{ $soal->id }}"
                                    class="bank-item-checkbox checkbox checkbox-primary checkbox-sm rounded-md mt-0.5 shrink-0" />
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-base-content/80 leading-relaxed line-clamp-2">
                                        {{ strip_tags($soal->konten) }}
                                    </p>
                                    <div class="mt-1.5 flex items-center gap-2">
                                        <span class="text-xs text-base-content/40">Kunci:</span>
                                        <span
                                            class="badge badge-primary badge-xs font-bold">{{ $soal->jawabanBenar->label ?? '-' }}</span>
                                    </div>
                                </div>
                            </label>
                        @empty
                            <div class="flex flex-col items-center justify-center h-full text-center py-12 px-4">
                                <div
                                    class="w-12 h-12 bg-base-200 text-base-content/30 rounded-full flex items-center justify-center mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.008 1.24l.885 1.77a2.25 2.25 0 0 0 2.007 1.24h1.98a2.25 2.25 0 0 0 2.007-1.24l.885-1.77a2.25 2.25 0 0 1 2.007-1.24h3.86m-18 0h18" />
                                    </svg>
                                </div>
                                <h4 class="text-sm font-bold text-base-content/60">Bank Soal Kosong</h4>
                                <p class="text-xs text-base-content/40 mt-1 max-w-xs">Semua soal sudah dimasukkan atau belum
                                    ada soal untuk mata pelajaran ini.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($bankSoal->count() > 0)
                        <div class="p-4 bg-base-200/30 border-t border-base-200 flex justify-end shrink-0">
                            <button type="submit" class="btn btn-primary btn-sm font-bold gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                    stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Masukkan ke Paket
                            </button>
                        </div>
                    @endif
                </form>
            </div>

            {{-- KANAN: Soal Terpilih --}}
            <div
                class="card bg-base-100 border border-base-200 shadow-sm rounded-xl overflow-hidden flex flex-col h-[620px]">
                <div class="p-5 border-b border-base-200 bg-primary/5 flex justify-between items-center shrink-0">
                    <div>
                        <h3 class="text-sm font-bold text-base-content">Soal Terpilih</h3>
                        <p class="text-xs text-base-content/50 mt-0.5">Soal yang akan diujikan kepada siswa.</p>
                    </div>
                    <span class="badge badge-primary badge-sm font-bold px-2.5">
                        {{ $paketUjian->soal->count() }} Butir
                    </span>
                </div>

                <div class="flex-1 overflow-y-auto p-4 space-y-2.5">
                    @forelse($paketUjian->soal as $index => $soalPaket)
                        <div
                            class="flex items-start gap-3 p-3.5 border border-base-200 rounded-xl bg-base-100 hover:border-base-300 transition group shadow-sm">
                            <div
                                class="w-7 h-7 shrink-0 bg-primary/10 text-primary border border-primary/20 rounded-lg flex items-center justify-center font-bold text-xs">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0 space-y-1">
                                <p class="text-sm text-base-content/80 leading-relaxed line-clamp-2 font-medium">
                                    {{ Str::limit(strip_tags($soalPaket->konten), 140) }}
                                </p>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-base-content/40">Kunci:</span>
                                    <span
                                        class="badge badge-primary badge-xs font-bold">{{ $soalPaket->jawabanBenar->label ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="shrink-0">
                                <form action="{{ route('guru.paket-ujian.hapus-soal', [$paketUjian->id, $soalPaket->id]) }}"
                                    method="POST" onsubmit="return confirm('Keluarkan soal ini dari paket ujian?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-ghost btn-xs text-base-content/30 hover:text-error hover:bg-error/10 p-1 rounded-md h-auto min-h-0 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center h-full text-center py-12 px-4">
                            <div
                                class="w-14 h-14 bg-base-200 border border-dashed border-base-300 rounded-full flex items-center justify-center mb-3 text-base-content/30">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                                </svg>
                            </div>
                            <h4 class="text-sm font-bold text-base-content/60">Paket Belum Berisi Soal</h4>
                            <p class="text-xs text-base-content/40 mt-1 max-w-xs">Pilih soal dari panel kiri dan klik
                                "Masukkan ke Paket".</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <script>
        const selectAllBank = document.getElementById('select_all_bank');
        if (selectAllBank) {
            const bankCheckboxes = document.querySelectorAll('.bank-item-checkbox');

            selectAllBank.addEventListener('change', function () {
                bankCheckboxes.forEach(cb => cb.checked = this.checked);
            });

            bankCheckboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    const totalChecked = document.querySelectorAll('.bank-item-checkbox:checked').length;
                    selectAllBank.checked = (totalChecked === bankCheckboxes.length);
                    selectAllBank.indeterminate = totalChecked > 0 && totalChecked < bankCheckboxes.length;
                });
            });
        }
    </script>
</x-app-layout>