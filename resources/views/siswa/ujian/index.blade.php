<x-app-layout>
    {{-- HEADER halaman --}}
    <div class="flex justify-between items-center bg-base-100 p-4 rounded-xl border border-base-200 shadow-sm mb-6">
        <h2 class="font-bold text-2xl text-base-content flex items-center gap-2">
            <i class="bi bi-display text-primary"></i> Daftar Ujian
        </h2>
        <div class="text-xs md:text-sm text-base-content/60 font-medium">
            Akademik <span class="text-primary">/</span> Ujian
        </div>
    </div>

    <div class="py-2">
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- ALERT SESSION --}}
            @if(session('info'))
                <div class="alert alert-info shadow-md rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        class="stroke-current shrink-0 size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium text-sm text-info-content">{{ session('info') }}</span>
                </div>
            @endif

            {{-- GRID UTAMA KARTU UJIAN --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($paketUjian as $paket)
                    <div
                        class="card bg-base-100 shadow-sm hover:shadow-md border border-base-200 rounded-2xl transition duration-200 flex flex-col justify-between">
                        <div class="card-body p-6 flex flex-col flex-1">

                            {{-- Atas: Ikon & Badge Status --}}
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center shadow-inner">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                    </svg>
                                </div>
                                <span class="badge badge-success font-bold text-xs p-3">Aktif</span>
                            </div>

                            {{-- Judul & Informasi Guru/Mapel --}}
                            <div>
                                <h2
                                    class="card-title text-lg font-bold text-base-content line-clamp-2 leading-tight h-14 items-start">
                                    {{ $paket->nama }}
                                </h2>

                                <div class="space-y-0.5 mt-2">
                                    <p class="text-xs text-base-content/60 font-medium">
                                        Mata Pelajaran: <span
                                            class="text-base-content font-bold">{{ $paket->mataPelajaran->nama }}</span>
                                    </p>
                                    <p class="text-xs text-base-content/60 font-medium">
                                        Guru Pengampu: <span
                                            class="text-base-content font-semibold">{{ $paket->guru->name }}</span>
                                    </p>
                                </div>
                            </div>

                            <div class="divider my-3 opacity-60"></div>

                            {{-- Detail Jadwal & Waktu --}}
                            <div class="flex flex-col gap-2.5 mb-6 flex-1">
                                <div class="flex items-center gap-3 text-xs md:text-sm text-base-content/80">
                                    <i class="bi bi-calendar-check text-success text-base shrink-0"></i>
                                    <div>
                                        <span
                                            class="block text-[10px] uppercase tracking-wider text-base-content/40 font-bold">Mulai</span>
                                        <span
                                            class="font-medium">{{ \Carbon\Carbon::parse($paket->tanggal_mulai)->format('d M Y, H:i') }}
                                            WIB</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 text-xs md:text-sm text-base-content/80">
                                    <i class="bi bi-calendar-x text-error text-base shrink-0"></i>
                                    <div>
                                        <span
                                            class="block text-[10px] uppercase tracking-wider text-base-content/40 font-bold">Selesai</span>
                                        <span
                                            class="font-medium">{{ \Carbon\Carbon::parse($paket->tanggal_selesai)->format('d M Y, H:i') }}
                                            WIB</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2 pt-2">
                                    <div class="bg-base-200/50 p-2 rounded-xl text-center border border-base-200">
                                        <span
                                            class="block text-[9px] uppercase tracking-wider text-base-content/40 font-bold">Durasi</span>
                                        <span class="text-xs font-bold text-base-content">{{ $paket->durasi }} Menit</span>
                                    </div>
                                    <div class="bg-base-200/50 p-2 rounded-xl text-center border border-base-200">
                                        <span
                                            class="block text-[9px] uppercase tracking-wider text-base-content/40 font-bold">Jumlah</span>
                                        <span class="text-xs font-bold text-base-content">{{ $paket->soal_count }}
                                            Soal</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="card-actions mt-auto">
                                <form action="{{ route('siswa.ujian.mulai', $paket->id) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-primary w-full font-bold shadow-md shadow-primary/20 rounded-xl"
                                        onclick="return confirm('Mulai ujian sekarang? Waktu akan langsung berjalan sejak lembar soal dimuat.')">
                                        Mulai Kerjakan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- KONDISI JIKA KOSONG --}}
                    <div class="col-span-full">
                        <div
                            class="flex flex-col items-center justify-center py-16 bg-base-100 rounded-2xl border border-base-200 shadow-sm text-center">
                            <div
                                class="w-20 h-20 bg-base-200 text-base-content/30 rounded-full flex items-center justify-center mb-4">
                                <i class="bi bi-cup-hot text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-base-content/80">Belum Ada Ujian Tersedia</h3>
                            <p class="text-xs text-base-content/50 mt-1 max-w-xs px-4"> Saat ini tidak ada paket ujian aktif
                                atau kelas terjadwal yang ditujukan untuk Anda.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>