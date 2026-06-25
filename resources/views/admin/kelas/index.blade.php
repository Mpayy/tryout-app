<x-app-layout>
    <div class="space-y-6">
        <x-data-tabel>
            <x-slot name="page">Kelas</x-slot>
            <x-slot name="header">
                <th class="w-12 text-center">No</th>
                <th>Nama Kelas</th>
                <th class="w-32 text-center">Kode Kelas</th>
                <th class="w-32 text-center">Jumlah Anggota</th>
                <th class="w-48 text-center">Aksi</th>
            </x-slot>

            @forelse ($daftarKelas as $kelas)
                <tr class="hover align-middle">
                    <td class="text-center font-medium text-base-content/60">
                        {{ $loop->iteration }}
                    </td>

                    <td>
                        <div class="font-bold text-base-content">{{ $kelas->nama }}</div>
                    </td>

                    <td class="text-center">
                        <span class="badge badge-neutral font-mono font-bold text-xs uppercase tracking-wider px-2.5">
                            {{ $kelas->kode }}
                        </span>
                    </td>

                    <td class="text-center">
                        <span class="badge badge-info font-mono font-bold text-xs uppercase tracking-wider px-2.5">
                            {{ $kelas->total_siswa }}
                        </span>
                    </td>

                    <td class="text-center">
                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('admin.kelas.anggota', $kelas) }}"
                                class="btn btn-xs btn-primary shadow-none font-medium">
                                Anggota
                            </a>

                            <button type="button"
                                class="btn btn-xs btn-warning text-warning-content shadow-none font-medium px-2.5"
                                onclick="openEditModal({{ $kelas }})">
                                Edit
                            </button>

                            <form action="{{ route('admin.kelas.destroy', $kelas) }}" method="POST"
                                class="inline form-delete">
                                @csrf
                                @method('DELETE')
                                <button type="submit" data-name="{{ $kelas->nama }}"
                                    class="btn btn-xs btn-outline btn-error shadow-none font-medium px-2.5">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <div class="flex flex-col items-center gap-3 py-16 text-center">
                            <div
                                class="w-16 h-16 rounded-full bg-base-200 border border-base-300 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" class="w-7 h-7 text-base-content/30">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-base-content/70">Belum ada Kelas</p>
                                <p class="text-sm text-base-content/40 mt-0.5">Klik "Tambah Kelas" untuk menambahkan
                                    Kelas</p>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-data-tabel>
    </div>

    <x-form-modal>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <div class="form-control w-full col-span-1 sm:col-span-2">
                <label class="floating-label">
                    <span class="label-text font-semibold text-slate-700 text-sm">Nama
                        Kelas</span>
                    <input type="text" id="input_name" name="nama" value="{{ old('nama') }}"
                        placeholder="Masukkan nama kelas..." class="input input-primary w-full" required />
                </label>
            </div>

            <div class="form-control w-full col-span-2">
                <label class="floating-label">
                    <span class="label-text font-semibold text-slate-700 text-sm">Kode
                        Kelas</span>
                    <input type="text" id="input_kode" name="kode" value="{{ old('kode') }}"
                        placeholder="Contoh: X MIPA 1" class="input input-primary w-full" required />
                </label>
            </div>
        </div>
    </x-form-modal>

    <script>
        const modal = document.getElementById('modal')
        const form = document.getElementById('form')
        const modalTitle = document.getElementById('modal_title')
        const method = document.getElementById('method')


        function openCreateModal() {
            modalTitle.innerText = 'Tambah Kelas'
            form.action = `{{ route('admin.kelas.store') }}`
            method.innerHTML = ''
            form.reset()
            modal.showModal()
        }

        function openEditModal(kelas) {
            modalTitle.innerText = 'Edit Kelas'
            form.action = `{{ url('admin/kelas') }}/${kelas.id}`
            method.innerHTML = `@method('PUT')`

            document.getElementById('input_name').value = kelas.nama
            document.getElementById('input_kode').value = kelas.kode
            modal.showModal()
        }

        document.addEventListener('DOMContentLoaded', function () {
            @if ($errors->any())
                const modal = document.getElementById('modal');
                const modalTitle = document.getElementById('modal_title');
                const form = document.getElementById('form');
                const methodField = document.getElementById('method');

                @if (old('_method') == 'PUT')
                    modalTitle.innerText = 'Edit Kelas';
                    form.action = `{{ route('admin.kelas.update', old('id')) }}`;
                    methodField.innerHTML = `@method('PUT')`;
                @else
                    modalTitle.innerText = 'Tambah Kelas';
                    form.action = `{{ route('admin.kelas.store') }}`;
                    methodField.innerHTML = "";
                @endif

                modal.showModal();
            @endif
                        });

        function closeModal() {
            modal.close();
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.form-delete').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const namaKelas = this.querySelector('button[data-name]').getAttribute('data-name');
                    Swal.fire({
                        title: "Apakah anda yakin?",
                        text: `Data kelas "${namaKelas}" akan dihapus permanen dari sistem!`,
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