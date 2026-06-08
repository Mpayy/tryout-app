<x-app-layout>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-base-content flex items-center gap-2">
                Kelola Anggota Kelas: <span class="text-primary font-mono">{{ $kelas->nama }}</span>
            </h2>
            <p class="text-sm text-base-content/60 mt-0.5">Pilih siswa yang belum memiliki kelas untuk dimasukkan ke
                kelas ini.</p>
        </div>
        <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline btn-primary btn-sm sm:btn-md gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Daftar Kelas
        </a>
    </div>

    {{-- Alert Error Validasi (DaisyUI Pure Alert) --}}
    @if($errors->any())
        <div role="alert" class="alert alert-error mb-6 shadow-sm border-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current text-error-content" fill="none"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0 1 18 0z" />
            </svg>
            <span class="text-error-content text-sm font-medium">{{ $errors->first() }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="card bg-base-100 border border-base-200 shadow-sm overflow-hidden">
            <div class="card-body p-0">
                <div class="p-5 flex justify-between items-center bg-base-200/20 border-b border-base-200">
                    <div>
                        <h3 class="text-base font-bold text-base-content">Siswa Tanpa Kelas</h3>
                        <p class="text-xs text-base-content/50 mt-0.5">Centang siswa untuk dimasukkan ke kelas.</p>
                    </div>
                    <span class="badge badge-neutral font-bold tracking-wide px-2.5 py-1 text-xs">
                        {{ $siswaTanpaKelas->count() }} Tersedia
                    </span>
                </div>

                <form action="{{ route('admin.kelas.tambah-siswa', $kelas->id) }}" method="POST">
                    @csrf

                    {{-- CHECKBOX SELECT ALL --}}
                    @if($siswaTanpaKelas->count() > 0)
                        <div class="px-5 py-3 border-b border-base-200 bg-base-200/10">
                            <label class="flex items-center gap-3 cursor-pointer select-none">
                                <input type="checkbox" id="check_all"
                                    class="checkbox checkbox-sm checkbox-primary rounded-md" />
                                <span class="text-xs font-bold uppercase tracking-wider text-base-content/70">Pilih Semua
                                    Siswa</span>
                            </label>
                        </div>
                    @endif

                    <div class="overflow-y-auto max-h-[450px] p-5 space-y-2.5">
                        @forelse($siswaTanpaKelas as $siswa)
                            <label
                                class="flex items-center gap-4 p-3.5 border border-base-200 rounded-xl hover:bg-base-200/50 cursor-pointer transition-colors duration-150 group">
                                <input type="checkbox" name="siswa_id[]" value="{{ $siswa->profileSiswa->id }}"
                                    class="checkbox-siswa checkbox checkbox-primary rounded-md checkbox-sm" />
                                <div class="flex-1">
                                    <div
                                        class="text-sm font-bold text-base-content group-hover:text-primary transition-colors">
                                        {{ $siswa->name }}
                                    </div>
                                    <div class="mt-0.5 text-xs text-base-content/50 flex flex-wrap gap-x-2 gap-y-1">
                                        <span>NISN: <span
                                                class="font-semibold font-mono text-base-content/70">{{ $siswa->profileSiswa->nis ?? '-' }}</span></span>
                                        <span class="text-base-content/30">|</span>
                                        <span>Email: <span class="text-base-content/70">{{ $siswa->email }}</span></span>
                                    </div>
                                </div>
                            </label>
                        @empty
                            <div class="text-center py-12 px-4">
                                <div class="text-sm font-medium text-base-content/40">
                                    Tidak ada siswa baru yang tersedia.<br>
                                    <span class="text-xs opacity-70">Semua siswa sudah masuk ke kelas masing-masing.</span>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    @if($siswaTanpaKelas->count() > 0)
                        <div class="p-4 bg-base-200/30 border-t border-base-200 flex justify-end">
                            <button type="submit" class="btn btn-sm btn-primary px-5 font-semibold normal-case">
                                Masukkan ke Kelas
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-200 shadow-sm overflow-hidden">
            <div class="card-body p-0">
                <div class="p-5 flex justify-between items-center bg-base-200/20 border-b border-base-200">
                    <div>
                        <h3 class="text-base font-bold text-base-content">Anggota Kelas Sekarang</h3>
                        <p class="text-xs text-base-content/50 mt-0.5">Daftar siswa yang saat ini terdaftar.</p>
                    </div>
                    <span class="badge badge-primary font-bold tracking-wide px-2.5 py-1 text-xs">
                        {{ $siswaDiKelas->count() }} Siswa
                    </span>
                </div>

                <div class="overflow-y-auto max-h-[450px] p-5 space-y-2.5">
                    @forelse($siswaDiKelas as $index => $anggota)
                        <div
                            class="flex items-center gap-4 p-3.5 border border-base-200 rounded-xl hover:bg-base-200/30 transition-colors duration-150">
                            <div
                                class="w-7 h-7 shrink-0 bg-base-200 text-base-content/70 rounded-full flex items-center justify-center font-mono font-bold text-xs">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-bold text-base-content">{{ $anggota->name }}</div>
                                <div class="mt-0.5 text-xs text-base-content/50">
                                    NISN: <span
                                        class="font-semibold font-mono text-base-content/70">{{ $anggota->profileSiswa->nis ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="shrink-0">
                                <form
                                    action="{{ route('admin.kelas.hapus-siswa', [$kelas->id, $anggota->profileSiswa->id]) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin mengeluarkan {{ $anggota->name }} dari kelas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-outline btn-error font-medium px-2.5">
                                        Keluarkan
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-16 text-center">
                            <div class="w-12 h-12 bg-base-200 rounded-full flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-base-content/30" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <p class="text-base-content/40 text-sm font-medium">Belum ada siswa di kelas ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkAll = document.getElementById('check_all');
            const checkboxes = document.querySelectorAll('.checkbox-siswa');

            if (checkAll) {
                // Event ketika checkbox 'Pilih Semua' diklik
                checkAll.addEventListener('change', function () {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });

                // Event ketika checkbox siswa perorangan diklik
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function () {
                        // Cek apakah semua checkbox siswa tercentang
                        const allChecked = Array.from(checkboxes).every(c => c.checked);
                        // Cek apakah ada sebagian yang tercentang
                        const someChecked = Array.from(checkboxes).some(c => c.checked);

                        checkAll.checked = allChecked;

                        // Efek Indeterminate (Tanda strip/minus) jika hanya sebagian yang dicentang
                        checkAll.indeterminate = someChecked && !allChecked;
                    });
                });
            }
        });
    </script>
</x-app-layout>