<x-app-layout>
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-800">Manajemen Kelas</h1>
                <p class="text-sm text-slate-500">Kelola daftar kelas.</p>
            </div>
            <div>
                <button onclick="openCreateModal()"
                    class="btn bg-indigo-600 hover:bg-indigo-700 border-none text-white shadow-sm font-semibold normal-case gap-2 px-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Kelas
                </button>
            </div>
        </div>

        <div class="card bg-white border border-slate-200/80 shadow-sm rounded-xl overflow-hidden">
            <div class="p-6 space-y-6">

                {{-- <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="relative w-full sm:w-80">
                        <span
                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.603 10.603Z" />
                            </svg>
                        </span>
                        <input type="text" placeholder="Cari nama guru..."
                            class="input input-bordered w-full pl-10 bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                    </div>

                    <div class="text-sm text-slate-500 font-medium">
                        Menampilkan <span class="text-slate-800 font-semibold">{{ $daftarGuru->count() }}</span> guru
                    </div>
                </div> --}}

                <div class="overflow-x-auto rounded-lg border border-slate-100">
                    <table class="table w-full text-slate-700">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-200 text-slate-600 font-semibold text-sm">
                                <th class="w-16 text-center">#</th>
                                <th>Nama Kelas</th>
                                <th>Kode</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($daftarKelas as $kelas)
                                <tr class="hover:bg-slate-50/50 transition duration-150">
                                    <td class="text-center font-medium text-slate-500">{{ $loop->iteration }}</td>
                                    <td class="font-semibold text-slate-800">{{ $kelas->nama }}</td>
                                    <td class="font-medium">{{ $kelas->kode }}</td>
                                    <td class="text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button onclick="openEditModal({{ $kelas }})"
                                                class="btn btn-sm bg-indigo-50 hover:bg-indigo-100 border-none text-indigo-700 normal-case font-medium px-3 shadow-none">Edit</button>
                                            <form action="{{ route('admin.kelas.destroy', $kelas) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm bg-rose-50 hover:bg-rose-100 border-none text-rose-700 normal-case font-medium px-3 shadow-none">Hapus</button>
                                            </form>
                                            <a href="{{ route('admin.kelas.anggota', $kelas) }}"
                                                class="btn btn-sm bg-emerald-50 hover:bg-emerald-100 border-none text-emerald-700 normal-case font-medium px-3 shadow-none">Anggota</a>
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
        <div class="modal-box bg-white border border-slate-200/80 shadow-xl max-w-lg p-6 rounded-xl text-slate-700">

            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                <div>
                    <h3 id="modal_title" class="text-xl font-bold text-slate-800">Tambah Mata Pelajaran Baru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Isi data mata pelajaran dengan lengkap untuk
                        mengonfigurasi data mata pelajaran.</p>
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

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div class="form-control w-full col-span-1 sm:col-span-2">
                        <label class="label py-1"><span class="label-text font-semibold text-slate-700 text-sm">Nama
                                Kelas</span></label>
                        <input type="text" id="input_name" name="nama" value="{{ old('nama') }}"
                            placeholder="Masukkan nama kelas..."
                            class="input input-bordered w-full bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg text-sm transition" 
                            required/>
                    </div>

                    <div class="form-control w-full col-span-2">
                        <label class="label py-1"><span class="label-text font-semibold text-slate-700 text-sm">Kode
                                Kelas</span></label>
                        <input type="text" id="input_kode" name="kode" value="{{ old('kode') }}"
                            placeholder="Contoh: X MIPA 1"
                            class="input input-bordered w-full bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg text-sm transition"
                            required />
                    </div>
                </div>

                <div class="modal-action flex justify-end gap-2 pt-4 border-t border-slate-100 mt-6">
                    <button type="button" onclick="closeModal()"
                        class="btn bg-slate-100 hover:bg-slate-200 border-none text-slate-600 font-medium normal-case px-5 rounded-lg shadow-none transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="btn bg-indigo-600 hover:bg-indigo-700 border-none text-white font-semibold normal-case px-6 rounded-lg shadow-sm transition">
                        Simpan User
                    </button>
                </div>
            </form>
        </div>
    </dialog>

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