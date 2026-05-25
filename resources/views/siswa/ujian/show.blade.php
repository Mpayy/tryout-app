{{-- resources/views/siswa/ujian/show.blade.php --}}
@extends('layouts.ujian') {{-- Pastikan layout utama sudah memuat Tailwind & DaisyUI --}}

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl">
    <div class="flex flex-col lg:flex-row gap-6">
        
        {{-- Panel Kiri: Area Soal (Lebar 2/3 di layar besar) --}}
        <div class="lg:w-2/3 w-full">
            <div id="soal-container" class="min-h-[400px]">
                {{-- Skeleton/Spinner bawaan saat memuat via AJAX --}}
                <div class="flex flex-col items-center justify-center py-20 text-base-content/50">
                    <span class="loading loading-spinner loading-lg text-primary mb-4"></span>
                    <p>Menyiapkan soal...</p>
                </div>
            </div>
        </div>

        {{-- Panel Kanan: Sidebar Navigasi & Timer (Lebar 1/3 di layar besar) --}}
        <div class="lg:w-1/3 w-full space-y-4">
            
            {{-- Timer Card --}}
            <div class="card bg-base-100 shadow-md border-t-4 border-error">
                <div class="card-body items-center text-center py-6">
                    <h6 class="text-sm font-semibold text-base-content/70 uppercase tracking-wider">Sisa Waktu</h6>
                    {{-- Countdown font (opsional: bisa dipadukan dengan font mono) --}}
                    <h2 id="timer" class="text-4xl font-bold text-error mt-2 font-mono">--:--</h2>
                </div>
            </div>

            {{-- Navigasi Soal Card --}}
            <div class="card bg-base-100 shadow-md">
                <div class="card-body p-5">
                    {{-- Keterangan / Legend --}}
                    <div class="flex flex-wrap gap-3 mb-4 text-xs font-medium justify-center border-b pb-4">
                        <div class="flex items-center gap-1">
                            <span class="w-3 h-3 rounded-full bg-success"></span> Dijawab
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="w-3 h-3 rounded-full bg-warning"></span> Ragu
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="w-3 h-3 border border-base-content/30 rounded-full"></span> Belum
                        </div>
                    </div>

                    {{-- Grid Navigasi --}}
                    <div id="nav-soal" class="grid grid-cols-5 gap-2">
                        @foreach($soalList as $i => $soal)
                            @php
                                $j = $jawabList[$soal->id] ?? null;
                                // Default: Belum dijawab (Outline)
                                $kelas = 'btn-outline border-base-300 text-base-content/70 hover:bg-base-200'; 
                                if ($j && $j->pilihan_jawaban_id) {
                                    // Jika sudah dijawab, cek apakah ragu
                                    $kelas = $j->is_ragu ? 'btn-warning text-warning-content' : 'btn-success text-success-content border-none';
                                }
                            @endphp
                            <button class="btn btn-sm text-sm p-0 {{ $kelas }} soal-nav-btn transition-all duration-200"
                                    data-nomor="{{ $i+1 }}"
                                    id="nav-{{ $soal->id }}">
                                {{ $i+1 }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Tombol Submit Selesai Ujian --}}
            <button class="btn btn-error w-full shadow-lg" id="btn-submit">
                <i class="bi bi-check-circle text-lg mr-1"></i> Selesaikan Ujian
            </button>
        </div>
    </div>
</div>

{{-- Hidden Inputs untuk State JS --}}
<input type="hidden" id="token" value="{{ $sesi->token }}">
<input type="hidden" id="sisa-waktu-awal" value="{{ $sisaWaktu }}">
<input type="hidden" id="soal-sekarang" value="1">
<input type="hidden" id="total-soal" value="{{ $soalList->count() }}">
@endsection

@push('scripts')
<script>
const TOKEN = document.getElementById('token').value;
const TOTAL_SOAL = parseInt(document.getElementById('total-soal').value);
let soalSekarang = 1;
let timerInterval;

