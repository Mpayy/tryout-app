{{-- resources/views/siswa/ujian/hasil.blade.php --}}
@extends('layouts.ujian')

@section('title', 'Hasil Ujian')

@section('content')
<div class="container mx-auto px-4 py-12 max-w-3xl">
    
    {{-- Main Card Hasil --}}
    <div class="card bg-base-100 shadow-sm border border-base-200 overflow-hidden">
        
        {{-- Banner Atas (Kondisional: Sukses vs Pelanggaran) --}}
        @if($sesi->jumlah_pelanggaran >= 3)
            <div class="bg-error text-error-content py-8 px-6 text-center space-y-3">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-error-content/20 rounded-full mb-1">
                    <i class="bi bi-exclamation-triangle-fill text-4xl"></i>
                </div>
                <h1 class="text-2xl font-bold tracking-wide">Ujian Dihentikan Otomatis!</h1>
                <p class="text-sm opacity-90 max-w-md mx-auto">
                    Sistem mendeteksi aktivitas mencurigakan yang melebihi batas toleransi. Nilai Anda telah direkam secara otomatis.
                </p>
            </div>
        @else
            <div class="bg-success text-success-content py-8 px-6 text-center space-y-2">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-success-content/20 rounded-full mb-1">
                    <i class="bi bi-check2-all text-4xl"></i>
                </div>
                <h1 class="text-2xl font-bold tracking-wide">Ujian Selesai!</h1>
                <p class="text-sm opacity-90">Terima kasih telah menyelesaikan ujian dengan jujur dan tepat waktu.</p>
            </div>
        @endif

        <div class="card-body p-6 sm:p-8 space-y-6">
            
            {{-- Alert Informasi Pelanggaran (Muncul jika ada pelanggaran 1 atau 2 kali) --}}
            @if($sesi->jumlah_pelanggaran > 0 && $sesi->jumlah_pelanggaran < 3)
                <div class="alert alert-warning shadow-sm text-sm">
                    <i class="bi bi-info-circle-fill text-lg"></i>
                    <span>Tercatat <strong>{{ $sesi->jumlah_pelanggaran }} kali</strong> peringatan pelanggaran (meninggalkan halaman ujian) selama pengerjaan.</span>
                </div>
            @endif

            {{-- Detail Informasi Peserta & Ujian --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 bg-base-200/50 p-5 rounded-2xl border border-base-200 text-sm">
                <div class="space-y-1">
                    <span class="text-base-content/60 text-xs font-semibold uppercase tracking-wider">Mata Pelajaran</span>
                    <div class="font-bold text-base">{{ $sesi->paketUjian->mataPelajaran->nama ?? 'N/A' }}</div>
                </div>
                <div class="space-y-1">
                    <span class="text-base-content/60 text-xs font-semibold uppercase tracking-wider">Paket Ujian</span>
                    <div class="font-bold text-base">{{ $sesi->paketUjian->nama }}</div>
                </div>
                <div class="sm:border-t border-base-300 sm:pt-3 space-y-1">
                    <span class="text-base-content/60 text-xs font-semibold uppercase tracking-wider">Selesai Pada</span>
                    <div class="font-medium text-base-content">
                        {{ \Carbon\Carbon::parse($sesi->waktu_selesai)->translatedFormat('d F Y, H:i') }} WIB
                    </div>
                </div>
                <div class="sm:border-t border-base-300 sm:pt-3 space-y-1">
                    <span class="text-base-content/60 text-xs font-semibold uppercase tracking-wider">Status Sesi</span>
                    <div>
                        <span class="badge {{ $sesi->jumlah_pelanggaran >= 3 ? 'badge-error' : 'badge-success' }} badge-sm text-white font-bold uppercase px-3 py-2.5 shadow-sm">
                            {{ $sesi->status }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="divider text-base-content/40 text-sm font-semibold uppercase tracking-widest my-1">Ringkasan Hasil</div>

            {{-- Statistik Nilai (DaisyUI Stats Component) --}}
            <div class="stats stats-vertical sm:stats-horizontal shadow-sm border border-base-200 w-full bg-base-100">
                
                {{-- Score Stat --}}
                <div class="stat place-items-center">
                    <div class="stat-title text-sm font-bold text-base-content/70">Nilai Akhir</div>
                    <div class="stat-value text-primary font-mono text-5xl my-2">{{ $sesi->nilai }}</div>
                    <div class="stat-desc font-medium text-base-content/50">Skala 0 - 100</div>
                </div>
                
                {{-- Analisis Stat --}}
                <div class="stat">
                    <div class="stat-title text-sm font-bold text-base-content/70 mb-3">Analisis Jawaban</div>
                    <div class="space-y-2.5 w-full">
                        <div class="flex justify-between items-center text-sm bg-success/10 px-3 py-1.5 rounded-lg border border-success/20">
                            <span class="flex items-center gap-2 text-success-content font-semibold">
                                <i class="bi bi-check-circle-fill text-success"></i> Benar
                            </span>
                            <span class="font-bold font-mono text-success text-base">{{ $sesi->total_benar }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm bg-error/10 px-3 py-1.5 rounded-lg border border-error/20">
                            <span class="flex items-center gap-2 text-error-content font-semibold">
                                <i class="bi bi-x-circle-fill text-error"></i> Salah
                            </span>
                            <span class="font-bold font-mono text-error text-base">{{ $sesi->total_salah }}</span>
                        </div>
                    </div>
                </div>

                {{-- Keaktifan Stat --}}
                <div class="stat">
                    <div class="stat-title text-sm font-bold text-base-content/70 mb-3">Keaktifan</div>
                    <div class="space-y-2.5 w-full">
                        <div class="flex justify-between items-center text-sm px-3 py-1.5">
                            <span class="flex items-center gap-2 text-warning font-semibold">
                                <i class="bi bi-flag-fill"></i> Ragu-ragu
                            </span>
                            <span class="font-bold font-mono">{{ $sesi->total_ragu }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm px-3 py-1.5 border-t border-base-200">
                            <span class="text-base-content/70 font-medium">Total Soal</span>
                            <span class="font-bold font-mono">
                                {{ $sesi->total_benar + $sesi->total_salah }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Tombol Aksi Bawah --}}
            <div class="card-actions justify-center pt-6 mt-2">
                <a href="{{ route('siswa.ujian.index') }}" class="btn btn-primary btn-wide font-bold shadow-sm rounded-xl">
                    <i class="bi bi-house-door-fill mr-1"></i> Kembali ke Dashboard
                </a>
            </div>

        </div>
    </div>
</div>
@endsection