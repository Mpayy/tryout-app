<x-app-layout>
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-800">Manajemen Paket Ujian</h1>
                <p class="text-sm text-slate-500">Kelola daftar paket ujian dan tentukan kelas penerima ujian.</p>
            </div>
            <div>
                <button onclick="openCreateModal()"
                    class="btn bg-indigo-600 hover:bg-indigo-700 border-none text-white shadow-sm font-semibold normal-case gap-2 px-4 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Paket Ujian
                </button>
            </div>
        </div>

        <div class="card bg-white border border-slate-200/80 shadow-sm rounded-xl overflow-hidden">
            <div class="p-6 space-y-6">
                <div class="overflow-x-auto rounded-lg border border-slate-100">
                    <table class="table w-full text-slate-700">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-200 text-slate-600 font-semibold text-sm">
                                <th class="w-16 text-center">#</th>
                                <th>Nama Paket Ujian</th>
                                <th class="text-center">Mata Pelajaran</th>
                                <th class="text-center">Kelas Dituju</th>
                                <th class="text-center">Tanggal Mulai</th>
                                <th class="text-center">Tanggal Selesai</th>
                                <th class="text-center">Durasi</th>
                                <th class="text-center">Total Soal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($paketUjian as $paket)
                                <tr class="hover:bg-slate-50/50 transition duration-150">
                                    <td class="text-center font-medium text-slate-500">{{ $loop->iteration }}</td>
                                    <td class="font-semibold text-slate-800">{{ $paket->nama }}</td>
                                    <td class="text-center font-medium">
                                        <div
                                            class="badge bg-slate-100 text-slate-600 font-semibold px-3 py-2.5 rounded-md border-none text-xs">
                                            {{ $paket->mataPelajaran->nama ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="flex flex-wrap justify-center gap-1">
                                            @forelse($paket->kelas as $k)
                                                <span
                                                    class="badge bg-indigo-50 text-indigo-600 border border-indigo-100 text-xs font-medium rounded px-2 py-1">
                                                    {{ $k->nama }}
                                                </span>
                                            @empty
                                                <span class="text-slate-400 text-xs italic">Belum diatur</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="text-center text-sm">
                                        {{ \Carbon\Carbon::parse($paket->tanggal_mulai)->format('d F Y') }}
                                    </td>
                                    <td class="text-center text-sm">
                                        {{ \Carbon\Carbon::parse($paket->tanggal_selesai)->format('d F Y') }}
                                    </td>
                                    <td class="text-center font-medium text-sm">{{ $paket->durasi }} Menit</td>
                                    <td class="text-center font-medium text-sm">{{ $paket->soal_count }}</td>
                                    <td class="text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button onclick="openEditModal({{ $paket->load('kelas') }})"
                                                class="btn btn-sm bg-indigo-50 hover:bg-indigo-100 border-none text-indigo-700 normal-case font-medium px-3 rounded-lg shadow-none transition">
                                                Edit
                                            </button>
                                            <form action="{{ route('guru.paket-ujian.destroy', $paket) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ujian ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm bg-rose-50 hover:bg-rose-100 border-none text-rose-700 normal-case font-medium px-3 rounded-lg shadow-none transition">
                                                    Hapus
                                                </button>
                                            </form>
                                            <a href="{{ route('guru.paket-ujian.show', $paket) }}"
                                                class="btn btn-sm bg-emerald-50 hover:bg-emerald-100 border-none text-emerald-700 normal-case font-medium px-3 shadow-none">Kelola Soal</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <dialog id="modal"
        class="modal modal-bottom sm:modal-middle bg-slate-900/40 backdrop-blur-sm transition-all duration-300">
        <div class="modal-box bg-white border border-slate-200/80 shadow-xl max-w-2xl p-6 rounded-xl text-slate-700">

            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                <div>
                    <h3 id="modal_title" class="text-xl font-bold text-slate-800">Tambah Paket Ujian</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Isi data konfigurasi paket ujian dan tentukan target kelas
                        penerima.</p>
                </div>
                <button type="button" onclick="closeModal()"
                    class="btn btn-sm btn-circle btn-ghost text-slate-400 hover:text-slate-600 hover:bg-slate-100">✕</button>
            </div>

            @if ($errors->any())
                <div role="alert"
                    class="alert alert-error mb-5 bg-rose-50 text-rose-800 border-rose-200 shadow-none rounded-lg p-3.5">
                    <div class="flex flex-col gap-1.5 items-start">
                        @foreach ($errors->all() as $error)
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 stroke-current text-rose-600"
                                    fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm font-medium">{{ $error }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <form id="form" method="POST" action="" class="space-y-5">
                @csrf
                <div id="method"></div>
                <input type="hidden" name="id" id="input_id" value="{{ old('id') }}">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-control w-full col-span-1 sm:col-span-2">
                        <label class="label py-1"><span class="label-text font-semibold text-slate-700 text-sm">Nama
                                Paket Ujian</span></label>
                        <input type="text" id="input_name" name="nama" value="{{ old('nama') }}"
                            placeholder="Contoh: Penilaian Tengah Semester Ganjil"
                            class="input input-bordered w-full bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg text-sm transition"
                            required />
                    </div>

                    <div class="form-control w-full">
                        <label class="label py-1"><span class="label-text font-semibold text-slate-700 text-sm">Mata
                                Pelajaran</span></label>
                        <select name="mata_pelajaran_id" id="input_mapel"
                            class="select select-bordered w-full bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg text-sm transition"
                            required>
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach ($mataPelajaranGuru as $mapel)
                                <option value="{{ $mapel->id }}" {{ old('mata_pelajaran_id') == $mapel->id ? 'selected' : '' }}>{{ $mapel->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-control w-full">
                        <label class="label py-1"><span class="label-text font-semibold text-slate-700 text-sm">Durasi
                                Pengerjaan (Menit)</span></label>
                        <input type="number" min="10" id="input_durasi" name="durasi" value="{{ old('durasi') }}"
                            placeholder="Masukkan total menit ujian..."
                            class="input input-bordered w-full bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg text-sm transition"
                            required />
                    </div>

                    <div class="form-control w-full">
                        <label class="label py-1"><span class="label-text font-semibold text-slate-700 text-sm">Tanggal
                                Dibuka</span></label>
                        <input type="date" id="input_tanggal_mulai" name="tanggal_mulai"
                            value="{{ old('tanggal_mulai') }}"
                            class="input input-bordered w-full bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg text-sm transition"
                            required />
                    </div>

                    <div class="form-control w-full">
                        <label class="label py-1"><span class="label-text font-semibold text-slate-700 text-sm">Tanggal
                                Ditutup</span></label>
                        <input type="date" id="input_tanggal_selesai" name="tanggal_selesai"
                            value="{{ old('tanggal_selesai') }}"
                            class="input input-bordered w-full bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg text-sm transition"
                            required />
                    </div>

                    <div class="form-control w-full col-span-1 sm:col-span-2">
                        <label class="label py-1">
                            <span class="label-text font-semibold text-slate-700 text-sm">Target Kelas Terbuka</span>
                        </label>
                        <div
                            class="grid grid-cols-2 gap-2 p-3 bg-slate-50 border border-slate-200 rounded-lg max-h-40 overflow-y-auto">
                            @foreach($daftarKelas as $kelas)
                                <label
                                    class="flex items-center gap-2.5 p-1.5 hover:bg-white rounded-md cursor-pointer transition">
                                    <input type="checkbox" name="kelas_ids[]" value="{{ $kelas->id }}"
                                        class="checkbox checkbox-sm checkbox-primary rounded bg-white border-slate-300 class-checkbox"
                                        {{ is_array(old('kelas_ids')) && in_array($kelas->id, old('kelas_ids')) ? 'checked' : '' }} />
                                    <span class="text-sm text-slate-600 font-medium">{{ $kelas->nama }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Paket ujian hanya akan muncul pada beranda siswa yang
                            berada di dalam kelas terpilih.</p>
                    </div>

                    {{-- Toggle: Acak Soal & Acak Jawaban --}}
                    <div class="form-control w-full col-span-1 sm:col-span-2 border-t border-slate-100 pt-4">
                        <label class="label py-1 pb-2">
                            <span class="label-text font-semibold text-slate-700 text-sm">Pengaturan Pengacakan</span>
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="cursor-pointer flex items-center gap-3 p-3 bg-slate-50 border border-slate-200 rounded-lg hover:bg-indigo-50 hover:border-indigo-200 transition">
                                <input type="checkbox" id="input_acak_soal" name="acak_soal" value="1"
                                    class="toggle toggle-primary toggle-sm"
                                    {{ old('acak_soal') ? 'checked' : '' }} />
                                <div>
                                    <span class="text-sm font-semibold text-slate-700 block">Acak Soal</span>
                                    <span class="text-xs text-slate-400">Urutan soal berbeda tiap siswa</span>
                                </div>
                            </label>
                            <label class="cursor-pointer flex items-center gap-3 p-3 bg-slate-50 border border-slate-200 rounded-lg hover:bg-indigo-50 hover:border-indigo-200 transition">
                                <input type="checkbox" id="input_acak_jawaban" name="acak_jawaban" value="1"
                                    class="toggle toggle-primary toggle-sm"
                                    {{ old('acak_jawaban') ? 'checked' : '' }} />
                                <div>
                                    <span class="text-sm font-semibold text-slate-700 block">Acak Jawaban</span>
                                    <span class="text-xs text-slate-400">Opsi A/B/C/D diacak tiap siswa</span>
                                </div>
                            </label>
                        </div>
                    </div>

                </div>

                <div class="modal-action flex justify-end gap-2 pt-4 border-t border-slate-100 mt-6">
                    <button type="button" onclick="closeModal()"
                        class="btn bg-slate-100 hover:bg-slate-200 border-none text-slate-600 font-medium normal-case px-5 rounded-lg shadow-none transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="btn bg-indigo-600 hover:bg-indigo-700 border-none text-white font-semibold normal-case px-6 rounded-lg shadow-sm transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </dialog>

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
            document.getElementById('input_id').value             = paket.id;
            document.getElementById('input_name').value           = paket.nama;
            document.getElementById('input_mapel').value          = paket.mata_pelajaran_id;
            document.getElementById('input_durasi').value         = paket.durasi;
            document.getElementById('input_tanggal_mulai').value  = tglMulai;
            document.getElementById('input_tanggal_selesai').value = tglSelesai;

            // Bind toggle acak_soal dan acak_jawaban (nilai dari DB: 1 = true, 0 = false)
            document.getElementById('input_acak_soal').checked    = paket.acak_soal == 1;
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