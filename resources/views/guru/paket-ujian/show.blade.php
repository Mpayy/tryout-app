<x-app-layout>
    <div class="space-y-6">
        {{-- 1. HEADER SECTION --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-base-content">Kelola Soal Ujian</h1>
                <p class="text-sm text-base-content/60">Rakit dan kustomisasi butir soal untuk paket ujian terpilih.</p>
            </div>
            <div>
                <a href="{{ route('guru.paket-ujian.index') }}"
                    class="btn btn-ghost bg-base-200 hover:bg-base-300 text-base-content btn-sm md:btn-md font-semibold gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                        stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>
        </div>

        {{-- 2. STATS / SUMMARY BAR --}}
        <div
            class="grid grid-cols-1 md:grid-cols-3 bg-base-100 border border-base-200 shadow-sm rounded-xl divide-y md:divide-y-0 md:divide-x divide-base-200 overflow-hidden">
            {{-- Detail Paket --}}
            <div class="p-5 flex items-start gap-4">
                <div class="p-3 bg-primary/10 text-primary rounded-xl">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-base-content/40 uppercase tracking-wider">Nama Paket</span>
                    <h3 class="text-base font-bold text-base-content mt-0.5 leading-tight">{{ $paketUjian->nama }}</h3>
                    <p class="text-xs font-semibold text-primary mt-1">Mata Pelajaran:
                        {{ $paketUjian->mataPelajaran->nama }}
                    </p>
                </div>
            </div>

            {{-- Jadwal --}}
            <div class="p-5 flex items-start gap-4">
                <div class="p-3 bg-success/10 text-success rounded-xl">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-base-content/40 uppercase tracking-wider">Jadwal
                        Pelaksanaan</span>
                    <h3 class="text-xs font-semibold text-base-content/80 mt-1">
                        Mulai: <span
                            class="text-base-content font-bold">{{ \Carbon\Carbon::parse($paketUjian->tanggal_mulai)->format('d M Y') }}</span>
                    </h3>
                    <h3 class="text-xs font-semibold text-base-content/80 mt-0.5">
                        Selesai: <span
                            class="text-base-content font-bold">{{ \Carbon\Carbon::parse($paketUjian->tanggal_selesai)->format('d M Y') }}</span>
                    </h3>
                    <p class="text-xs font-medium text-base-content/50 mt-1">Durasi: {{ $paketUjian->durasi }} Menit</p>
                </div>
            </div>

            {{-- Akses / Status --}}
            <div class="p-5 flex items-start gap-4">
                <div class="p-3 bg-warning/10 text-warning rounded-xl">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <span class="text-[10px] font-bold text-base-content/40 uppercase tracking-wider">Akses &
                        Publikasi</span>
                    <div class="mt-2">
                        <form action="{{ route('guru.paket-ujian.status', $paketUjian->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                class="select select-bordered select-sm w-full font-medium">
                                <option value="draft" {{ $paketUjian->status == 'draft' ? 'selected' : '' }}>🔒 Draft
                                    (Sembunyikan)</option>
                                <option value="aktif" {{ $paketUjian->status == 'aktif' ? 'selected' : '' }}>🔓 Aktif
                                    (Buka Ujian)</option>
                                <option value="selesai" {{ $paketUjian->status == 'selesai' ? 'selected' : '' }}>🏁
                                    Selesai (Arsipkan)</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. TWO-COLUMN INTERACTIVE PANEL --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Kiri: Bank Soal --}}
            <div
                class="card bg-base-100 border border-base-200 shadow-sm rounded-xl overflow-hidden flex flex-col h-[620px]">
                <div class="p-5 border-b border-base-200 bg-base-200/30 flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-bold text-base-content">Pilih dari Bank Soal</h3>
                        <p class="text-xs text-base-content/50 mt-0.5">Koleksi bank soal yang sesuai dengan kategori
                            mapel.</p>
                    </div>
                    <span class="badge badge-primary badge-sm font-bold p-3">
                        {{ $bankSoal->count() }} Tersedia
                    </span>
                </div>

                <form action="{{ route('guru.paket-ujian.tambah-soal', $paketUjian->id) }}" method="POST"
                    class="flex flex-col flex-1 overflow-hidden">
                    @csrf

                    @if($bankSoal->count() > 0)
                        <div class="px-5 py-2.5 bg-base-200/50 border-b border-base-200 flex items-center">
                            <label class="flex items-center gap-3 cursor-pointer select-none">
                                <input type="checkbox" id="select_all_bank"
                                    class="checkbox checkbox-primary checkbox-sm rounded-md" />
                                <span class="text-xs font-bold text-base-content/60">Pilih Semua Soal</span>
                            </label>
                        </div>
                    @endif

                    <div class="flex-1 overflow-y-auto p-5 space-y-3 bg-base-200/10">
                        @forelse($bankSoal as $soal)
                            <label
                                class="flex items-start gap-4 p-4 border border-base-200 rounded-xl hover:bg-base-200/60 hover:border-base-300 cursor-pointer transition bg-base-100 shadow-sm group">
                                <input type="checkbox" name="soal_id[]" value="{{ $soal->id }}"
                                    class="checkbox checkbox-primary checkbox-sm rounded-md mt-0.5 bank-item-checkbox" />
                                <div class="flex-1 space-y-2">
                                    <div
                                        class="text-sm text-base-content/80 group-hover:text-base-content leading-relaxed font-medium transition-colors">
                                        {{ Str::limit(strip_tags($soal->konten), 140) }}
                                    </div>
                                    <div class="flex flex-wrap gap-1.5 pt-0.5">
                                        <span class="badge badge-neutral badge-xs font-bold p-2 tracking-wide">
                                            KUNCI: {{ $soal->jawabanBenar->label ?? '-' }}
                                        </span>
                                        @if($soal->tingkat_kesulitan == 'sulit')
                                            <span class="badge badge-error badge-outline badge-xs font-bold p-2">🔥 SULIT</span>
                                        @elseif($soal->tingkat_kesulitan == 'mudah')
                                            <span class="badge badge-success badge-outline badge-xs font-bold p-2">🌱
                                                MUDAH</span>
                                        @else
                                            <span class="badge badge-warning badge-outline badge-xs font-bold p-2">⚡
                                                SEDANG</span>
                                        @endif
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
                                <h4 class="text-sm font-bold text-base-content/80">Bank Soal Kosong / Sudah Terpilih</h4>
                                <p class="text-xs text-base-content/40 max-w-xs mt-1">Tidak ada sisa soal kategori mata
                                    pelajaran ini yang siap dipindahkan.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($bankSoal->count() > 0)
                        <div class="p-4 bg-base-200/30 border-t border-base-200 flex justify-end">
                            <button type="submit" class="btn btn-primary btn-sm md:btn-md font-bold gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                    stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Masukkan ke Paket Ujian
                            </button>
                        </div>
                    @endif
                </form>
            </div>

            {{-- Kanan: Daftar Soal Terpilih --}}
            <div
                class="card bg-base-100 border border-base-200 shadow-sm rounded-xl overflow-hidden flex flex-col h-[620px]">
                <div class="p-5 border-b border-base-200 bg-primary/5 flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-bold text-base-content">Daftar Soal Terpilih</h3>
                        <p class="text-xs text-base-content/50 mt-0.5">Soal yang tersusun di bawah ini akan diujikan
                            kepada siswa.</p>
                    </div>
                    <span class="badge badge-primary font-bold p-3">
                        {{ $paketUjian->soal->count() }} Butir Soal
                    </span>
                </div>

                <div class="flex-1 overflow-y-auto p-5 space-y-3 bg-base-200/10">
                    @forelse($paketUjian->soal as $index => $soalPaket)
                        <div
                            class="flex items-start gap-4 p-4 border border-base-200 rounded-xl bg-base-100 hover:border-base-300 transition group relative shadow-sm">
                            <div
                                class="w-7 h-7 shrink-0 bg-primary/10 text-primary border border-primary/20 rounded-lg flex items-center justify-center font-bold text-xs">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 space-y-1.5">
                                <div class="text-sm text-base-content/80 leading-relaxed font-medium">
                                    {{ Str::limit(strip_tags($soalPaket->konten), 140) }}
                                </div>
                                <div class="text-xs text-base-content/40 font-semibold">
                                    Kunci Jawaban: <span
                                        class="text-primary font-bold">{{ $soalPaket->jawabanBenar->label ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="shrink-0 ml-2">
                                <form action="{{ route('guru.paket-ujian.hapus-soal', [$paketUjian->id, $soalPaket->id]) }}"
                                    method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin mengeluarkan soal ini dari paket ujian?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-ghost btn-xs text-base-content/40 hover:text-error hover:bg-error/10 p-1 rounded-md h-auto min-h-0 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" class="size-4.5">
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
                            <h4 class="text-sm font-bold text-base-content/80">Paket Ujian Belum Berisi Soal</h4>
                            <p class="text-xs text-base-content/40 max-w-xs mt-1">Gunakan panel sebelah kiri untuk memilah
                                dan memasukkan soal ke dalam daftar lembar ujian ini.</p>
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
                bankCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });

            // Sinkronisasi balik: Jika salah satu checkbox dilepas centangnya, matikan centang master "Pilih Semua"
            bankCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    const totalChecked = document.querySelectorAll('.bank-item-checkbox:checked').length;
                    selectAllBank.checked = (totalChecked === bankCheckboxes.length);
                });
            });
        }
    </script>
</x-app-layout>