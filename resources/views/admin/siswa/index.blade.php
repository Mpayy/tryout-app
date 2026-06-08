<x-app-layout>
    <div class="space-y-6">
        <x-data-tabel>
            <x-slot name="page">Siswa</x-slot>
            <x-slot name="header">
                <th class="w-12 text-center">No</th>
                <th>Nama Siswa</th>
                <th>NISN & Role</th>
                <th class="text-center">Kelas</th>
                <th class="w-28 text-center">Aksi</th>
            </x-slot>

            @foreach ($daftarSiswa as $siswa)
                <tr class="hover align-middle">
                    <td class="text-center font-medium text-base-content/60">
                        {{ ($daftarSiswa->currentPage() - 1) * $daftarSiswa->perPage() + $loop->iteration }}
                    </td>

                    <td>
                        <div class="flex items-center gap-3">
                            <div class="avatar placeholder">
                                <div
                                    class="bg-secondary text-secondary-content w-9 rounded-full font-bold text-sm uppercase">
                                    {{ substr($siswa->name, 0, 2) }}
                                </div>
                            </div>
                            <div>
                                <div class="font-bold text-base-content">{{ $siswa->name }}</div>
                                <div class="text-xs text-base-content/50">{{ $siswa->email }}</div>
                            </div>
                        </div>
                    </td>

                    <td>
                        <div class="flex flex-col gap-1 items-start">
                            <span class="text-sm font-semibold font-mono text-base-content/80">
                                {{ $siswa->profileSiswa->nis ?? '-' }}
                            </span>
                            <span class="badge badge-neutral badge-xs uppercase font-bold tracking-wider px-1.5 rounded">
                                {{ $siswa->roles->pluck('name')->implode(', ') }}
                            </span>
                        </div>
                    </td>

                    <td class="text-center">
                        @if ($siswa->profileSiswa && $siswa->profileSiswa->kelas)
                            <span class="badge badge-primary badge-sm font-semibold px-3">
                                {{ $siswa->profileSiswa->kelas->nama }}
                            </span>
                        @else
                            <span class="text-base-content/30 text-xs italic">Belum masuk kelas</span>
                        @endif
                    </td>

                    <td class="text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <button type="button"
                                class="btn btn-xs btn-warning text-warning-content shadow-none font-medium px-2.5"
                                onclick="openEditModal({{ $siswa }})">
                                Edit
                            </button>

                            <form action="{{ route('admin.siswa.destroy', $siswa) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="btn btn-xs btn-outline btn-error shadow-none font-medium px-2.5">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-data-tabel>

        {{ $daftarSiswa->links() }}
    </div>
    <x-form-modal>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="form-control w-full col-span-1 sm:col-span-2">
                <label class="floating-label">
                    <span class="label-text font-semibold text-slate-700 text-sm">Masukan Nama</span>
                    <input type="text" id="input_name" name="name" value="{{ old('name') }}" placeholder="Masukan Nama"
                        class="input input-primary w-full" />
                </label>
            </div>

            <div class="form-control w-full col-span-1 sm:col-span-2">
                <label class="floating-label">
                    <span class="label-text font-semibold text-slate-700 text-sm">mail@site.com</span>
                    <input type="email" id="input_email" name="email" value="{{ old('email') }}"
                        placeholder="mail@site.com" class="input input-primary w-full" required />
                </label>
            </div>

            <div class="form-control w-full">
                <label class="floating-label">
                    <span class="label-text font-semibold text-slate-700 text-sm">NIS</span>
                    <input type="number" id="input_nis" name="nis" value="{{ old('nis') }}" placeholder="NIS"
                        class="input input-primary w-full" />
                </label>
            </div>

            <input type="hidden" name="role" value="siswa">

            <div class="form-control w-full">
                <label class="floating-label">
                    <span class="label-text font-semibold text-slate-700 text-sm">Pilih Kelas</span>
                    <select id="input_kelas" name="kelas" class="select select-primary w-full">
                        <option value="" disabled selected></option>
                        @foreach ($daftarKelas as $kelas)
                            <option value="{{ $kelas->id }}">{{ ucfirst($kelas->nama) }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
        </div>

        <div class="form-control col-span-1 sm:col-span-2">
            <label class="floating-label">
                <span>Kata Sandi</span>
                <input type="password" id="input_password" name="password" placeholder="Kata Sandi"
                    class="input input-primary w-full" />
            </label>
            <div class="label py-1 hidden" id="password_hint">
                <span class="label-text-alt text-error text-xs flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="size-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                    *Kosongkan jika tidak ingin mengubah sandi
                </span>
            </div>
        </div>
    </x-form-modal>

    <script>
        const modal = document.getElementById('modal')
        const form = document.getElementById('form')
        const modalTitle = document.getElementById('modal_title')
        const method = document.getElementById('method')

        function openCreateModal() {
            modalTitle.innerText = 'Tambah Siswa'
            form.action = `{{ route('admin.siswa.store') }}`
            method.innerHTML = ''
            form.reset()
            document.getElementById('password_hint').classList.add('hidden');
            modal.showModal()
        }

        function openEditModal(siswa) {
            modalTitle.innerText = 'Edit Siswa (' + siswa.name + ')'
            form.action = `{{ url('admin/siswa') }}/` + siswa.id
            method.innerHTML = `@method('PUT')`

            document.getElementById('input_name').value = siswa.name
            document.getElementById('input_email').value = siswa.email
            document.getElementById('input_nis').value = siswa.profile_siswa?.nis || ''
            document.getElementById('input_kelas').value = siswa.profile_siswa?.kelas?.id || ''
            document.getElementById('input_password').required = false
            document.getElementById('password_hint').classList.remove('hidden')

            modal.showModal()
        }

        document.addEventListener("DOMContentLoaded", function () {
            @if ($errors->any())
                const modal = document.getElementById('modal');
                const modalTitle = document.getElementById('modal_title');
                const form = document.getElementById('form');
                const methodField = document.getElementById('method');

                @if (old('_method') == 'PUT')
                    modalTitle.innerText = "Modal Form: Edit User";
                    form.action = "{{ url('admin/siswa') }}/" + "{{ old('id') }}";
                    methodField.innerHTML = `@method('PUT')`;
                @else
                    modalTitle.innerText = "Modal Form: Tambah User Baru";
                    form.action = "{{ route('admin.siswa.store') }}";
                    methodField.innerHTML = "";
                @endif

                modal.showModal();
            @endif
        });

        function closeUserModal() {
            modal.close();
        }
    </script>
</x-app-layout>