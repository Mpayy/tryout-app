<x-app-layout>
    <x-data-tabel>
        <x-slot name="page">
            Paket Ujian
        </x-slot>

        <x-slot name="header">
            <th class="w-12 text-center">No</th>
            <th>Paket Ujian</th>
            <th>Mata Pelajaran & Kelas</th>
            <th class="text-center">Status</th>
            <th>Periode Ujian</th>
            <th class="text-center">Info Soal</th>
            <th class="text-center">Aksi</th>
        </x-slot>

        @forelse ($paketUjian as $paket)
            <tr class="hover">
                <td class="text-center font-medium">{{ $loop->iteration }}</td>

                <td>
                    <div class="font-bold text-base-content">{{ $paket->nama }}</div>
                </td>

                <td>
                    <div class="flex flex-col gap-1 items-start">
                        <span class="text-sm font-semibold text-primary">
                            {{ $paket->mataPelajaran->nama ?? '-' }}
                        </span>
                        <div class="flex flex-wrap gap-1">
                            @forelse($paket->kelas as $k)
                                <span class="badge badge-neutral badge-xs rounded">
                                    {{ $k->nama }}
                                </span>
                            @empty
                                <span class="text-base-content/40 text-xs italic">Belum ada kelas</span>
                            @endforelse
                        </div>
                    </div>
                </td>

                <td class="text-center align-middle">
                    @if ($paket->status == 'draft')
                        <span class="badge badge-warning badge-sm font-medium">Draft</span>
                    @elseif ($paket->status == 'aktif')
                        <span class="badge badge-success badge-sm text-success-content font-medium">Aktif</span>
                    @else
                        <span class="badge badge-neutral badge-sm font-medium">Selesai</span>
                    @endif
                </td>

                <td class="text-xs">
                    <div class="flex flex-col">
                        <span class="text-base-content/70">
                            Mulai: <b
                                class="text-base-content">{{ \Carbon\Carbon::parse($paket->tanggal_mulai)->format('d M Y') }}</b>
                        </span>
                        <span class="text-base-content/70">
                            Selesai: <b
                                class="text-base-content">{{ \Carbon\Carbon::parse($paket->tanggal_selesai)->format('d M Y') }}</b>
                        </span>
                    </div>
                </td>

                <td class="text-center">
                    <div class="text-sm font-semibold">{{ $paket->soal_count }} Soal</div>
                    <div class="text-xs text-base-content/60">{{ $paket->durasi }} Menit</div>
                </td>

                <td class="text-center align-middle">
                    <div class="flex items-center justify-center gap-1">
                        <a href="{{ route('guru.paket-ujian.show', $paket) }}" class="btn btn-xs btn-primary shadow-none">
                            Kelola Soal
                        </a>

                        <button onclick="openEditModal({{ $paket->load('kelas') }})"
                            class="btn btn-xs btn-warning text-warning-content shadow-none">
                            Edit
                        </button>

                        <form action="{{ route('guru.paket-ujian.destroy', $paket) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ujian ini?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-xs btn-outline btn-error shadow-none">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center py-20">
                    <div class="flex flex-col items-center gap-4">
                        <div
                            class="w-16 h-16 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 9l4-4m0 0l4 4m-4-4v14">
                                </path>
                            </svg>
                        </div>
                        <div class="text-slate-600 text-center">
                            <p class="font-semibold text-lg">Belum ada data</p>
                            <p class="text-sm">Silahkan tambah data terlebih dahulu.</p>
                        </div>
                    </div>
                </td>
            </tr>
        @endforelse
    </x-data-tabel>

    <x-form-modal>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <input type="text" id="input_id" name="id" value="{{ old('id') }}" class="hidden" />
            <div class="form-control col-span-1 sm:col-span-2">
                <label class="label py-1">
                    <span class="label-text font-bold text-base-content/80">Nama Paket Ujian</span>
                </label>
                <input type="text" id="input_name" name="nama" value="{{ old('nama') }}"
                    placeholder="Contoh: Penilaian Tengah Semester Ganjil"
                    class="input input-bordered w-full input-sm md:input-md" required />
            </div>

            <div class="form-control">
                <label class="label py-1">
                    <span class="label-text font-bold text-base-content/80">Mata Pelajaran</span>
                </label>
                <select name="mata_pelajaran_id" id="input_mapel"
                    class="select select-bordered w-full select-sm md:select-md" required>
                    <option value="" disabled selected>Pilih Mata Pelajaran</option>
                    @foreach ($mataPelajaranGuru as $mapel)
                        <option value="{{ $mapel->id }}" {{ old('mata_pelajaran_id') == $mapel->id ? 'selected' : '' }}>
                            {{ $mapel->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-control">
                <label class="label py-1">
                    <span class="label-text font-bold text-base-content/80">Durasi Pengerjaan (Menit)</span>
                </label>
                <input type="number" min="10" id="input_durasi" name="durasi" value="{{ old('durasi') }}"
                    placeholder="Masukkan total menit ujian..." class="input input-bordered w-full input-sm md:input-md"
                    required />
            </div>

            <div class="form-control">
                <label class="label py-1">
                    <span class="label-text font-bold text-base-content/80">Tanggal Dibuka</span>
                </label>
                <input type="date" id="input_tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                    class="input input-bordered w-full input-sm md:input-md" required />
            </div>

            <div class="form-control">
                <label class="label py-1">
                    <span class="label-text font-bold text-base-content/80">Tanggal Ditutup</span>
                </label>
                <input type="date" id="input_tanggal_selesai" name="tanggal_selesai"
                    value="{{ old('tanggal_selesai') }}" class="input input-bordered w-full input-sm md:input-md"
                    required />
            </div>

            <div class="form-control col-span-1 sm:col-span-2">
                <label class="label py-1">
                    <span class="label-text font-bold text-base-content/80">Target Kelas Terbuka</span>
                </label>
                <div
                    class="grid grid-cols-2 gap-2 p-3 bg-base-200/40 border border-base-200 rounded-xl max-h-40 overflow-y-auto">
                    @foreach($daftarKelas as $kelas)
                        <label
                            class="flex items-center gap-2.5 p-2 border border-transparent hover:border-base-300 hover:bg-base-100 rounded-lg cursor-pointer transition duration-150">
                            <input type="checkbox" name="kelas_ids[]" value="{{ $kelas->id }}"
                                class="checkbox checkbox-sm checkbox-primary rounded-md class-checkbox" {{ is_array(old('kelas_ids')) && in_array($kelas->id, old('kelas_ids')) ? 'checked' : '' }} />
                            <span class="text-sm text-base-content/80 font-medium">{{ $kelas->nama }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="form-control col-span-1 sm:col-span-2 border-t border-base-200 mt-2 pt-4">
                <label class="label py-0 pb-3">
                    <span class="label-text font-bold text-base-content/80">Pengaturan Pengacakan</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label
                        class="cursor-pointer flex items-center gap-4 p-3.5 bg-base-200/40 border border-base-200 rounded-xl hover:bg-base-200/80 transition duration-150 group">
                        <input type="checkbox" id="input_acak_soal" name="acak_soal" value="1"
                            class="toggle toggle-primary toggle-sm md:toggle-md" {{ old('acak_soal') ? 'checked' : '' }} />
                        <div>
                            <span
                                class="text-sm font-bold text-base-content group-hover:text-primary transition-colors block">Acak
                                Soal</span>
                            <span class="text-xs text-base-content/50 mt-0.5">Urutan urutan soal berbeda tiap
                                siswa</span>
                        </div>
                    </label>

                    <label
                        class="cursor-pointer flex items-center gap-4 p-3.5 bg-base-200/40 border border-base-200 rounded-xl hover:bg-base-200/80 transition duration-150 group">
                        <input type="checkbox" id="input_acak_jawaban" name="acak_jawaban" value="1"
                            class="toggle toggle-primary toggle-sm md:toggle-md" {{ old('acak_jawaban') ? 'checked' : '' }} />
                        <div>
                            <span
                                class="text-sm font-bold text-base-content group-hover:text-primary transition-colors block">Acak
                                Jawaban</span>
                            <span class="text-xs text-base-content/50 mt-0.5">Opsi pilihan A/B/C/D diacak tiap
                                siswa</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </x-form-modal>

    <script>
        const modal = document.getElementById('modal');
        const form = document.getElementById('form');
        const modalTitle = document.getElementById('modal_title');
        const method = document.getElementById('method');
        const checkBoxes = document.querySelectorAll('.class-checkbox');

        // Fungsi membersihkan centang checkbox sebelum modal dibuka kembali
        function resetCheckboxes() {
            checkBoxes.forEach(box => box.checked = false);
        }

        function openCreateModal() {
            modalTitle.innerText = 'Tambah Paket Ujian Baru';
            form.action = `{{ route('guru.paket-ujian.store') }}`;
            method.innerHTML = '';
            form.reset();
            resetCheckboxes();
            modal.showModal();
        }

        function openEditModal(paket) {
            modalTitle.innerText = 'Edit Konfigurasi Paket Ujian';

            // Generate rute URL update dinamis menggunakan ID paket terkait
            let updateUrl = `{{ route('guru.paket-ujian.update', ':id') }}`;
            form.action = updateUrl.replace(':id', paket.id);

            method.innerHTML = `@method('PUT')`;
            form.reset();
            resetCheckboxes();

            // Potong format datetime MySQL ke YYYY-MM-DD standar HTML5
            const tglMulai = paket.tanggal_mulai ? paket.tanggal_mulai.slice(0, 10) : '';
            const tglSelesai = paket.tanggal_selesai ? paket.tanggal_selesai.slice(0, 10) : '';

            // Mapping nilai value ke dalam form fields
            document.getElementById('input_id').value = paket.id;
            document.getElementById('input_name').value = paket.nama;
            document.getElementById('input_mapel').value = paket.mata_pelajaran_id;
            document.getElementById('input_durasi').value = paket.durasi;
            document.getElementById('input_tanggal_mulai').value = tglMulai;
            document.getElementById('input_tanggal_selesai').value = tglSelesai;

            // Bind toggle acak_soal dan acak_jawaban (nilai dari DB: 1 = true, 0 = false)
            document.getElementById('input_acak_soal').checked = paket.acak_soal == 1;
            document.getElementById('input_acak_jawaban').checked = paket.acak_jawaban == 1;

            // Logika Otomatisasi Centang Checkbox Kelas (Many-to-Many Matcher)
            if (paket.kelas && paket.kelas.length > 0) {
                // Ambil daftar array ID saja dari list kelas relasi paket ujian
                const targetKelasIds = paket.kelas.map(k => k.id);

                // Looping semua checkbox di HTML, jika ID-nya cocok, otomatis centang!
                checkBoxes.forEach(box => {
                    if (targetKelasIds.includes(parseInt(box.value))) {
                        box.checked = true;
                    }
                });
            }

            modal.showModal();
        }

        function closeModal() {
            modal.close();
        }

        // Handle Penjaga Keamanan: Jika ada error server side, modal tidak menutup dan data 'old' tetap terkunci
        document.addEventListener("DOMContentLoaded", function () {
            @if ($errors->any())
                @if (old('_method') == 'PUT')
                    modalTitle.innerText = 'Edit Konfigurasi Paket Ujian';
                    let updateUrl = `{{ route('guru.paket-ujian.update', ':id') }}`;
                    form.action = updateUrl.replace(':id', `{{ old('id') }}`);
                    method.innerHTML = `@method('PUT')`;
                @else
                    modalTitle.innerText = 'Tambah Paket Ujian Baru';
                    form.action = `{{ route('guru.paket-ujian.store') }}`;
                    method.innerHTML = "";
                @endif
                modal.showModal();
            @endif
        });
    </script>
</x-app-layout>