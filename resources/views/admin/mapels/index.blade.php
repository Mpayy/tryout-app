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

            @forelse ($mapels as $mapel)
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

                            <form action="{{ route('admin.mapels.destroy', $mapel) }}" method="POST" class="inline form-delete">
                                @csrf
                                @method('DELETE')
                                <button type="submit" data-name="{{ $mapel->nama }}"
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
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-base-content/70">Belum ada Mapel</p>
                                <p class="text-sm text-base-content/40 mt-0.5">Klik "Tambah Mapel" untuk menambahkan
                                    Mapel</p>
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
                    <span class="label-text font-semibold text-slate-700 text-sm">Mata Pelajaran</span>
                    <input type="text" id="input_name" name="nama" value="{{ old('nama') }}"
                        placeholder="Mata Pelajaran" class="input input-primary w-full" />
                </label>
                <x-input-error :messages="$errors->get('nama')" />
            </div>

            <div class="form-control w-full col-span-1 sm:col-span-2">
                <label class="floating-label">
                    <span class="label-text font-semibold text-slate-700 text-sm">Kode</span>
                    <input type="text" id="input_kode" name="kode" value="{{ old('kode') }}" placeholder="Kode"
                        class="input input-primary w-full" required />
                </label>
                <x-input-error :messages="$errors->get('kode')" />
            </div>

            <div class="form-control w-full col-span-1 sm:col-span-2">
                <label class="floating-label">
                    <span class="label-text font-semibold text-slate-700 text-sm">Deskripsi</span>
                    <input type="text" id="input_deskripsi" name="deskripsi" value="{{ old('deskripsi') }}"
                        placeholder="Deskripsi" class="input input-primary w-full" />
                </label>
                <x-input-error :messages="$errors->get('deskripsi')" />
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
            form.action = `{{ url('admin/mapels') }}/${mapel.id}`
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

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.form-delete').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const namaMapel = this.querySelector('button[data-name]').getAttribute('data-name');
                    Swal.fire({
                        title: "Apakah anda yakin?",
                        text: `Data mapel "${namaMapel}" akan dihapus permanen dari sistem!`,
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