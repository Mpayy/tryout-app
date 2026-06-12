{{-- resources/views/siswa/ujian/hasil.blade.php --}}
@extends('layouts.ujian')

@section('title', 'Hasil Ujian')

@section('content')
    <div class="max-w-2xl mx-auto py-8 px-4">

        <div class="card bg-base-100 shadow-sm border border-base-200 overflow-hidden">

            {{-- ═══════════════════════════════════════════════
            BANNER ATAS — kondisional: normal / pelanggaran
            ════════════════════════════════════════════════ --}}
            @if($sesi->jumlah_pelanggaran >= 3)
                <div class="bg-error text-error-content py-10 px-6 text-center space-y-3">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-error-content/20 rounded-full mb-1">
                        {{-- Icon: triangle warning --}}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold tracking-tight">Ujian Dihentikan Otomatis</h1>
                    <p class="text-sm text-error-content/80 max-w-sm mx-auto leading-relaxed">
                        Sistem mendeteksi aktivitas mencurigakan yang melebihi batas toleransi. Nilai Anda telah direkam secara
                        otomatis.
                    </p>
                </div>
            @else
                <div class="bg-success text-success-content py-10 px-6 text-center space-y-3">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-success-content/20 rounded-full mb-1">
                        {{-- Icon: double check --}}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="size-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold tracking-tight">Ujian Selesai!</h1>
                    <p class="text-sm text-success-content/80 leading-relaxed">
                        Terima kasih telah menyelesaikan ujian dengan jujur dan tepat waktu.
                    </p>
                </div>
            @endif

            <div class="card-body p-6 sm:p-8 space-y-6">

                {{-- Alert pelanggaran 1-2x --}}
                @if($sesi->jumlah_pelanggaran > 0 && $sesi->jumlah_pelanggaran < 3)
                    <div role="alert" class="alert alert-warning shadow-sm text-sm gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="size-5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                        <span>
                            Tercatat <strong>{{ $sesi->jumlah_pelanggaran }}x</strong> peringatan
                            (meninggalkan halaman ujian) selama pengerjaan.
                        </span>
                    </div>
                @endif

                {{-- ─── Info Peserta & Ujian ─────────────────── --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-base-200/40 border border-base-200 rounded-xl p-5">
                    <div class="space-y-0.5">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">Mata
                            Pelajaran</span>
                        <div class="text-sm font-bold text-base-content">
                            {{ $sesi->paketUjian->mataPelajaran->nama ?? 'N/A' }}</div>
                    </div>
                    <div class="space-y-0.5">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">Paket
                            Ujian</span>
                        <div class="text-sm font-bold text-base-content">{{ $sesi->paketUjian->nama }}</div>
                    </div>
                    <div class="space-y-0.5 sm:pt-3 sm:border-t border-base-200">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">Selesai
                            Pada</span>
                        <div class="text-sm font-medium text-base-content">
                            {{ \Carbon\Carbon::parse($sesi->waktu_selesai)->translatedFormat('d F Y, H:i') }} WIB
                        </div>
                    </div>
                    <div class="space-y-0.5 sm:pt-3 sm:border-t border-base-200">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">Status
                            Sesi</span>
                        <div class="mt-1">
                            <span
                                class="badge {{ $sesi->jumlah_pelanggaran >= 3 ? 'badge-error' : 'badge-success' }} badge-sm font-bold uppercase tracking-wide px-3 py-2.5">
                                {{ $sesi->status }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- ─── Divider label ───────────────────────── --}}
                <div class="divider text-[10px] font-bold uppercase tracking-widest text-base-content/30 my-0">
                    Ringkasan Hasil
                </div>

                {{-- ─── Stats DaisyUI ───────────────────────── --}}
                <div
                    class="stats stats-vertical sm:stats-horizontal shadow-none border border-base-200 w-full bg-base-100 rounded-xl overflow-hidden">

                    {{-- Nilai Akhir --}}
                    <div class="stat place-items-center py-6">
                        <div class="stat-title text-xs font-bold text-base-content/50 uppercase tracking-wider">Nilai Akhir
                        </div>
                        <div class="stat-value font-mono text-5xl my-3
                            {{ $sesi->nilai >= 75 ? 'text-success' : 'text-error' }}">
                            {{ $sesi->nilai }}
                        </div>
                        <div class="stat-desc text-base-content/40 font-medium">Skala 0 – 100</div>
                        {{-- Lulus / Tidak lulus chip --}}
                        <div class="stat-actions mt-2">
                            @if($sesi->nilai >= 75)
                                <span class="badge badge-success badge-sm font-bold px-3 py-2.5">Lulus</span>
                            @else
                                <span class="badge badge-error badge-sm font-bold px-3 py-2.5">Tidak Lulus</span>
                            @endif
                        </div>
                    </div>

                    {{-- Analisis Jawaban --}}
                    <div class="stat py-6">
                        <div class="stat-title text-xs font-bold text-base-content/50 uppercase tracking-wider mb-3">
                            Analisis Jawaban
                        </div>
                        <div class="space-y-2 w-full">
                            {{-- Benar --}}
                            <div
                                class="flex justify-between items-center bg-success/10 border border-success/20 px-3 py-2 rounded-lg">
                                <span class="flex items-center gap-2 text-xs font-bold text-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2.5" stroke="currentColor" class="size-4 shrink-0">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                    Benar
                                </span>
                                <span class="font-bold font-mono text-success text-base">{{ $sesi->total_benar }}</span>
                            </div>
                            {{-- Salah --}}
                            <div
                                class="flex justify-between items-center bg-error/10 border border-error/20 px-3 py-2 rounded-lg">
                                <span class="flex items-center gap-2 text-xs font-bold text-error">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2.5" stroke="currentColor" class="size-4 shrink-0">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                    Salah
                                </span>
                                <span class="font-bold font-mono text-error text-base">{{ $sesi->total_salah }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Keaktifan --}}
                    <div class="stat py-6">
                        <div class="stat-title text-xs font-bold text-base-content/50 uppercase tracking-wider mb-3">
                            Keaktifan
                        </div>
                        <div class="space-y-2 w-full">
                            {{-- Ragu-ragu --}}
                            <div
                                class="flex justify-between items-center px-3 py-2 bg-warning/10 border border-warning/20 rounded-lg">
                                <span class="flex items-center gap-2 text-xs font-bold text-warning">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" class="size-4 shrink-0">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-.005-10.499l-3.11.732a9 9 0 0 1-6.085-.711l-.108-.054a9 9 0 0 0-6.208-.682L3 4.5M3 15V4.5" />
                                    </svg>
                                    Ragu-ragu
                                </span>
                                <span class="font-bold font-mono text-warning text-base">{{ $sesi->total_ragu }}</span>
                            </div>
                            {{-- Total soal --}}
                            <div class="flex justify-between items-center px-3 py-2 border-t border-base-200 mt-1">
                                <span class="text-xs font-semibold text-base-content/60">Total Soal</span>
                                <span class="font-bold font-mono text-base-content text-base">
                                    {{ $sesi->total_benar + $sesi->total_salah }}
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ─── Progress bar nilai ──────────────────── --}}
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs font-semibold text-base-content/50">
                        <span>Skor</span>
                        <span>{{ $sesi->nilai }} / 100</span>
                    </div>
                    <progress class="progress w-full h-3 {{ $sesi->nilai >= 75 ? 'progress-success' : 'progress-error' }}"
                        value="{{ $sesi->nilai }}" max="100"></progress>
                    <div class="flex justify-between text-[10px] text-base-content/30">
                        <span>0</span>
                        <span class="text-warning">KKM 75</span>
                        <span>100</span>
                    </div>
                </div>

                {{-- ─── Tombol kembali ──────────────────────── --}}
                <div class="flex justify-center pt-2">
                    <a href="{{ route('siswa.ujian.index') }}" class="btn btn-primary btn-wide font-bold gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        Kembali ke Dashboard
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection