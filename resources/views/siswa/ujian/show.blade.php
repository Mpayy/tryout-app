@extends('layouts.ujian')

@section('title', $paketUjian->nama ?? 'Ujian Berlangsung')

@section('content')
    <div class="flex flex-col lg:flex-row gap-5">

        {{-- ===================================================
        PANEL KIRI: Area Soal
        ==================================================== --}}
        <div class="lg:w-2/3 w-full">

            {{-- Progress bar soal di atas area soal --}}
            <div class="card bg-base-100 border border-base-200 shadow-sm mb-4 px-5 py-3">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-bold text-base-content/50 uppercase tracking-wider">Progress</span>
                    <span class="text-xs font-bold text-primary" id="progress-label">0 / {{ $soalList->count() }}
                        dijawab</span>
                </div>
                <progress id="progress-bar" class="progress progress-primary w-full h-2" value="0"
                    max="{{ $soalList->count() }}"></progress>
            </div>

            {{-- Container soal — diisi via AJAX --}}
            <div id="soal-container">
                <div class="card bg-base-100 border border-base-200 shadow-sm">
                    <div class="card-body flex flex-col items-center justify-center py-24 gap-4">
                        <span class="loading loading-spinner loading-lg text-primary"></span>
                        <p class="text-sm text-base-content/50">Menyiapkan soal pertama...</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===================================================
        PANEL KANAN: Sidebar Timer + Navigasi + Submit
        ==================================================== --}}
        <div class="lg:w-1/3 w-full space-y-4">

            {{-- --- TIMER CARD --- --}}
            <div class="card bg-base-100 border border-base-200 shadow-sm">
                <div class="card-body items-center text-center py-5 px-6">
                    <span class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest mb-1">Sisa
                        Waktu</span>
                    <div class="flex items-center gap-3">
                        {{-- Icon stopwatch (inline SVG, ganti Bootstrap Icons) --}}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-7 text-error/70">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span id="timer" class="text-4xl font-bold font-mono tracking-widest text-error">--:--</span>
                    </div>
                </div>
            </div>

            {{-- --- NAVIGASI SOAL CARD --- --}}
            <div class="card bg-base-100 border border-base-200 shadow-sm">
                <div class="card-body p-5">

                    {{-- Judul card --}}
                    <h3 class="text-xs font-bold text-base-content/50 uppercase tracking-widest mb-4">Navigasi Soal</h3>

                    {{-- Legend --}}
                    <div
                        class="flex flex-wrap gap-x-4 gap-y-2 mb-4 pb-4 border-b border-base-200 text-xs font-semibold text-base-content/60">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-primary"></span> Dijawab
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-warning"></span> Ragu-ragu
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full border-2 border-base-300"></span> Belum
                        </div>
                    </div>

                    {{-- Grid nomor soal --}}
                    <div id="nav-soal" class="grid grid-cols-5 gap-2">
                        @foreach($soalList as $i => $soal)
                            @php
                                $j = $jawabList[$soal->id] ?? null;
                                $kelas = 'btn-outline border-base-300 text-base-content/60';
                                if ($j && $j->pilihan_jawaban_id) {
                                    $kelas = $j->is_ragu
                                        ? 'btn-warning text-warning-content border-transparent'
                                        : 'btn-primary text-primary-content border-transparent';
                                }
                            @endphp
                            <button class="btn btn-xs font-bold soal-nav-btn transition-all duration-150 {{ $kelas }}"
                                data-nomor="{{ $i + 1 }}" data-soal-id="{{ $soal->id }}"
                                data-dijawab="{{ ($j && $j->pilihan_jawaban_id) ? 'true' : 'false' }}"
                                data-ragu="{{ ($j && $j->is_ragu) ? 'true' : 'false' }}" id="nav-{{ $soal->id }}">
                                {{ $i + 1 }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- --- TOMBOL SELESAIKAN UJIAN --- --}}
            <button class="btn btn-success w-full font-bold gap-2 shadow-sm" id="btn-submit">
                {{-- Icon check-circle (inline SVG, ganti bi-check-circle) --}}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Selesaikan Ujian
            </button>

        </div>
    </div>

    {{-- Hidden inputs untuk JS --}}
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

        // ============================================================
        // HELPERS: Progress bar
        // ============================================================
        function updateProgressBar() {
            const dijawab = document.querySelectorAll('.soal-nav-btn[data-dijawab="true"]').length;
            document.getElementById('progress-bar').value = dijawab;
            document.getElementById('progress-label').textContent = `${dijawab} / ${TOTAL_SOAL} dijawab`;
        }

        // ============================================================
        // 0. UPDATE WARNA TOMBOL NAVIGASI
        // ============================================================
        function updateNavStatus(nomor, isDijawab, isRagu) {
            const btn = document.querySelector(`.soal-nav-btn[data-nomor="${nomor}"]`);
            if (!btn) return;

            btn.dataset.dijawab = isDijawab ? 'true' : 'false';
            btn.dataset.ragu = isRagu ? 'true' : 'false';

            // Reset ke kelas dasar
            btn.className = 'btn btn-xs font-bold soal-nav-btn transition-all duration-150 ';

            if (isDijawab) {
                btn.className += isRagu
                    ? 'btn-warning text-warning-content border-transparent'
                    : 'btn-primary text-primary-content border-transparent';
            } else {
                btn.className += 'btn-outline border-base-300 text-base-content/60';
            }

            // Ring penanda soal aktif
            if (nomor === soalSekarang) {
                btn.classList.add('ring-2', 'ring-primary', 'ring-offset-1', 'ring-offset-base-100');
            }

            updateProgressBar();
        }

        // ============================================================
        // 1. TIMER
        // ============================================================
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
                } else {
                    el.classList.remove('animate-pulse');
                }
            }, 1000);
        }

        // ============================================================
        // 2. LOAD SOAL (AJAX)
        // ============================================================
        async function loadSoal(nomor) {
            // Loading state
            document.getElementById('soal-container').innerHTML = `
                                                        <div class="card bg-base-100 border border-base-200 shadow-sm">
                                                            <div class="card-body flex items-center justify-center py-24">
                                                                <span class="loading loading-spinner loading-lg text-primary"></span>
                                                            </div>
                                                        </div>`;

            try {
                const res = await fetch(`/siswa/ujian/${TOKEN}/soal/${nomor}`);
                const data = await res.json();
                const isRagu = data.jawaban?.is_ragu || false;

                // Class tombol ragu
                const btnRaguClass = isRagu
                    ? 'btn-warning text-warning-content border-transparent'
                    : 'btn-outline btn-warning';

                // ─── Bangun HTML soal ───────────────────────────────────
                let html = `
                                                        <div class="card bg-base-100 border border-base-200 shadow-sm">
                                                            <div class="card-body p-5 lg:p-7">

                                                                {{-- Header soal: nomor + tombol ragu --}}
                                                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5 pb-4 border-b border-base-200">
                                                                    <div>
                                                                        <span class="text-xs font-bold text-base-content/40 uppercase tracking-wider">Soal</span>
                                                                        <h2 class="text-lg font-bold text-base-content leading-tight">
                                                                            ${nomor}
                                                                            <span class="text-sm text-base-content/40 font-medium">/ ${TOTAL_SOAL}</span>
                                                                        </h2>
                                                                    </div>
                                                                    <button class="btn btn-sm ${btnRaguClass} gap-2 self-start sm:self-auto"
                                                                        id="btn-ragu" onclick="toggleRagu(${data.soal.id})">
                                                                        {{-- Icon flag --}}
                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                                            stroke-width="2" stroke="currentColor" class="size-4">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                d="M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-.005-10.499l-3.11.732a9 9 0 0 1-6.085-.711l-.108-.054a9 9 0 0 0-6.208-.682L3 4.5M3 15V4.5" />
                                                                        </svg>
                                                                        Ragu-ragu
                                                                    </button>
                                                                </div>

                                                                {{-- Konten soal --}}
                                                                <div class="mb-6">
                                                                    <p class="text-base leading-relaxed text-base-content">
                                                                        ${data.soal.konten}
                                                                    </p>
                                                                    ${data.soal.gambar
                        ? `<img src="${data.soal.gambar}" class="rounded-xl border border-base-200 shadow-sm max-h-72 object-contain mt-4">`
                        : ''}
                                                                </div>

                                                                {{-- Pilihan jawaban --}}
                                                                <div class="flex flex-col gap-2.5" id="pilihan-container">`;

                data.pilihan.forEach((p, index) => {
                    const isActive = data.jawaban && data.jawaban.pilihan_id === p.id;
                    const abjad = String.fromCharCode(65 + index); // A, B, C, D
                    const activeClass = isActive
                        ? 'border-primary bg-primary/5 ring-1 ring-primary'
                        : 'border-base-200 hover:border-primary/40 hover:bg-base-200/50';

                    html += `
                                                            <div class="flex items-start gap-3 p-4 border-2 rounded-xl cursor-pointer
                                                                        transition-all duration-150 ${activeClass} pilihan-item group"
                                                                 onclick="pilihJawaban(${data.soal.id}, ${p.id}, this)">

                                                                {{-- Label abjad --}}
                                                                <div class="w-7 h-7 shrink-0 rounded-lg bg-primary/10 text-primary
                                                                            flex items-center justify-center font-bold text-sm mt-0.5
                                                                            group-hover:bg-primary group-hover:text-primary-content transition-colors
                                                                            ${isActive ? '!bg-primary !text-primary-content' : ''}">
                                                                    ${abjad}
                                                                </div>

                                                                {{-- Teks opsi --}}
                                                                <div class="text-sm font-medium text-base-content leading-relaxed pt-1">
                                                                    ${p.konten}
                                                                </div>
                                                            </div>`;
                });

                html += `
                                                                </div>

                                                                {{-- Navigasi bawah --}}
                                                                <div class="flex justify-between items-center mt-6 pt-5 border-t border-base-200">
                                                                    <button class="btn btn-ghost bg-base-200 hover:bg-base-300 btn-sm gap-2 font-medium"
                                                                        onclick="navigasi(${nomor - 1})" ${nomor === 1 ? 'disabled' : ''}>
                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                                            stroke-width="2.5" stroke="currentColor" class="size-4">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                                                                        </svg>
                                                                        Sebelumnya
                                                                    </button>
                                                                    <span class="text-xs text-base-content/40 font-medium hidden sm:block">
                                                                        ${nomor} dari ${TOTAL_SOAL}
                                                                    </span>
                                                                    <button class="btn btn-primary btn-sm gap-2 font-medium"
                                                                        onclick="navigasi(${nomor + 1})" ${nomor === TOTAL_SOAL ? 'disabled' : ''}>
                                                                        Selanjutnya
                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                                            stroke-width="2.5" stroke="currentColor" class="size-4">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                                                        </svg>
                                                                    </button>
                                                                </div>

                                                            </div>
                                                        </div>`;
                // ─── End HTML ────────────────────────────────────────────

                document.getElementById('soal-container').innerHTML = html;

                // Update ring navigasi: lepas ring lama, pasang ke soal baru
                const prevSoal = soalSekarang;
                soalSekarang = nomor;

                const prevBtn = document.querySelector(`.soal-nav-btn[data-nomor="${prevSoal}"]`);
                if (prevBtn) updateNavStatus(prevSoal, prevBtn.dataset.dijawab === 'true', prevBtn.dataset.ragu === 'true');

                const currBtn = document.querySelector(`.soal-nav-btn[data-nomor="${nomor}"]`);
                if (currBtn) updateNavStatus(nomor, currBtn.dataset.dijawab === 'true', currBtn.dataset.ragu === 'true');

            } catch (err) {
                document.getElementById('soal-container').innerHTML = `
                                                            <div class="alert alert-error shadow-sm">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                                    stroke="currentColor" class="size-5 shrink-0">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                                                </svg>
                                                                Gagal memuat soal. Periksa koneksi internet dan <strong>refresh halaman</strong>.
                                                            </div>`;
            }
        }

        // ============================================================
        // 3. PILIH JAWABAN
        // ============================================================
        async function pilihJawaban(soalId, pilihanId, el) {
            // Reset semua pilihan
            document.querySelectorAll('.pilihan-item').forEach(e => {
                e.classList.remove('border-primary', 'bg-primary/5', 'ring-1', 'ring-primary');
                e.classList.add('border-base-200', 'hover:border-primary/40', 'hover:bg-base-200/50');
                // Reset label abjad
                const label = e.querySelector('div:first-child');
                if (label) {
                    label.classList.remove('!bg-primary', '!text-primary-content');
                }
            });

            // Aktifkan pilihan yang diklik
            el.classList.remove('border-base-200', 'hover:border-primary/40', 'hover:bg-base-200/50');
            el.classList.add('border-primary', 'bg-primary/5', 'ring-1', 'ring-primary');
            const label = el.querySelector('div:first-child');
            if (label) label.classList.add('!bg-primary', '!text-primary-content');

            const btnRagu = document.getElementById('btn-ragu');
            const isRagu = btnRagu && !btnRagu.classList.contains('btn-outline');

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

        // ============================================================
        // 4. TANDAI RAGU
        // ============================================================
        async function toggleRagu(soalId) {
            const btn = document.getElementById('btn-ragu');
            const isRaguBaru = btn.classList.contains('btn-outline'); // outline = belum ragu → toggle jadi ragu

            btn.className = isRaguBaru
                ? 'btn btn-sm btn-warning text-warning-content border-transparent gap-2'
                : 'btn btn-sm btn-outline btn-warning gap-2';

            // Rebuild ikon flag agar tetap muncul setelah className diganti
            btn.innerHTML = `
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor" class="size-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-.005-10.499l-3.11.732a9 9 0 0 1-6.085-.711l-.108-.054a9 9 0 0 0-6.208-.682L3 4.5M3 15V4.5" />
                                                        </svg>
                                                        Ragu-ragu`;

            const navBtn = document.querySelector(`.soal-nav-btn[data-nomor="${soalSekarang}"]`);
            const isDijawab = navBtn && navBtn.dataset.dijawab === 'true';
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

        // ============================================================
        // 5. NAVIGASI & SUBMIT
        // ============================================================
        function navigasi(nomor) {
            if (nomor < 1 || nomor > TOTAL_SOAL) return;
            loadSoal(nomor);
        }

        async function autoSubmit() {
            const res = await fetch(`/siswa/ujian/${TOKEN}/submit`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
            });
            const data = await res.json();
            window.location.href = data.redirect;
        }

        document.getElementById('btn-submit').addEventListener('click', function () {
            const dijawab = document.querySelectorAll('.soal-nav-btn[data-dijawab="true"]').length;
            const belum = TOTAL_SOAL - dijawab;

            // Menentukan teks, ikon, dan warna tombol berdasarkan status soal
            const title = belum > 0 ? 'Perhatian!' : 'Konfirmasi Selesai';
            const pesan = belum > 0
                ? `Masih ada ${belum} soal yang belum dijawab. Yakin ingin menyelesaikan ujian?`
                : 'Semua soal sudah dijawab. Yakin ingin menyelesaikan ujian?';
            const iconType = belum > 0 ? 'warning' : 'question';
            const confirmButtonColor = belum > 0 ? '#d33' : '#3085d6'; // Merah jika belum lengkap, biru jika sudah semua

            // Jalankan SweetAlert2
            Swal.fire({
                title: title,
                text: pesan,
                icon: iconType,
                showCancelButton: true,
                confirmButtonColor: confirmButtonColor,
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Ya, Selesai',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                allowOutsideClick: false, // Mencegah klik luar sengaja menutup modal
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Hentikan timer ujian
                    clearInterval(timerInterval);

                    // Nyalakan sakelar bolehKeluar agar browser tidak mencegat proses redirect/submit
                    bolehKeluar = true;

                    // Efek loading pada tombol submit
                    const btnSubmit = document.getElementById('btn-submit');
                    btnSubmit.innerHTML = '<span class="loading loading-spinner loading-sm"></span> Memproses...';
                    btnSubmit.disabled = true;

                    // Jalankan fungsi submit bawaan kamu
                    autoSubmit();
                }
            });
        });

        // Klik nomor navigasi
        document.querySelectorAll('.soal-nav-btn').forEach(btn => {
            btn.addEventListener('click', () => navigasi(parseInt(btn.dataset.nomor)));
        });

        // ============================================================
        // INIT
        // ============================================================
        startTimer(parseInt(document.getElementById('sisa-waktu-awal').value));
        loadSoal(1);
        updateProgressBar();

        // ============================================================
        // 6. ANTI-CURANG: Pelanggaran
        // ============================================================
        let jumlahPelanggaran = parseInt(document.getElementById('db-pelanggaran')?.value || 0);
        const MAKS_PELANGGARAN = 3;
        let lastPelanggaranTime = 0;

        // Flag penting: true selagi browser menampilkan popup izin layar,
        // supaya event 'blur' yang muncul akibat popup itu TIDAK dihitung
        // sebagai pelanggaran "pindah tab".
        let isRequestingPermission = false;

        // Flag tambahan: true setelah status 'blocked' diterima, untuk
        // mengunci semua interaksi lain selagi menunggu redirect selesai.
        let sedangDiblokir = false;

        // ─────────────────────────────────────────────
        // TOAST NON-BLOCKING
        // PERBAIKAN: pengganti alert() yang dulunya menghentikan timer
        // karena alert() itu blocking dan menahan event loop browser.
        // ─────────────────────────────────────────────
        function tampilkanToast(pesan, jenis = 'warning') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: jenis, // 'warning' atau 'error'
                title: pesan
            });
        }

        // ─────────────────────────────────────────────
        // 1. FUNGSI UTAMA PENCATAT PELANGGARAN
        // ─────────────────────────────────────────────
        async function catatPelanggaran(alasan) {
            if (sedangDiblokir) return; // sudah dalam proses blokir, jangan lapor lagi

            const now = Date.now();
            if (now - lastPelanggaranTime < 2000) return; // debounce 2 detik
            lastPelanggaranTime = now;

            jumlahPelanggaran++; // optimistic increment, akan disinkron/rollback di bawah

            try {
                const res = await fetch(`/siswa/ujian/${TOKEN}/pelanggaran`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({ alasan, pelanggaran_ke: jumlahPelanggaran })
                });

                if (!res.ok) throw new Error(`HTTP ${res.status}`);

                const data = await res.json();

                // PERBAIKAN [Critical #2]: selalu sinkronkan counter lokal
                // dengan angka resmi dari server, bukan percaya hitungan client.
                if (typeof data.jumlah_pelanggaran === 'number') {
                    jumlahPelanggaran = data.jumlah_pelanggaran;
                }

                if (data.status === 'blocked') {
                    sedangDiblokir = true;

                    // PERBAIKAN [Major #6]: kunci interaksi dulu sebelum redirect,
                    // supaya tidak ada jendela waktu untuk navigasi manual.
                    document.body.style.pointerEvents = 'none';
                    document.body.style.opacity = '0.6';
                    Swal.fire({
                        title: 'Ujian Dihentikan!',
                        text: 'Ujian dihentikan otomatis karena terlalu sering meninggalkan halaman ujian.',
                        icon: 'error',
                        allowOutsideClick: false, // Siswa tidak bisa klik luar untuk menutup
                        allowEscapeKey: false,    // Siswa tidak bisa tekan tombol Esc
                        showConfirmButton: true,
                        confirmButtonText: 'Lihat Hasil Ujian',
                        timer: 5000,              // Otomatis redirect dalam 5 detik jika didiamkan
                        timerProgressBar: true
                    }).then(() => {
                        // Berjalan setelah tombol diklik ATAU timer habis
                        bolehKeluar = true;
                        window.location.replace(`/siswa/hasil/${TOKEN}`);
                    });

                } else if (data.status === 'warned') {
                    tampilkanToast(
                        `Peringatan! Terdeteksi: ${alasan} (${data.jumlah_pelanggaran}/${MAKS_PELANGGARAN}). Jika mencapai batas, ujian otomatis selesai.`,
                        'warning'
                    );
                }

            } catch (err) {
                // PERBAIKAN [Major #5]: rollback counter lokal jika request gagal,
                // supaya tidak terus naik padahal server tidak tahu apa-apa.
                jumlahPelanggaran--;
                console.error('Gagal mencatat pelanggaran, counter di-rollback:', err);
            }
        }

        // ─────────────────────────────────────────────
        // 2. PROTEKSI PINDAH TAB & MINIMIZE
        // PERBAIKAN [Critical #1]: diaktifkan kembali (sebelumnya di-comment,
        // yang berarti fitur anti-curang utama benar-benar mati).
        // Dijaga oleh isRequestingPermission agar popup izin layar
        // tidak ikut terhitung sebagai "pindah tab".
        // ─────────────────────────────────────────────
        window.addEventListener('blur', () => {
            if (isRequestingPermission) return; // abaikan blur akibat popup izin browser

            setTimeout(() => {
                if (!document.hasFocus() && !isRequestingPermission) {
                    catatPelanggaran('Meninggalkan halaman (Alt+Tab / Aplikasi Lain)');
                }
            }, 200);
        });

        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'hidden' && !isRequestingPermission) {
                catatPelanggaran('Pindah tab atau minimize browser');
            }
        });

        // ─────────────────────────────────────────────
        // 3. PROTEKSI SPLIT SCREEN (RESIZE WINDOW)
        // PERBAIKAN [Minor #8]: threshold dinaikkan 70% → 60%, dan ditambah
        // syarat innerWidth < 800px agar laptop kecil / tablet tidak
        // false-positive ter-deteksi sebagai split screen.
        // ─────────────────────────────────────────────
        let resizeTimeout;
        function cekUkuranSplitScreen() {
            const batasMinimalLebar = window.screen.width * 0.6;
            if (window.innerWidth < batasMinimalLebar && window.innerWidth < 800) {
                catatPelanggaran('Mengecilkan Ukuran Browser (Diduga Split Screen)');
            }
        }

        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(cekUkuranSplitScreen, 1000);
        });

        // ─────────────────────────────────────────────
        // 4. PROTEKSI DUAL MONITOR (HARDWARE)
        // PERBAIKAN [Minor #7]: guard HTTPS, karena getScreenDetails() hanya
        // berjalan di secure context. Tanpa guard, di localhost (HTTP biasa)
        // fungsi ini gagal diam-diam tanpa pesan yang jelas ke developer.
        // ─────────────────────────────────────────────
        async function inisialisasiMultiLayar() {
            if (!('getScreenDetails' in window)) return; // browser tidak mendukung

            if (location.protocol !== 'https:' && location.hostname !== 'localhost') {
                console.warn('Deteksi dual monitor membutuhkan koneksi HTTPS. Fitur ini dilewati.');
                return;
            }

            isRequestingPermission = true; // tandai popup izin akan/sedang muncul

            try {
                const sd = await window.getScreenDetails();
                isRequestingPermission = false;

                if (sd.screens.length > 1) {
                    catatPelanggaran('Menggunakan Dual Monitor / HDMI Aktif');
                }

                sd.addEventListener('screenschange', () => {
                    if (sd.screens.length > 1) {
                        catatPelanggaran('Menggunakan Dual Monitor / HDMI Aktif');
                    }
                });
            } catch (err) {
                isRequestingPermission = false;
                console.warn('Izin deteksi layar ditolak oleh siswa.');
            }
        }

        // ─────────────────────────────────────────────
        // 5. JEMBATAN UTAMA SAAT HALAMAN DIMUAT
        // ─────────────────────────────────────────────
        window.addEventListener('DOMContentLoaded', () => {
            const inputWaktu = document.getElementById('sisa-waktu-awal');
            if (inputWaktu && typeof startTimer === 'function') startTimer(parseInt(inputWaktu.value));
            if (typeof loadSoal === 'function') loadSoal(1);

            // Cek sekali di awal apakah window sudah dalam mode split saat halaman dibuka
            cekUkuranSplitScreen();

            // Taktik "jebakan klik": memancing pop-up izin layar pada interaksi pertama,
            // karena getScreenDetails() butuh user gesture untuk bisa dipanggil.
            const pancingIzinLayar = async () => {
                await inisialisasiMultiLayar();
                document.removeEventListener('click', pancingIzinLayar);
            };
            document.addEventListener('click', pancingIzinLayar);
        });
    </script>
@endpush