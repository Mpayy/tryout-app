<x-app-layout>
    <div class="space-y-6">
        <x-data-tabel>
            <x-slot name="page">Mata Pelajaran</x-slot>
            <x-slot name="header">
                <th class="w-12 text-center">No</th>
                <th class="w-1/4">Nama Mata Pelajaran</th>
                <th class="w-28 text-center">Kode</th>
                <th>Deskripsi</th>
                <th class="w-28 text-center">Aksi</th>
            </x-slot>

            @foreach ($mapels as $mapel)
                <tr class="hover align-middle">
                    <td class="text-center font-medium text-base-content/60">
                        {{ $loop->iteration }}
                    </td>

                    <td>
                        <div class="font-bold text-base-content">{{ $mapel->nama }}</div>
                    </td>

                    <td class="text-center">
                        <span class="badge badge-neutral font-mono font-bold text-xs uppercase tracking-wider px-2">
                            {{ $mapel->kode }}
                        </span>
                    </td>

                    <td>
                        @if($mapel->deskripsi)
                            <div class="text-sm text-base-content/70 line-clamp-1 max-w-md" title="{{ $mapel->deskripsi }}">
                                {{ $mapel->deskripsi }}
                            </div>
                        @else
                            <span class="text-base-content/30 text-xs italic">Tidak ada deskripsi</span>
                        @endif
                    </td>

                    <td class="text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <button type="button"
                                class="btn btn-xs btn-warning text-warning-content shadow-none font-medium px-2.5"
                                onclick="openEditModal({{ $mapel }})">
                                Edit
                            </button>

                            <form action="{{ route('admin.mapels.destroy', $mapel) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')"
                                class="inline">
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

        {{ $mapels->links() }}
    </div>
    <x-form-modal>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="form-control w-full col-span-1 sm:col-span-2">
                <label class="floating-label">
                    <span class="label-text font-semibold text-slate-700 text-sm">Mata Pelajaran</span>
                    <input type="text" id="input_name" name="nama" value="{{ old('nama') }}"
                        placeholder="Mata Pelajaran" class="input input-primary w-full" />
                </label>
            </div>

            <div class="form-control w-full col-span-1 sm:col-span-2">
                <label class="floating-label">
                    <span class="label-text font-semibold text-slate-700 text-sm">Kode</span>
                    <input type="text" id="input_kode" name="kode" value="{{ old('kode') }}" placeholder="Kode"
                        class="input input-primary w-full" required />
                </label>
            </div>

            <div class="form-control w-full col-span-1 sm:col-span-2">
                <label class="floating-label">
                    <span class="label-text font-semibold text-slate-700 text-sm">Deskripsi</span>
                    <input type="text" id="input_deskripsi" name="deskripsi" value="{{ old('deskripsi') }}"
                        placeholder="Deskripsi" class="input input-primary w-full" />
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

        function closeModal() { 
            modal.close(); 
        }
    </script>
</x-app-layout>