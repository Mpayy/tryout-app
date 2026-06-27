<x-app-layout>
    <div class="space-y-6">
        <x-data-tabel>
            <x-slot name="page">Paket Ujian</x-slot>

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
                <tr class="hover align-middle">
                    <td class="text-center font-medium text-base-content/50">{{ $loop->iteration }}</td>

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
                                    <span class="badge badge-neutral badge-xs rounded">{{ $k->nama }}</span>
                                @empty
                                    <span class="text-base-content/40 text-xs italic">Belum ada kelas</span>
                                @endforelse
                            </div>
                        </div>
                    </td>

                    <td class="text-center">
                        {{-- PERUBAHAN: tambah dot indicator untuk mempercepat pembacaan status --}}
                        @if ($paket->status == 'draft')
                            <span class="badge badge-warning badge-sm font-semibold gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-current inline-block"></span>Draft
                            </span>
                        @elseif ($paket->status == 'aktif')
                            <span class="badge badge-success badge-sm font-semibold gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-current inline-block"></span>Aktif
                            </span>
                        @else
                            <span class="badge badge-neutral badge-sm font-semibold gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-current inline-block"></span>Selesai
                            </span>
                        @endif
                    </td>

                    <td class="text-xs text-base-content/70">
                        <div class="flex flex-col gap-0.5">
                            <span>Mulai: <b
                                    class="text-base-content">{{ \Carbon\Carbon::parse($paket->tanggal_mulai)->format('d M Y') }}</b></span>
                            <span>Selesai: <b
                                    class="text-base-content">{{ \Carbon\Carbon::parse($paket->tanggal_selesai)->format('d M Y') }}</b></span>
                        </div>
                    </td>

                    <td class="text-center">
                        <div class="text-sm font-bold text-base-content">{{ $paket->soal_count }} Soal</div>
                        <div class="text-xs text-base-content/50">{{ $paket->durasi }} Menit</div>
                    </td>

                    <td class="text-center">
                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('guru.paket-ujian.show', $paket) }}"
                                class="btn btn-xs btn-primary shadow-none font-medium">
                                Kelola Soal
                            </a>
                            <button onclick="openEditModal({{ $paket->load('kelas') }})"
                                class="btn btn-xs btn-warning text-warning-content shadow-none font-medium">
                                Edit
                            </button>
                            <form action="{{ route('guru.paket-ujian.destroy', $paket) }}" method="POST"
                                onsubmit="return confirm('Hapus paket ujian ini?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-outline btn-error shadow-none font-medium">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

            @empty
                {{-- PERUBAHAN: Ganti semua bg-slate-* / text-slate-* → pakai DaisyUI variables --}}
                <tr>
                    <td colspan="7">
                        <div class="flex flex-col items-center gap-3 py-16 text-center">
                            <div
                                class="w-16 h-16 rounded-full bg-base-200 border border-base-300 flex items-center justify-center">
                                <svg class="w-7 h-7 text-base-content/30" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-base-content/70">Belum ada Paket Ujian</p>
                                <p class="text-sm text-base-content/40 mt-0.5">Klik "Tambah Paket Ujian" untuk membuat paket
                                    baru.</p>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-data-tabel>
        {{ $paketUjian->links() }}
    </div>

    <x-form-modal>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <input type="hidden" id="input_id" name="id" value="{{ old('id') }}" />

            <div class="form-control col-span-1 sm:col-span-2">
                <label class="floating-label">
                    <span class="label-text font-semibold text-base-content/70 text-sm">Nama Paket Ujian</span>
                    <input type="text" id="input_name" name="nama" value="{{ old('nama') }}"
                        placeholder="Contoh: Penilaian Tengah Semester Ganjil" class="input input-primary w-full"
                        required />
                </label>
                <x-input-error :messages="$errors->get('nama')" />
            </div>

            <div class="form-control">
                <label class="floating-label">
                    <span class="label-text font-semibold text-base-content/70 text-sm">Mata Pelajaran</span>
                    <select name="mata_pelajaran_id" id="input_mapel" class="select select-primary w-full" required>
                        <option value="" disabled selected>Pilih Mata Pelajaran</option>
                        @foreach ($mataPelajaranGuru as $mapel)
                            <option value="{{ $mapel->id }}" {{ old('mata_pelajaran_id') == $mapel->id ? 'selected' : '' }}>
                                {{ $mapel->nama }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <x-input-error :messages="$errors->get('mata_pelajaran_id')" />
            </div>

            <div class="form-control">
                <label class="floating-label">
                    <span class="label-text font-semibold text-base-content/70 text-sm">Durasi (Menit)</span>
                    <input type="number" min="10" id="input_durasi" name="durasi" value="{{ old('durasi') }}"
                        placeholder="Contoh: 90" class="input input-primary w-full" required />
                </label>
                <x-input-error :messages="$errors->get('durasi')" />
            </div>

            <div class="form-control">
                <label class="floating-label">
                    <span class="label-text font-semibold text-base-content/70 text-sm">Tanggal Dibuka</span>
                    <input type="date" id="input_tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                        class="input input-primary w-full" required />
                </label>
                <x-input-error :messages="$errors->get('tanggal_mulai')" />
            </div>

            <div class="form-control">
                <label class="floating-label">
                    <span class="label-text font-semibold text-base-content/70 text-sm">Tanggal Ditutup</span>
                    <input type="date" id="input_tanggal_selesai" name="tanggal_selesai"
                        value="{{ old('tanggal_selesai') }}" class="input input-primary w-full" required />
                </label>
                <x-input-error :messages="$errors->get('tanggal_selesai')" />
            </div>

            <div class="form-control col-span-1 sm:col-span-2">
                <label class="label py-1 pb-2">
                    <span class="label-text font-semibold text-base-content/70 text-sm">Target Kelas</span>
                </label>
                <div
                    class="grid grid-cols-2 gap-2 p-3 bg-base-200/40 border border-base-200 rounded-xl max-h-40 overflow-y-auto">
                    @foreach($daftarKelas as $kelas)
                        <label
                            class="flex items-center gap-2.5 p-2 border border-transparent hover:border-base-300 hover:bg-base-100 rounded-lg cursor-pointer transition duration-150">
                            <input type="checkbox" name="kelas_ids[]" value="{{ $kelas->id }}"
                                class="checkbox checkbox-sm checkbox-primary rounded-md class-checkbox" {{
                                is_array(old('kelas_ids')) && in_array($kelas->id, old('kelas_ids')) ? 'checked' : '' }}/>
                            <span class="text-sm text-base-content/80 font-medium">{{ $kelas->nama }}</span>
                        </label>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('kelas_ids')" />
            </div>

            <div class="form-control col-span-1 sm:col-span-2 border-t border-base-200 pt-4">
                <p class="text-sm font-semibold text-base-content/70 mb-3">Pengaturan Pengacakan</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label
                        class="cursor-pointer flex items-center gap-4 p-3.5 bg-base-200/40 border border-base-200 rounded-xl hover:bg-base-200/80 transition duration-150 group">
                        <input type="checkbox" id="input_acak_soal" name="acak_soal" value="1"
                            class="toggle toggle-primary toggle-sm" {{ old('acak_soal') ? 'checked' : '' }} />
                        <div>
                            <span class="text-sm font-bold text-base-content block">Acak Soal</span>
                        </div>
                    </label>
                    <label
                        class="cursor-pointer flex items-center gap-4 p-3.5 bg-base-200/40 border border-base-200 rounded-xl hover:bg-base-200/80 transition duration-150 group">
                        <input type="checkbox" id="input_acak_jawaban" name="acak_jawaban" value="1"
                            class="toggle toggle-primary toggle-sm" {{ old('acak_jawaban') ? 'checked' : '' }} />
                        <div>
                            <span class="text-sm font-bold text-base-content block">Acak Jawaban</span>
                        </div>
                    </label>
                </div>
                <x-input-error :messages="$errors->get('acak_soal')" />
                <x-input-error :messages="$errors->get('acak_jawaban')" />
            </div>
        </div>
    </x-form-modal>

    <script>
        const modal = document.getElementById('modal');
        const form = document.getElementById('form');
        const modalTitle = document.getElementById('modal_title');
        const method = document.getElementById('method');
        const checkBoxes = document.querySelectorAll('.class-checkbox');

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
            modalTitle.innerText = 'Edit Paket Ujian';
            let updateUrl = `{{ route('guru.paket-ujian.update', ':id') }}`;
            form.action = updateUrl.replace(':id', paket.id);
            method.innerHTML = `@method('PUT')`;
            form.reset();
            resetCheckboxes();

            const tglMulai = paket.tanggal_mulai ? paket.tanggal_mulai.slice(0, 10) : '';
            const tglSelesai = paket.tanggal_selesai ? paket.tanggal_selesai.slice(0, 10) : '';

            document.getElementById('input_id').value = paket.id;
            document.getElementById('input_name').value = paket.nama;
            document.getElementById('input_mapel').value = paket.mata_pelajaran_id;
            document.getElementById('input_durasi').value = paket.durasi;
            document.getElementById('input_tanggal_mulai').value = tglMulai;
            document.getElementById('input_tanggal_selesai').value = tglSelesai;
            document.getElementById('input_acak_soal').checked = paket.acak_soal == 1;
            document.getElementById('input_acak_jawaban').checked = paket.acak_jawaban == 1;

            if (paket.kelas && paket.kelas.length > 0) {
                const targetKelasIds = paket.kelas.map(k => k.id);
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

        document.addEventListener("DOMContentLoaded", function () {
            @if ($errors->any())
                @if (old('_method') == 'PUT')
                    modalTitle.innerText = 'Edit Paket Ujian';
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