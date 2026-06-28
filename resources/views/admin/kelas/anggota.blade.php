<x-app-layout>
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-bold tracking-tight text-base-content">Kelola Anggota Kelas: <span
                        class="text-primary font-mono">{{ $kelas->nama }}</span></h1>
                <p class="text-sm text-base-content/60 mt-0.5">Pilih siswa yang belum memiliki kelas untuk dimasukkan ke
                    kelas ini.</p>
            </div>
            <a href="{{ route('admin.kelas.index') }}"
                class="btn btn-ghost bg-base-200 hover:bg-base-300 text-base-content btn-sm md:btn-md font-semibold gap-2 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div
                class="card bg-base-100 border border-base-200 shadow-sm rounded-xl overflow-hidden flex flex-col h-[620px]">
                <div class="p-5 border-b border-base-200 bg-base-200/30 flex justify-between items-center shrink-0">
                    <div>
                        <h3 class="text-sm font-bold text-base-content">Pilih Siswa Tanpa Kelas</h3>
                        <p class="text-xs text-base-content/50 mt-0.5">Centang siswa untuk dimasukkan ke kelas.</p>
                    </div>
                    <span class="badge badge-neutral badge-sm font-bold px-2.5">
                        {{ $siswaTanpaKelas->count() }} Tersedia
                    </span>
                </div>

                <form action="{{ route('admin.kelas.tambah-siswa', $kelas->id) }}" method="POST"
                    class="flex flex-col flex-1 overflow-hidden">
                    @csrf

                    @if($siswaTanpaKelas->count() > 0)
                        <div class="px-5 py-2.5 bg-base-200/50 border-b border-base-200 shrink-0">
                            <label class="flex items-center gap-3 cursor-pointer select-none">
                                <input type="checkbox" id="select_all_siswa"
                                    class="checkbox checkbox-primary checkbox-sm rounded-md" />
                                <span class="text-xs font-bold text-base-content/60">Pilih Semua Siswa</span>
                            </label>
                        </div>
                    @endif

                    <div class="flex-1 overflow-y-auto p-4 space-y-2.5">
                        @forelse($siswaTanpaKelas as $siswa)
                            <label
                                class="flex items-start gap-3 p-3.5 border border-base-200 rounded-xl bg-base-100 hover:border-primary/40 hover:bg-primary/5 cursor-pointer transition duration-150 has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                <input type="checkbox" name="siswa_id[]" value="{{ $siswa->profileSiswa->id }}"
                                    class="siswa-item-checkbox checkbox checkbox-primary checkbox-sm rounded-md mt-0.5 shrink-0" />
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-base-content/80 leading-relaxed line-clamp-2">
                                        {{ strip_tags($siswa->name) }}
                                    </p>
                                    <div class="mt-1.5 flex items-center gap-2">
                                        <span class="text-xs text-base-content/40">NISN:</span>
                                        <span
                                            class="badge badge-primary badge-xs font-bold">{{ $siswa->profileSiswa->nis ?? '-' }}</span>
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
                                <h4 class="text-sm font-bold text-base-content/60">Siswa Kosong</h4>
                                <p class="text-xs text-base-content/40 mt-1 max-w-xs">Semua siswa sudah dimasukkan ke kelas
                                    atau belum ada siswa tanpa kelas.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($siswaTanpaKelas->count() > 0)
                        <div class="p-4 bg-base-200/30 border-t border-base-200 flex justify-end shrink-0">
                            <button type="submit" class="btn btn-primary btn-sm font-bold gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                    stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Masukkan ke Kelas
                            </button>
                        </div>
                    @endif
                </form>
            </div>

            <div
                class="card bg-base-100 border border-base-200 shadow-sm rounded-xl overflow-hidden flex flex-col h-[620px]">
                <div class="p-5 border-b border-base-200 bg-primary/5 flex justify-between items-center shrink-0">
                    <div>
                        <h3 class="text-sm font-bold text-base-content">Anggota Kelas</h3>
                        <p class="text-xs text-base-content/50 mt-0.5">Siswa yang terdaftar di kelas ini.</p>
                    </div>
                    <span class="badge badge-primary badge-sm font-bold px-2.5">
                        {{ $siswaDiKelas->count() }} Siswa
                    </span>
                </div>

                <div class="flex-1 overflow-y-auto p-4 space-y-2.5">
                    @forelse($siswaDiKelas as $index => $anggota)
                        <div
                            class="flex items-start gap-3 p-3.5 border border-base-200 rounded-xl bg-base-100 hover:border-base-300 transition group shadow-sm">
                            <div
                                class="w-7 h-7 shrink-0 bg-primary/10 text-primary border border-primary/20 rounded-lg flex items-center justify-center font-bold text-xs">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0 space-y-1">
                                <p class="text-sm text-base-content/80 leading-relaxed line-clamp-2 font-medium">
                                    {{ $anggota->name }}
                                </p>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-base-content/40">NISN:</span>
                                    <span
                                        class="badge badge-primary badge-xs font-bold">{{ $anggota->profileSiswa->nis ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="shrink-0">
                                <form
                                    action="{{ route('admin.kelas.hapus-siswa', [$kelas->id, $anggota->profileSiswa->id]) }}"
                                    method="POST" class="form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" data-name="{{ $anggota->name }}"
                                        class="btn btn-ghost btn-xs text-base-content/30 hover:text-error hover:bg-error/10 p-1 rounded-md h-auto min-h-0 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" class="size-4">
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
                            <h4 class="text-sm font-bold text-base-content/60">Kelas Belum Berisi Siswa</h4>
                            <p class="text-xs text-base-content/40 mt-1 max-w-xs">Pilih siswa dari panel kiri dan klik
                                "Masukkan ke Kelas".</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <script>
        const selectAllSiswa = document.getElementById('select_all_siswa');
        if (selectAllSiswa) {
            const siswaCheckboxes = document.querySelectorAll('.siswa-item-checkbox');

            selectAllSiswa.addEventListener('change', function () {
                siswaCheckboxes.forEach(cb => cb.checked = this.checked);
            });

            siswaCheckboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    const totalChecked = document.querySelectorAll('.siswa-item-checkbox:checked').length;
                    selectAllSiswa.checked = (totalChecked === siswaCheckboxes.length);
                    selectAllSiswa.indeterminate = totalChecked > 0 && totalChecked < siswaCheckboxes.length;
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.form-delete').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const namaKelas = this.querySelector('button[data-name]').getAttribute('data-name');
                    Swal.fire({
                        title: "Apakah anda yakin?",
                        text: `Data siswa "${namaKelas}" akan dikeluarkan dari kelas ini!`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: "Ya, Hapus",
                        cancelButtonText: "Tidak",
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>