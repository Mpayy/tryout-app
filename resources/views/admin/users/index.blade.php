<x-app-layout>
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-800">Manajemen Guru</h1>
                <p class="text-sm text-slate-500">Kelola daftar guru.</p>
            </div>
            <div>
                <button onclick="openCreateModal()"
                    class="btn bg-indigo-600 hover:bg-indigo-700 border-none text-white shadow-sm font-semibold normal-case gap-2 px-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Guru
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
                                <th>Nama Guru</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Role</th>
                                <th class="text-center">NIP</th>
                                <th class="text-center">Pelajaran</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($daftarGuru as $guru)
                                <tr class="hover:bg-slate-50/50 transition duration-150">
                                    <td class="text-center font-medium text-slate-500">{{ $loop->iteration }}</td>
                                    <td class="font-semibold text-slate-800">{{ $guru->name }}</td>
                                    <td class="text-center font-medium">{{ $guru->email }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 font-medium text-xs rounded-full">
                                            {{ $guru->roles->pluck('name')->implode(', ') }}
                                        </span>
                                    </td>
                                    <td class="text-center font-medium">{{ $guru->profileGuru->nip ?? '-' }}</td>
                                    <td class="text-center font-medium">
                                        @if ($guru->profileGuru && $guru->profileGuru->mataPelajarans->isNotEmpty())
                                            @foreach ($guru->profileGuru->mataPelajarans as $mapel)
                                                <span
                                                    class="badge bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-1 font-medium text-xs rounded-full">
                                                    {{ $mapel->nama }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button onclick="openEditModal({{ $guru }})"
                                                class="btn btn-sm bg-indigo-50 hover:bg-indigo-100 border-none text-indigo-700 normal-case font-medium px-3 shadow-none">Edit</button>
                                            <form action="{{ route('admin.users.destroy', $guru) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm bg-rose-50 hover:bg-rose-100 border-none text-rose-700 normal-case font-medium px-3 shadow-none">Hapus</button>
                                            </form>
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
                    <h3 id="modal_title" class="text-xl font-bold text-slate-800">Tambah Guru Baru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Isi data akun dengan lengkap untuk mengonfigurasi hak akses
                        pengguna.</p>
                </div>
                <button type="button" onclick="closeUserModal()"
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
                                Lengkap</span></label>
                        <input type="text" id="input_name" name="name" value="{{ old('name') }}"
                            placeholder="Masukkan nama lengkap beserta gelar..."
                            class="input input-bordered w-full bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg text-sm transition" />
                    </div>

                    <div class="form-control w-full">
                        <label class="label py-1"><span class="label-text font-semibold text-slate-700 text-sm">Email
                                Address</span></label>
                        <input type="email" id="input_email" name="email" value="{{ old('email') }}"
                            placeholder="contoh@gmail.com"
                            class="input input-bordered w-full bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg text-sm transition"
                            required />
                    </div>

                    <div class="form-control w-full">
                        <label class="label py-1"><span class="label-text font-semibold text-slate-700 text-sm">NIP /
                                Nomor Induk</span></label>
                        <input type="number" id="input_nip" name="nip" value="{{ old('nip') }}"
                            placeholder="199503212022031002"
                            class="input input-bordered w-full bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg text-sm transition [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" />
                    </div>

                    <div class="form-control w-full">
                        <label class="label py-1"><span class="label-text font-semibold text-slate-700 text-sm">Pilih
                                Role</span></label>
                        <select id="input_role" name="role"
                            class="select select-bordered w-full bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg text-sm font-normal transition"
                            required>
                            <option value="" disabled selected>-- Pilih Role --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-control w-full">
                        <label class="label py-1"><span class="label-text font-semibold text-slate-700 text-sm">Mata
                                Pelajaran</span></label>
                        <div class="flex gap-2">
                            <select id="select_mapel" name="mapel"
                                class="select select-bordered w-full bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg text-sm font-normal transition">
                                <option value="" disabled selected>-- Pilih Mata Pelajaran --</option>
                                @foreach ($mapels as $mapel)
                                    <option value="{{ $mapel->id }}">{{ ucfirst($mapel->nama) }}</option>
                                @endforeach
                            </select>

                            <button type="button" id="btn_tambah_mapel"
                                class="btn bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 font-medium text-sm transition">
                                +
                            </button>
                        </div>
                        <div id="wrapper_mapel_terpilih" class="mt-3 space-y-2">
                        </div>
                    </div>

                    <div class="form-control w-full col-span-1 sm:col-span-2">
                        <label class="label py-1">
                            <span class="label-text font-semibold text-slate-700 text-sm">Kata Sandi</span>
                            <span id="password_hint" class="label-text-alt text-amber-600 font-medium hidden">*Kosongkan
                                jika tidak ingin mengubah sandi</span>
                        </label>
                        <input type="password" id="input_password" name="password" placeholder="••••••••"
                            class="input input-bordered w-full bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg text-sm transition" />
                    </div>

                </div>

                <div class="modal-action flex justify-end gap-2 pt-4 border-t border-slate-100 mt-6">
                    <button type="button" onclick="closeUserModal()"
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

        let selectedMapelIds = [];


        function openCreateModal() {
            modalTitle.innerText = 'Tambah Guru'
            form.action = `{{ route('admin.users.store') }}`
            method.innerHTML = ''
            form.reset()
            modal.showModal()
        }

        function openEditModal(guru) {
            modalTitle.innerText = 'Edit Guru (' + guru.name + ')'
            form.action = `/admin/users/` + guru.id;
            method.innerHTML = `@method('PUT')`

            document.getElementById('input_name').value = guru.name
            document.getElementById('input_email').value = guru.email
            document.getElementById('input_nip').value = guru.profile_guru?.nip || ''
            document.getElementById('input_role').value = guru.roles[0].name || ''
            document.getElementById('input_password').required = false

            const wrapperMapel = document.getElementById('wrapper_mapel_terpilih');

            // 1. Bersihkan dulu list mapel sisa klik/edit dari guru sebelumnya
            wrapperMapel.innerHTML = '';

            // Ambil array pelacak ID global dari script sebelumnya (pastikan variabel ini bisa diakses ya)
            selectedMapelIds = [];

            // 2. Cek apakah guru ini punya profile dan punya mata pelajaran
            if (guru.profile_guru && guru.profile_guru.mata_pelajarans) {

                // Loop setiap mapel yang dimiliki guru ini
                guru.profile_guru.mata_pelajarans.forEach(mapel => {

                    // Catat ID-nya ke array pelacak agar tidak bisa di-double tambah lewat dropdown
                    selectedMapelIds.push(mapel.id.toString());

                    // Buat baris HTML-nya (sama persis logikanya seperti saat tombol tambah diklik)
                    const itemRow = document.createElement('div');
                    itemRow.className = "flex items-center justify-between bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700";
                    itemRow.setAttribute('data-id', mapel.id);

                    itemRow.innerHTML = `
                <span>${mapel.nama_mapel || mapel.nama}</span>
                
                <input type="hidden" name="mapel[]" value="${mapel.id}">
                
                <button type="button" class="btn-hapus-mapel text-rose-500 hover:text-rose-700 font-medium text-xs transition">
                    Hapus
                </button>
            `;

                    // Masukkan ke dalam wrapper di modal
                    wrapperMapel.appendChild(itemRow);
                });
            }

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
                    form.action = "{{ url('admin/users') }}/" + "{{ old('id') }}";
                    methodField.innerHTML = `@method('PUT')`;
                @else
                    modalTitle.innerText = "Modal Form: Tambah User Baru";
                    form.action = "{{ route('admin.users.store') }}";
                    methodField.innerHTML = "";
                @endif

                modal.showModal();
            @endif
        });

        function closeUserModal() {
            modal.close();
        }

        document.addEventListener('DOMContentLoaded', function () {
            const selectMapel = document.getElementById('select_mapel');
            const btnTambahMapel = document.getElementById('btn_tambah_mapel');
            const wrapperMapel = document.getElementById('wrapper_mapel_terpilih');

            btnTambahMapel.addEventListener('click', function () {
                const id = selectMapel.value;
                const nama = selectMapel.options[selectMapel.selectedIndex].text;

                // Validasi 1: Pastikan user sudah memilih mapel
                if (!id) {
                    alert('Silakan pilih mata pelajaran terlebih dahulu!');
                    return;
                }

                // Validasi 2: Pastikan mapel belum pernah ditambahkan sebelumnya
                if (selectedMapelIds.includes(id)) {
                    alert('Mata pelajaran ini sudah ditambahkan!');
                    return;
                }

                // Catat ID ke dalam array pelacak
                selectedMapelIds.push(id);

                // Buat elemen baris baru untuk list mapel
                const itemRow = document.createElement('div');
                itemRow.className = "flex items-center justify-between bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 animate-fade-in";
                itemRow.setAttribute('data-id', id);

                itemRow.innerHTML = `
            <span>${nama}</span>
            
            <input type="hidden" name="mapel[]" value="${id}">
            
            <button type="button" class="btn-hapus-mapel text-rose-500 hover:text-rose-700 font-medium text-xs transition">
                Hapus
            </button>
        `;

                // Masukkan baris baru ke dalam wrapper list
                wrapperMapel.appendChild(itemRow);

                // Reset dropdown select ke pilihan default
                selectMapel.value = "";
            });

            // Logika Tombol Hapus (Menggunakan Event Delegation)
            wrapperMapel.addEventListener('click', function (e) {
                if (e.target.classList.contains('btn-hapus-mapel')) {
                    const row = e.target.closest('div');
                    const idYangDihapus = row.getAttribute('data-id');

                    // Hapus ID dari array pelacak
                    selectedMapelIds = selectedMapelIds.filter(id => id !== idYangDihapus);

                    // Hapus elemen HTML-nya
                    row.remove();
                }
            });
        });
    </script>
</x-app-layout>