// ========== 1. TIMER ==========
function startTimer(sisaDetik) {
    const el = document.getElementById('timer');
    timerInterval = setInterval(() => {
        sisaDetik--;
        if (sisaDetik <= 0) {
            clearInterval(timerInterval);
            el.textContent = '00:00';
            autoSubmit();
            return;
        }
        const m = String(Math.floor(sisaDetik / 60)).padStart(2, '0');
        const s = String(sisaDetik % 60).padStart(2, '0');
        el.textContent = `${m}:${s}`;
        
        // Animasi pulse dari Tailwind saat waktu < 1 menit
        if (sisaDetik <= 60) {
            el.classList.add('animate-pulse');
        }
    }, 1000);
}

// ========== 2. LOAD SOAL (AJAX) ==========
async function loadSoal(nomor) {
    // Tampilkan loading spinner DaisyUI
    document.getElementById('soal-container').innerHTML = `
        <div class="flex items-center justify-center py-20">
            <span class="loading loading-spinner loading-lg text-primary"></span>
        </div>`;

    try {
        const res  = await fetch(`/siswa/ujian/${TOKEN}/soal/${nomor}`);
        const data = await res.json();

        // Template menggunakan komponen Card DaisyUI
        let html = `
            <div class="card bg-base-100 shadow-md border border-base-200">
                <div class="card-body p-6 lg:p-8">
                    
                    {{-- Header Soal & Tombol Ragu --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-4 border-b border-base-200">
                        <h2 class="text-lg font-bold text-base-content">Soal ${nomor} <span class="text-base-content/50 font-normal">dari ${TOTAL_SOAL}</span></h2>
                        <button class="btn btn-sm ${data.jawaban.is_ragu ? 'btn-warning' : 'btn-outline btn-warning'}"
                                id="btn-ragu" onclick="toggleRagu(${data.soal.id})">
                            <i class="bi bi-flag"></i> Ragu-ragu
                        </button>
                    </div>

                    {{-- Konten Soal --}}
                    <div class="prose max-w-none mb-8 text-base-content">
                        <p class="text-base leading-relaxed">${data.soal.konten}</p>
                        ${data.soal.gambar ? `<img src="${data.soal.gambar}" class="rounded-xl shadow-sm max-h-[300px] object-contain mt-4">` : ''}
                    </div>

                    {{-- Pilihan Jawaban --}}
                    <div class="flex flex-col gap-3" id="pilihan-container">
        `;

        data.pilihan.forEach(p => {
            // Styling jika aktif vs tidak aktif
            const isActive = data.jawaban.pilihan_id === p.id;
            const activeClasses = isActive 
                ? 'border-primary bg-primary/10 ring-1 ring-primary' 
                : 'border-base-300 hover:border-primary/50 hover:bg-base-200/50';

            html += `
                <div class="border-2 rounded-xl p-4 cursor-pointer transition-all duration-200 ${activeClasses} pilihan-item flex gap-4 items-start"
                     onclick="pilihJawaban(${data.soal.id}, ${p.id}, this)">
                    <div class="font-bold text-lg mt-0.5">${p.label}.</div>
                    <div class="text-base pt-1">${p.konten}</div>
                </div>`;
        });

        html += `
                    </div>
                    
                    {{-- Footer Navigasi Prev/Next --}}
                    <div class="flex justify-between items-center mt-8 pt-6 border-t border-base-200">
                        <button class="btn btn-outline" onclick="navigasi(${nomor - 1})" ${nomor === 1 ? 'disabled' : ''}>
                            ← Sebelumnya
                        </button>
                        <button class="btn btn-primary" onclick="navigasi(${nomor + 1})" ${nomor === TOTAL_SOAL ? 'disabled' : ''}>
                            Selanjutnya →
                        </button>
                    </div>
                </div>
            </div>`;

        document.getElementById('soal-container').innerHTML = html;
        soalSekarang = nomor;

        // Highlight navigasi tombol yang sedang aktif
        document.querySelectorAll('.soal-nav-btn').forEach(b => {
            b.classList.remove('ring-2', 'ring-primary', 'ring-offset-2');
        });
        const activeNav = document.querySelector(`[data-nomor="${nomor}"]`);
        if (activeNav) {
            activeNav.classList.add('ring-2', 'ring-primary', 'ring-offset-2');
        }

    } catch (error) {
        console.error("Gagal memuat soal", error);
        document.getElementById('soal-container').innerHTML = `<div class="alert alert-error">Gagal memuat soal. Silakan refresh halaman.</div>`;
    }
}

