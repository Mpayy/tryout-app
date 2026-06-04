{{-- resources/views/siswa/ujian/show.blade.php --}}
@extends('layouts.ujian')

@section('content')
    <div class="container mx-auto px-4 py-6 max-w-7xl">
        <div class="flex flex-col lg:flex-row gap-6">

            {{-- Panel Kiri: Area Soal --}}
            <div class="lg:w-2/3 w-full">
                <div id="soal-container" class="min-h-[400px]">
                    <div class="flex flex-col items-center justify-center py-20 text-base-content/50">
                        <span class="loading loading-spinner loading-lg text-indigo-600 mb-4"></span>
                        <p>Menyiapkan soal...</p>
                    </div>
                </div>
            </div>

            {{-- Panel Kanan: Sidebar Navigasi & Timer --}}
            <div class="lg:w-1/3 w-full space-y-4">

                {{-- Timer Card --}}
                <div class="card bg-base-100 shadow-md border-t-4 border-error">
                    <div class="card-body items-center text-center py-6">
                        <h6 class="text-sm font-semibold text-base-content/70 uppercase tracking-wider">Sisa Waktu</h6>
                        <h2 id="timer" class="text-4xl font-bold text-error mt-2 font-mono">--:--</h2>
                    </div>
                </div>

                {{-- Navigasi Soal Card --}}
                <div class="card bg-base-100 shadow-md">
                    <div class="card-body p-5">
                        {{-- Keterangan / Legend (Sudah disamakan warnanya) --}}
                        <div
                            class="flex flex-wrap gap-3 mb-4 text-xs font-medium justify-center border-b border-base-200 pb-4">
                            <div class="flex items-center gap-1">
                                <span class="w-3 h-3 rounded-full bg-indigo-600"></span> Dijawab
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="w-3 h-3 rounded-full bg-amber-500"></span> Ragu
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="w-3 h-3 border border-base-300 rounded-full"></span> Belum
                            </div>
                        </div>

                        {{-- Grid Navigasi --}}
                        <div id="nav-soal" class="grid grid-cols-5 gap-2">
                            @foreach($soalList as $i => $soal)
                                @php
                                    $j = $jawabList[$soal->id] ?? null;
                                    // Default: Belum dijawab
                                    $kelas = 'border-base-300 text-base-content/70 hover:bg-base-200 bg-transparent';
                                    if ($j && $j->pilihan_jawaban_id) {
                                        // Jika dijawab, cek ragu
                                        $kelas = $j->is_ragu
                                            ? 'bg-amber-500 hover:bg-amber-600 text-white border-transparent shadow-md'
                                            : 'bg-indigo-600 hover:bg-indigo-700 text-white border-transparent shadow-md';
                                    }
                                @endphp
                                <button
                                    class="btn btn-sm text-sm p-0 border soal-nav-btn transition-all duration-200 {{ $kelas }}"
                                    data-nomor="{{ $i + 1 }}"
                                    data-dijawab="{{ ($j && $j->pilihan_jawaban_id) ? 'true' : 'false' }}"
                                    data-ragu="{{ ($j && $j->is_ragu) ? 'true' : 'false' }}" id="nav-{{ $soal->id }}">
                                    {{ $i + 1 }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <button
                    class="btn bg-indigo-600 hover:bg-indigo-700 text-white w-full border-none shadow-lg shadow-indigo-200 rounded-xl"
                    id="btn-submit">
                    <i class="bi bi-check-circle text-lg mr-1"></i> Selesaikan Ujian
                </button>
            </div>
        </div>
    </div>

    <input type="hidden" id="token" value="{{ $sesi->token }}">
    <input type="hidden" id="db-pelanggaran" value="{{ $sesi->jumlah_pelanggaran }}">
    <input type="hidden" id="sisa-waktu-awal" value="{{ $sisaWaktu }}">
    <input type="hidden" id="total-soal" value="{{ $soalList->count() }}">
@endsection

@push('scripts')
    <script>
        const TOKEN = document.getElementById('token').value;
        const TOTAL_SOAL = parseInt(document.getElementById('total-soal').value);
        let soalSekarang = 1;
        let timerInterval;

        // ========== 0. FUNGSI BANTUAN UPDATE WARNA NAVIGASI ==========
        // Fungsi ini menyelesaikan masalah class bentrok dan warna hilang
        function updateNavStatus(nomor, isDijawab, isRagu) {
            const navBtn = document.querySelector(`[data-nomor="${nomor}"]`);
            if (!navBtn) return;

            // Simpan status di data attribute
            navBtn.dataset.dijawab = isDijawab ? 'true' : 'false';
            navBtn.dataset.ragu = isRagu ? 'true' : 'false';

            // Reset ke class dasar
            navBtn.className = "btn btn-sm text-sm p-0 border soal-nav-btn transition-all duration-200";

            // Set warna sesuai status
            if (isDijawab) {
                if (isRagu) {
                    navBtn.classList.add('bg-amber-500', 'hover:bg-amber-600', 'text-white', 'border-transparent', 'shadow-md');
                } else {
                    navBtn.classList.add('bg-indigo-600', 'hover:bg-indigo-700', 'text-white', 'border-transparent', 'shadow-md');
                }
            } else {
                navBtn.classList.add('border-base-300', 'text-base-content/70', 'hover:bg-base-200', 'bg-transparent');
            }

            // Beri ring/highlight jika ini soal yang sedang dibuka
            if (nomor === soalSekarang) {
                navBtn.classList.add('ring-2', 'ring-indigo-500', 'ring-offset-2');
            }
        }

        // ========== 1. TIMER ==========
        function startTimer(sisaDetik) {
            const endTime = performance.now() + sisaDetik * 1000;
            const el = document.getElementById('timer');
            timerInterval = setInterval(() => {
                const remaining = Math.round((endTime - performance.now()) / 1000);
                if (remaining <= 0) {
                    clearInterval(timerInterval);
                    el.textContent = '00:00';
                    autoSubmit();
                    return;
                }
                const m = String(Math.floor(remaining / 60)).padStart(2, '0');
                const s = String(remaining % 60).padStart(2, '0');
                el.textContent = `${m}:${s}`;

                if (remaining <= 60) {
                    el.classList.add('animate-pulse');
                }
            }, 1000);
        }

        // ========== 2. LOAD SOAL (AJAX) ==========
        async function loadSoal(nomor) {
            document.getElementById('soal-container').innerHTML = `
            <div class="flex items-center justify-center py-20">
                <span class="loading loading-spinner loading-lg text-indigo-600"></span>
            </div>`;

            try {
                const res = await fetch(`/siswa/ujian/${TOKEN}/soal/${nomor}`);
                const data = await res.json();

                const isRagu = data.jawaban?.is_ragu || false;

                // Logika warna tombol Ragu Manual
                const btnRaguClass = isRagu
                    ? 'bg-amber-500 hover:bg-amber-600 text-white border-transparent'
                    : 'bg-transparent border-amber-500 text-amber-600 hover:bg-amber-50';

                let html = `
                <div class="card bg-base-100 shadow-md border border-base-200">
                    <div class="card-body p-6 lg:p-8">

                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-4 border-b border-base-200">
                            <h2 class="text-lg font-bold text-base-content">Soal ${nomor} <span class="text-base-content/50 font-normal">dari ${TOTAL_SOAL}</span></h2>
                            <button class="btn btn-sm ${btnRaguClass}" id="btn-ragu" onclick="toggleRagu(${data.soal.id})">
                                <i class="bi bi-flag"></i> Ragu-ragu
                            </button>
                        </div>

                        <div class="prose max-w-none mb-8 text-base-content">
                            <p class="text-base leading-relaxed">${data.soal.konten}</p>
                            ${data.soal.gambar ? `<img src="${data.soal.gambar}" class="rounded-xl shadow-sm max-h-[300px] object-contain mt-4">` : ''}
                        </div>

                        <div class="flex flex-col gap-3" id="pilihan-container">
            `;

                // LOOPING JAWABAN
                data.pilihan.forEach((p, index) => {
                    const isActive = data.jawaban && data.jawaban.pilihan_id === p.id;
                    const activeClasses = isActive
                        ? 'border-indigo-600 bg-indigo-50 ring-1 ring-indigo-600'
                        : 'border-base-300 hover:border-indigo-300 hover:bg-base-50';

                    // GENERATE ABJAD (A, B, C, D) SECARA DINAMIS (Mengatasi urutan berantakan saat diacak)
                    const abjad = String.fromCharCode(65 + index);

                    html += `
                    <div class="border-2 rounded-xl p-4 cursor-pointer transition-all duration-200 ${activeClasses} pilihan-item flex gap-4 items-start"
                         onclick="pilihJawaban(${data.soal.id}, ${p.id}, this)">
                        <div class="font-bold text-lg mt-0.5 text-indigo-700">${abjad}.</div>
                        <div class="text-base pt-1">${p.konten}</div>
                    </div>`;
                });

                html += `
                        </div>

                        <div class="flex justify-between items-center mt-8 pt-6 border-t border-base-200">
                            <button class="btn btn-outline" onclick="navigasi(${nomor - 1})" ${nomor === 1 ? 'disabled' : ''}>
                                ← Sebelumnya
                            </button>
                            <button class="btn bg-indigo-600 hover:bg-indigo-700 text-white border-transparent" onclick="navigasi(${nomor + 1})" ${nomor === TOTAL_SOAL ? 'disabled' : ''}>
                                Selanjutnya →
                            </button>
                        </div>
                    </div>
                </div>`;

                document.getElementById('soal-container').innerHTML = html;

                // Update state navigasi yang aktif 
                const prevSoal = soalSekarang;
                soalSekarang = nomor;

                // Hilangkan ring dari tombol sebelumnya, tambahkan ke tombol saat ini
                const prevBtn = document.querySelector(`[data-nomor="${prevSoal}"]`);
                if (prevBtn) updateNavStatus(prevSoal, prevBtn.dataset.dijawab === 'true', prevBtn.dataset.ragu === 'true');

                const currBtn = document.querySelector(`[data-nomor="${nomor}"]`);
                if (currBtn) updateNavStatus(nomor, currBtn.dataset.dijawab === 'true', currBtn.dataset.ragu === 'true');

            } catch (error) {
                document.getElementById('soal-container').innerHTML = `<div class="alert alert-error">Gagal memuat soal. Silakan refresh.</div>`;
            }
        }

        // ========== 3. PILIH JAWABAN ==========
        async function pilihJawaban(soalId, pilihanId, el) {
            document.querySelectorAll('.pilihan-item').forEach(e => {
                e.classList.remove('border-indigo-600', 'bg-indigo-50', 'ring-1', 'ring-indigo-600');
                e.classList.add('border-base-300', 'hover:border-indigo-300', 'hover:bg-base-50');
            });

            el.classList.remove('border-base-300', 'hover:border-indigo-300', 'hover:bg-base-50');
            el.classList.add('border-indigo-600', 'bg-indigo-50', 'ring-1', 'ring-indigo-600');

            // Cek apakah tombol ragu saat ini sedang aktif
            const btnRagu = document.getElementById('btn-ragu');
            const isRagu = btnRagu && btnRagu.classList.contains('bg-amber-500');

            // Update warna di navigasi samping langsung!
            updateNavStatus(soalSekarang, true, isRagu);

            await fetch(`/siswa/ujian/${TOKEN}/jawab`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({ soal_id: soalId, pilihan_jawaban_id: pilihanId })
            });
        }

        // ========== 4. TANDAI RAGU ==========
        async function toggleRagu(soalId) {
            const btn = document.getElementById('btn-ragu');
            const isRaguSaatIni = btn.classList.contains('bg-amber-500');
            const isRaguBaru = !isRaguSaatIni;

            // Toggle tampilan tombol Ragu
            if (isRaguBaru) {
                btn.className = "btn btn-sm bg-amber-500 hover:bg-amber-600 text-white border-transparent";
            } else {
                btn.className = "btn btn-sm bg-transparent border-amber-500 text-amber-600 hover:bg-amber-50";
            }

            // Cek apakah soal ini sudah dijawab (lewat dataset yang kita setel tadi)
            const navBtn = document.querySelector(`[data-nomor="${soalSekarang}"]`);
            const isDijawab = navBtn && navBtn.dataset.dijawab === 'true';

            // Update kotak navigasi samping
            updateNavStatus(soalSekarang, isDijawab, isRaguBaru);

            await fetch(`/siswa/ujian/${TOKEN}/ragu`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({ soal_id: soalId, is_ragu: isRaguBaru })
            });
        }

        // ========== 5. NAVIGASI PREV / NEXT ==========
        function navigasi(nomor) {
            if (nomor < 1 || nomor > TOTAL_SOAL) return;
            loadSoal(nomor);
        }

        // ========== 6. AUTO SUBMIT ==========
        async function autoSubmit() {
            const res = await fetch(`/siswa/ujian/${TOKEN}/submit`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
            });
            const data = await res.json();
            window.location.href = data.redirect;
        }

        document.getElementById('btn-submit').addEventListener('click', function () {
            if (confirm('Yakin ingin menyelesaikan ujian?')) {
                clearInterval(timerInterval);
                this.innerHTML = '<span class="loading loading-spinner"></span> Memproses...';
                this.disabled = true;
                autoSubmit();
            }
        });

        document.querySelectorAll('.soal-nav-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                navigasi(parseInt(this.dataset.nomor));
            });
        });

        startTimer(parseInt(document.getElementById('sisa-waktu-awal').value));
        loadSoal(1);

        let jumlahPelanggaran = parseInt(document.getElementById('db-pelanggaran').value) || 0;
        const MAKSIMAL_PELANGGARAN = 3; // Siswa otomatis keluar di pelanggaran ke-3

        let lastPelanggaranTime = 0;

        function catatPelanggaran(alasan) {
            const now = Date.now();
            if (now - lastPelanggaranTime < 2000) return; // Mencegah double trigger (debounce 2 detik)
            lastPelanggaranTime = now;

            jumlahPelanggaran++;

            // Kirim data pelanggaran ke backend via AJAX untuk dicatat di database (anti-curang)
            fetch(`/siswa/ujian/${TOKEN}/pelanggaran`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({ alasan: alasan, pelanggaran_ke: jumlahPelanggaran })
            });

            if (jumlahPelanggaran >= MAKSIMAL_PELANGGARAN) {
                alert('Ujian Anda dihentikan otomatis karena terlalu sering keluar dari halaman ujian!');
                autoSubmit(); // Fungsi submit yang sudah kita buat sebelumnya
            } else {
                alert(`Peringatan keras! Anda terdeteksi mencoba keluar dari halaman ujian (${jumlahPelanggaran}/${MAKSIMAL_PELANGGARAN}). \nJika mencapai batas, ujian akan otomatis selesai!`);
            }
        }

        // Deteksi Taktik 1: Browser kehilangan fokus (Alt+Tab, klik aplikasi lain)
        window.addEventListener('blur', () => {
            // Kasih jeda sedikit untuk memastikan bukan karena alert/pop-up internal aplikasi
            setTimeout(() => {
                if (!document.hasFocus()) {
                    catatPelanggaran('Meninggalkan halaman (Alt+Tab / Buka Aplikasi Lain)');
                }
            }, 200);
        });

        // Deteksi Taktik 2: Pindah tab browser atau minimize window
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'hidden') {
                catatPelanggaran('Pindah tab atau meminimalkan browser');
            }
        });

        async function cekMultiLayar() {
            // 1. Cek menggunakan API Modern (Window Management)
            if ('getScreenDetails' in window || 'getScreens' in window) {
                try {
                    const screenDetails = window.getScreenDetails ? await window.getScreenDetails() : await window.getScreens();

                    // Jika jumlah layar lebih dari 1, rekam sebagai pelanggaran!
                    if (screenDetails.screens.length > 1) {
                        laporkanKecuranganLayar();
                    }

                    // Deteksi jika di tengah ujian siswa baru mencolok kabel HDMI
                    screenDetails.addEventListener('screenschange', () => {
                        if (screenDetails.screens.length > 1) {
                            laporkanKecuranganLayar();
                        }
                    });
                } catch (err) {
                    console.log("Izin deteksi layar ditolak atau diblokir browser.");
                }
            }

            // 2. Cadangan (Fallback) untuk browser lama: Cek resolusi lebar layar tidak wajar
            // Siswa jagoan sering pakai mode 'Extend' yang membuat window.screen.width menjadi sangat besar
            if (window.screen.width > 2560) {
                laporkanKecuranganLayar();
            }
        }

        function laporkanKecuranganLayar() {
            alert('Terdeteksi: Anda menggunakan lebih dari 1 layar (Dual Monitor/HDMI)! \nLepaskan layar tambahan untuk melanjutkan ujian.');

            // Panggil fungsi catatPelanggaran yang sudah kita buat ke database sebelumnya
            catatPelanggaran('Menggunakan Dual Monitor / Kabel HDMI Aktif');
        }

        // Jalankan fungsi ini saat ujian dimulai
        window.addEventListener('DOMContentLoaded', () => {
            cekMultiLayar();
        });
    </script>
@endpush