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

            @foreach ($daftarKelas as $kelas)
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
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')" class="inline">
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
                form.action = `{{ route('admin.kelas.update', $kelas->id) }}`
                method.innerHTML = `@method('PUT')`

                document.getElementById('input_name').value = kelas.nama
                document.getElementById('input_kode').value = kelas.kode
                modal.showModal()
            }

            document.addEventListener("DOMContentLoaded", function () {
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
        </script>
</x-app-layout>