// ========== 3. PILIH JAWABAN ==========
async function pilihJawaban(soalId, pilihanId, el) {
    // Reset gaya semua pilihan
    document.querySelectorAll('.pilihan-item').forEach(e => {
        e.classList.remove('border-primary', 'bg-primary/10', 'ring-1', 'ring-primary');
        e.classList.add('border-base-300', 'hover:border-primary/50', 'hover:bg-base-200/50');
    });
    
    // Tambah gaya pada pilihan yang di-klik
    el.classList.remove('border-base-300', 'hover:border-primary/50', 'hover:bg-base-200/50');
    el.classList.add('border-primary', 'bg-primary/10', 'ring-1', 'ring-primary');

    // Kirim ke backend
    await fetch(`/siswa/ujian/${TOKEN}/jawab`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
        },
        body: JSON.stringify({ soal_id: soalId, pilihan_jawaban_id: pilihanId })
    });

    // Update status warna di kotak navigasi samping
    const navBtn = document.querySelector(`[data-nomor="${soalSekarang}"]`);
    if (navBtn) {
        // Hapus style lama
        navBtn.classList.remove('btn-outline', 'border-base-300', 'text-base-content/70', 'btn-warning', 'text-warning-content');
        
        // JIKA tombol Ragu sedang aktif (di-UI), berarti statusnya harus warning, bukan success
        const btnRagu = document.getElementById('btn-ragu');
        if(btnRagu && btnRagu.classList.contains('btn-warning') && !btnRagu.classList.contains('btn-outline')) {
            navBtn.classList.add('btn-warning', 'text-warning-content', 'border-none');
        } else {
            navBtn.classList.add('btn-success', 'text-success-content', 'border-none');
        }
    }
}

// ========== 4. TANDAI RAGU ==========
async function toggleRagu(soalId) {
    const btn = document.getElementById('btn-ragu');
    const isRaguSaatIni = btn.classList.contains('btn-warning') && !btn.classList.contains('btn-outline');
    const isRaguBaru = !isRaguSaatIni;

    // Toggle tampilan tombol Ragu di atas soal
    if (isRaguBaru) {
        btn.classList.remove('btn-outline');
    } else {
        btn.classList.add('btn-outline');
    }

    // Kirim ke backend
    await fetch(`/siswa/ujian/${TOKEN}/ragu`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
        },
        body: JSON.stringify({ soal_id: soalId, is_ragu: isRaguBaru })
    });

    // Update kotak navigasi samping JIKA soal ini sudah dijawab
    const navBtn = document.querySelector(`[data-nomor="${soalSekarang}"]`);
    if (navBtn && (navBtn.classList.contains('btn-success') || navBtn.classList.contains('btn-warning'))) {
        if (isRaguBaru) {
            navBtn.classList.remove('btn-success', 'text-success-content');
            navBtn.classList.add('btn-warning', 'text-warning-content');
        } else {
            navBtn.classList.remove('btn-warning', 'text-warning-content');
            navBtn.classList.add('btn-success', 'text-success-content');
        }
    }
}

// ========== 5. NAVIGASI PREV / NEXT ==========
function navigasi(nomor) {
    if (nomor < 1 || nomor > TOTAL_SOAL) return;
    loadSoal(nomor);
}

// ========== 6. AUTO SUBMIT & MANUAL SUBMIT ==========
async function autoSubmit() {
    const res = await fetch(`/siswa/ujian/${TOKEN}/submit`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
    });
    const data = await res.json();
    window.location.href = data.redirect;
}

document.getElementById('btn-submit').addEventListener('click', function() {
    // Opsional: Kamu bisa mengganti confirm() ini dengan modal dari DaisyUI nantinya
    if (confirm('Yakin ingin menyelesaikan ujian? Jawaban tidak bisa diubah setelah ini.')) {
        clearInterval(timerInterval);
        
        // Ganti teks tombol menjadi loading saat diproses
        this.innerHTML = '<span class="loading loading-spinner"></span> Memproses...';
        this.disabled = true;
        
        autoSubmit();
    }
});

// Listener klik pada kotak nomor soal di samping
document.querySelectorAll('.soal-nav-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        navigasi(parseInt(this.dataset.nomor));
    });
});

// ========== INIT SAAT HALAMAN DIMUAT ==========
startTimer(parseInt(document.getElementById('sisa-waktu-awal').value));
loadSoal(1);
</script>
@endpush