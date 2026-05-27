<x-app-layout>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Kelola Anggota Kelas: {{ $kelas->nama }}</h2>
            <p class="text-sm text-slate-500">Pilih siswa yang belum memiliki kelas untuk dimasukkan ke kelas ini.</p>
        </div>
        <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline text-slate-800 border-slate-200 bg-transparent hover:bg-slate-50 shadow-sm normal-case font-semibold">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    Kembali ke Daftar Kelas
</a>

    </div>

    {{-- Tampilkan Alert jika ada error validasi --}}
    @if($errors->any())
        <div class="alert alert-error mb-4 rounded-xl">
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="card bg-white shadow-xl border border-slate-100 rounded-2xl">
            <div class="card-body p-0">
                <div class="bg-slate-50/50 p-5 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Siswa Tanpa Kelas</h3>
                        <p class="text-xs text-slate-500 mt-1">Centang siswa untuk dimasukkan ke kelas.</p>
                    </div>
                    <span
                        class="badge bg-indigo-100 text-indigo-700 font-bold border-none">{{ $siswaTanpaKelas->count() }}
                        Tersedia</span>
                </div>

                <form action="{{ route('admin.kelas.tambah-siswa', $kelas->id) }}" method="POST">
                    @csrf

                    {{-- CHECKBOX SELECT ALL --}}
                    @if($siswaTanpaKelas->count() > 0)
                        <div class="px-5 pt-4 pb-2 border-b border-slate-100">
                            <label class="flex items-center gap-3 cursor-pointer select-none">
                                <input type="checkbox" id="check_all" class="checkbox checkbox-sm checkbox-primary" />
                                <span class="text-sm font-semibold text-slate-700">Pilih Semua Siswa</span>
                            </label>
                        </div>
                    @endif

                    <div class="overflow-y-auto max-h-[500px] p-5">
                        @forelse($siswaTanpaKelas as $siswa)
                            <label
                                class="flex items-center gap-4 p-4 border border-slate-100 rounded-xl hover:bg-indigo-50/30 cursor-pointer mb-3 transition">
                                <input type="checkbox" name="siswa_id[]" value="{{ $siswa->profileSiswa->id }}"
                                    class="checkbox-siswa checkbox checkbox-primary" />
                                <div class="flex-1">
                                    <div class="text-sm text-slate-800 font-bold">{{ $siswa->name }}</div>
                                    <div class="mt-1 text-xs text-slate-500">
                                        NISN: <span class="font-semibold">{{ $siswa->profileSiswa->nis ?? '-' }}</span> |
                                        Email: {{ $siswa->email }}
                                    </div>
                                </div>
                            </label>
                        @empty
                            <div class="text-center py-8 text-slate-400 text-sm">
                                Tidak ada siswa baru yang menganggur.<br>Semua siswa sudah masuk ke kelas masing-masing.
                            </div>
                        @endforelse
                    </div>

                    @if($siswaTanpaKelas->count() > 0)
                        <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                            <button type="submit" class="btn bg-indigo-600 hover:bg-indigo-700 border-none text-white shadow-sm font-semibold normal-case gap-2 px-2">
                                Masukkan ke Kelas
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <div class="card bg-white shadow-xl border border-slate-100 rounded-2xl">
            <div class="card-body p-0">
                <div class="bg-indigo-50/50 p-5 border-b border-indigo-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-indigo-900">Anggota Kelas {{ $kelas->nama }}</h3>
                        <p class="text-xs text-indigo-600 mt-1">Daftar siswa yang saat ini terdaftar.</p>
                    </div>
                    <span class="badge bg-indigo-600 text-white font-bold border-none">{{ $siswaDiKelas->count() }}
                        Siswa</span>
                </div>

                <div class="overflow-y-auto max-h-[500px] p-5">
                    @forelse($siswaDiKelas as $index => $anggota)
                        <div
                            class="flex items-center gap-4 p-4 border border-indigo-50 rounded-xl mb-3 bg-white hover:border-indigo-200 transition group">
                            <div
                                class="w-8 h-8 shrink-0 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center font-bold text-sm">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <div class="text-sm text-slate-800 font-bold">{{ $anggota->name }}</div>
                                <div class="mt-1 text-xs text-slate-500">
                                    NISN: <span class="font-semibold">{{ $anggota->profileSiswa->nis ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="shrink-0">
                                {{-- Form untuk mengeluarkan siswa --}}
                                <form
                                    action="{{ route('admin.kelas.hapus-siswa', [$kelas->id, $anggota->profileSiswa->id]) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin mengeluarkan {{ $anggota->name }} dari kelas ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-ghost btn-sm text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg opacity-0 group-hover:opacity-100 transition"
                                        title="Keluarkan Siswa">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 space-y-3">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-2">
                                <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <p class="text-slate-400 text-sm font-medium">Belum ada siswa di kelas ini.</p>
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