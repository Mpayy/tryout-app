<x-app-layout>

    {{-- ═══════════════════════════════════════════════
    HEADER
    ════════════════════════════════════════════════ --}}
    <div class="mb-6">
        <h1 class="text-xl font-bold tracking-tight text-base-content">Daftar Ujian</h1>
        <p class="text-sm text-base-content/50 mt-0.5">Ujian aktif yang tersedia untuk kelas Anda.</p>
    </div>

    {{-- Alert session --}}
    @if(session('info'))
        <div role="alert" class="alert alert-info shadow-sm mb-5 text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                class="size-5 shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
            </svg>
            <span class="font-medium">{{ session('info') }}</span>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════
    GRID KARTU UJIAN
    ════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

        @forelse($paketUjian as $paket)
            @php
                $sudahMulai = $paket->sesiSiswa ?? null; // relasi sesi milik siswa ini (jika ada)
                $sudahSelesai = $sudahMulai && in_array($sudahMulai->status, ['selesai', 'timeout']);
                $sedangBerlangsungSiswa = $sudahMulai && $sudahMulai->status === 'berlangsung';

                // Hitung sisa hari tutup
                $sisaHari = now()->diffInDays(\Carbon\Carbon::parse($paket->tanggal_selesai), false);
            @endphp

            <div class="card bg-base-100 border border-base-200 shadow-sm hover:shadow-md hover:border-primary/20
                        transition-all duration-200 flex flex-col
                        {{ $sudahSelesai ? 'opacity-80' : '' }}">
                <div class="card-body p-5 flex flex-col gap-0">

                    {{-- Baris atas: icon + badge status --}}
                    <div class="flex justify-between items-start mb-4">
                        <div
                            class="w-11 h-11 bg-primary/10 text-primary rounded-xl flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" />
                            </svg>
                        </div>

                        {{-- Badge status siswa (prioritas: sudah selesai > sedang > sisa hari) --}}
                        @if($sudahSelesai)
                            <span class="badge badge-neutral badge-sm font-semibold gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                    stroke="currentColor" class="size-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                Sudah Dikerjakan
                            </span>
                        @elseif($sedangBerlangsungSiswa)
                            <span class="badge badge-warning badge-sm font-semibold gap-1.5">
                                <span class="relative flex h-1.5 w-1.5">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-current opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-current"></span>
                                </span>
                                Sedang Berlangsung
                            </span>
                        @elseif($sisaHari >= 0 && $sisaHari <= 2)
                            <span class="badge badge-error badge-sm font-semibold gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-current inline-block"></span>
                                Segera Berakhir
                            </span>
                        @else
                            <span class="badge badge-success badge-sm font-semibold gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-current inline-block"></span>
                                Aktif
                            </span>
                        @endif
                    </div>

                    {{-- Judul & mapel/guru --}}
                    <h2 class="text-base font-bold text-base-content leading-snug line-clamp-2 mb-1">
                        {{ $paket->nama }}
                    </h2>
                    <p class="text-xs font-semibold text-primary mb-0.5">{{ $paket->mataPelajaran->nama }}</p>
                    <p class="text-xs text-base-content/50 mb-4">Guru: {{ $paket->guru->name }}</p>

                    {{-- Jadwal --}}
                    <div class="space-y-2 mb-4">
                        <div class="flex items-start gap-2.5 text-xs text-base-content/70">
                            {{-- Icon kalender mulai --}}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="size-4 text-success shrink-0 mt-0.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                            </svg>
                            <div>
                                <span
                                    class="text-[10px] text-base-content/40 font-bold uppercase tracking-wider block">Dibuka</span>
                                {{ \Carbon\Carbon::parse($paket->tanggal_mulai)->translatedFormat('d M Y, H:i') }} WIB
                            </div>
                        </div>
                        <div class="flex items-start gap-2.5 text-xs text-base-content/70">
                            {{-- Icon kalender tutup --}}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="size-4 text-error shrink-0 mt-0.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                            <div>
                                <span
                                    class="text-[10px] text-base-content/40 font-bold uppercase tracking-wider block">Ditutup</span>
                                {{ \Carbon\Carbon::parse($paket->tanggal_selesai)->translatedFormat('d M Y, H:i') }} WIB
                            </div>
                        </div>
                    </div>

                    {{-- Chip durasi + jumlah soal --}}
                    <div class="grid grid-cols-2 gap-2 mb-5">
                        <div class="bg-base-200/60 border border-base-200 rounded-xl p-2.5 text-center">
                            <span
                                class="text-[10px] font-bold uppercase tracking-wider text-base-content/40 block">Durasi</span>
                            <span class="text-sm font-bold text-base-content">{{ $paket->durasi }} Menit</span>
                        </div>
                        <div class="bg-base-200/60 border border-base-200 rounded-xl p-2.5 text-center">
                            <span
                                class="text-[10px] font-bold uppercase tracking-wider text-base-content/40 block">Soal</span>
                            <span class="text-sm font-bold text-base-content">{{ $paket->soal_count }} Butir</span>
                        </div>
                    </div>

                    {{-- Tombol aksi (kondisional) --}}
                    <div class="mt-auto">
                        @if($sudahSelesai)
                            {{-- Sudah selesai: tampilkan nilai --}}
                            <div
                                class="flex items-center justify-between px-4 py-2.5 bg-base-200/60 border border-base-200 rounded-xl">
                                <span class="text-xs font-semibold text-base-content/60">Nilai Anda</span>
                                <span
                                    class="text-lg font-bold {{ ($sudahMulai->nilai ?? 0) >= 75 ? 'text-success' : 'text-error' }}">
                                    {{ $sudahMulai->nilai ?? '—' }}
                                </span>
                            </div>
                        @elseif($sedangBerlangsungSiswa)
                            {{-- Sedang berlangsung: tombol lanjutkan --}}
                            <a href="{{ route('siswa.ujian.show', $sudahMulai->token) }}"
                                class="btn btn-warning w-full font-bold gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
                                </svg>
                                Lanjutkan Ujian
                            </a>
                        @else
                            {{-- Belum mulai: tombol mulai --}}
                            <form action="{{ route('siswa.ujian.mulai', $paket->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary w-full font-bold gap-2"
                                    onclick="return confirm('Mulai ujian sekarang? Waktu akan langsung berjalan sejak lembar soal dimuat.')">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
                                    </svg>
                                    Mulai Kerjakan
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
            </div>

        @empty
            {{-- Empty state --}}
            <div class="col-span-full">
                <div
                    class="flex flex-col items-center justify-center py-20 bg-base-100 rounded-2xl border border-base-200 shadow-sm text-center">
                    <div
                        class="w-16 h-16 bg-base-200 border border-base-300 text-base-content/30 rounded-full flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-7">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-base-content/70">Belum Ada Ujian Tersedia</h3>
                    <p class="text-xs text-base-content/40 mt-1.5 max-w-xs">
                        Saat ini tidak ada paket ujian aktif yang ditujukan untuk kelas Anda.
                    </p>
                </div>
            </div>
        @endforelse

    </div>

</x-app-layout>