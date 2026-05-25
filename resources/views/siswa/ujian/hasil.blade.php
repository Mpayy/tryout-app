{{-- resources/views/siswa/ujian/hasil.blade.php --}}
@extends('layouts.ujian') {{-- Menggunakan layout minimalis yang sama --}}

@section('title', 'Hasil Ujian')

@section('content')
<div class="container mx-auto px-4 py-12 max-w-3xl">
    
    {{-- Main Card Hasil --}}
    <div class="card bg-base-100 shadow-xl border border-base-200 overflow-hidden animate__animated animate__fadeIn">
        
        {{-- Banner Atas (Warna hijau sukses) --}}
        <div class="bg-success text-success-content py-8 px-6 text-center space-y-2">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full mb-2">
                <i class="bi bi-check2-all text-4xl"></i>
            </div>
            <h1 class="text-2xl font-bold tracking-wide">Ujian Selesai!</h1>
            <p class="text-sm opacity-90">Terima kasih telah menyelesaikan ujian dengan jujur dan tepat waktu.</p>
        </div>

        <div class="card-body p-6 sm:p-8 space-y-6">
            
            {{-- Detail Informasi Peserta & Ujian --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-base-200/50 p-4 rounded-xl border border-base-200 text-sm">
                <div>
                    <span class="text-base-content/60 block">Mata Pelajaran:</span>
                    <strong class="text-base text-base-content">{{ $sesi->paketUjian->mataPelajaran->nama ?? 'N/A' }}</strong>
                </div>
                <div>
                    <span class="text-base-content/60 block">Paket Ujian:</span>
                    <strong class="text-base text-base-content">{{ $sesi->paketUjian->nama }}</strong>
                </div>
                <div class="sm:border-t sm:pt-2 border-base-300">
                    <span class="text-base-content/60 block">Selesai Pada:</span>
                    <strong class="text-base-content font-medium">
                        {{ \Carbon\Carbon::parse($sesi->waktu_selesai)->translatedFormat('d F Y, H:i') }} WIB
                    </strong>
                </div>
                <div class="sm:border-t sm:pt-2 border-base-300">
                    <span class="text-base-content/60 block">Status Sesi:</span>
                    <span class="badge badge-success badge-sm text-white font-semibold uppercase px-2 py-2">
                        {{ $sesi->status }}
                    </span>
                </div>
            </div>

            <div class="divider my-2">Ringkasan Hasil</div>

            {{-- Statistik Nilai (DaisyUI Stats Component) --}}
            <div class="stats stats-vertical sm:stats-horizontal shadow-sm border border-base-200 w-full bg-base-50">
                
                {{-- Score Stat --}}
                <div class="stat place-items-center sm:place-items-start">
                    <div class="stat-title text-sm font-semibold">Nilai Akhir</div>
                    <div class="stat-value text-primary font-mono text-4xl sm:text-5xl my-1">{{ $sesi->nilai }}</div>
                    <div class="stat-desc text-xs text-base-content/50">Skala 0 - 100</div>
                </div>
                
                {{-- Benar & Salah Stat --}}
                <div class="stat place-items-center sm:place-items-start">
                    <div class="stat-title text-sm font-semibold">Analisis Jawaban</div>
                    <div class="space-y-1 mt-2 w-full">
                        <div class="flex justify-between text-sm">
                            <span class="flex items-center gap-1.5 text-success font-medium">
                                <span class="w-2.5 h-2.5 bg-success rounded-full"></span> Benar
                            </span>
                            <span class="font-bold font-mono">{{ $sesi->total_benar }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="flex items-center gap-1.5 text-error font-medium">
                                <span class="w-2.5 h-2.5 bg-error rounded-full"></span> Salah
                            </span>
                            <span class="font-bold font-mono">{{ $sesi->total_salah }}</span>
                        </div>
                    </div>
                </div>

                {{-- Ragu-ragu Stat --}}
                <div class="stat place-items-center sm:place-items-start">
                    <div class="stat-title text-sm font-semibold">Catatan Keaktifan</div>
                    <div class="space-y-1 mt-2 w-full">
                        <div class="flex justify-between text-sm">
                            <span class="flex items-center gap-1.5 text-warning font-medium">
                                <span class="w-2.5 h-2.5 bg-warning rounded-full"></span> Sempat Ragu
                            </span>
                            <span class="font-bold font-mono">{{ $sesi->total_ragu }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-base-content/60">
                            <span>Total Soal</span>
                            <span class="font-bold font-mono">
                                {{ $sesi->total_benar + $sesi->total_salah }} Soal
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Tombol Aksi Bawah --}}
            <div class="card-actions justify-center pt-4">
                <a href="{{ route('siswa.ujian.index') }}" class="btn btn-primary px-8 shadow-md">
                    <i class="bi bi-house-door mr-1"></i> Kembali ke Dashboard Ujian
                </a>
            </div>

        </div>
    </div>
</div>
@endsection