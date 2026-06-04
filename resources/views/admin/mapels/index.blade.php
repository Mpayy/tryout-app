<x-app-layout>
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-800">Manajemen Mata Pelajaran</h1>
            </div>
            <div>
                <x-primary-button onclick="openCreateModal()">
                    Tambah
                </x-primary-button>
            </div>
        </div>

        <x-data-tabel>
            <x-slot name="header">
                <th>No</th>
                <th>Nama Mata Pelajaran</th>
                <th>Kode</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </x-slot>

            @foreach ($mapels as $mapel)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $mapel->nama }}</td>
                    <td>
                        <div class="badge badge-info badge-soft">
                            {{ $mapel->kode }}
                        </div>
                    </td>
                    <td>{{ $mapel->deskripsi }}</td>
                    <td>
                        <div class="flex items-center gap-1.5">
                            <button onclick="openEditModal({{ $mapel }})"
                                class="btn btn-primary btn-sm btn-soft">Edit</button>
                            <form action="{{ route('admin.mapels.destroy', $mapel) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-error btn-sm btn-soft">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-data-tabel>
    </div>
    <x-form-modal>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <div class="form-control w-full col-span-1 sm:col-span-2">
                <label class="label"><span class="label-text font-semibold text-slate-700 text-sm">Nama
                        Mata Pelajaran</span></label>
                <input type="text" id="input_name" name="nama" value="{{ old('nama') }}"
                    placeholder="Masukkan nama mata pelajaran..." class="input input-primary w-full" />
            </div>

            <div class="form-control w-full">
                <label class="label"><span class="label-text font-semibold text-slate-700 text-sm">Kode
                        Mata Pelajaran</span></label>
                <input type="text" id="input_kode" name="kode" value="{{ old('kode') }}" placeholder="Contoh: MTK"
                    class="input input-primary w-full" required />
            </div>

            <div class="form-control w-full">
                <label class="label"><span class="label-text font-semibold text-slate-700 text-sm">Deskripsi
                        Mata Pelajaran</span></label>
                <input type="text" id="input_deskripsi" name="deskripsi" value="{{ old('deskripsi') }}"
                    placeholder="Masukkan deskripsi mata pelajaran..." class="input input-primary w-full" />
            </div>
        </div>
    </x-form-modal>

    <script>
        const modal = document.getElementById('modal')
        const form = document.getElementById('form')
        const modalTitle = document.getElementById('modal_title')
        const method = document.getElementById('method')


        function openCreateModal() {
            modalTitle.innerText = 'Tambah Mapel'
            form.action = `{{ route('admin.mapels.store') }}`
            method.innerHTML = ''
            form.reset()
            modal.showModal()
        }

        function openEditModal(mapel) {
            modalTitle.innerText = 'Edit Mapel'
            form.action = `{{ route('admin.mapels.update', $mapel->id) }}`
            method.innerHTML = `@method('PUT')`

            document.getElementById('input_name').value = mapel.nama
            document.getElementById('input_kode').value = mapel.kode
            document.getElementById('input_deskripsi').value = mapel.deskripsi
            modal.showModal()
        }

        document.addEventListener("DOMContentLoaded", function () {
            @if ($errors->any())
                const modal = document.getElementById('modal');
                const modalTitle = document.getElementById('modal_title');
                const form = document.getElementById('form');
                const methodField = document.getElementById('method');

                @if (old('_method') == 'PUT')
                    modalTitle.innerText = 'Edit Mapel';
                    form.action = `{{ route('admin.mapels.update', old('id')) }}`;
                    methodField.innerHTML = `@method('PUT')`;
                @else
                    modalTitle.innerText = 'Tambah Mapel';
                    form.action = `{{ route('admin.mapels.store') }}`;
                    methodField.innerHTML = "";
                @endif

                modal.showModal();
            @endif
        });
    </script>
</x-app-layout>