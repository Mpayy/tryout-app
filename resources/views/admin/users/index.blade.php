<x-app-layout>
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-800">Manajemen Guru</h1>
            </div>
            <div>
                <x-primary-button onclick="openCreateModal()">
                    Tambah Guru
                </x-primary-button>
            </div>
        </div>

        <x-data-tabel>
            <x-slot name="header">
                <th>No</th>
                <th>Nama Guru</th>
                <th>Email</th>
                <th>Role</th>
                <th>NIP</th>
                <th>Pelajaran</th>
                <th>Aksi</th>
            </x-slot>

            @foreach ($daftarGuru as $guru)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $guru->name }}</td>
                    <td>{{ $guru->email }}</td>
                    <td>
                        <div class="badge badge-info badge-soft">
                            {{ $guru->roles->pluck('name')->implode(', ') }}
                        </div>
                    </td>
                    <td>{{ $guru->profileGuru->nip ?? '-' }}</td>
                    <td>
                        @if ($guru->profileGuru && $guru->profileGuru->mataPelajarans->isNotEmpty())
                            @foreach ($guru->profileGuru->mataPelajarans as $mapel)
                                <div class="badge badge-primary badge-soft">
                                    {{ $mapel->nama }}
                                </div>
                            @endforeach
                        @else
                            <span>-</span>
                        @endif
                    </td>
                    <td>
                        <div class="flex items-center gap-1.5">
                            <button onclick="openEditModal({{ $guru }})"
                                class="btn btn-primary btn-sm btn-soft">Edit</button>
                            <form action="{{ route('admin.users.destroy', $guru) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-error btn-sm btn-soft">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </x-data-tabel>
    </div>

    <x-form-modal>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <div class="form-control w-full col-span-1 sm:col-span-2">
                <label class="label"><span class="label-text font-semibold text-slate-700 text-sm">Nama
                        Lengkap</span></label>
                <input type="text" id="input_name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama"
                    class="input input-primary w-full" required />
            </div>

            <div class="form-control w-full">
                <label class="label"><span class="label-text font-semibold text-slate-700 text-sm">Email
                        Address</span></label>
                <input type="email" id="input_email" name="email" value="{{ old('email') }}"
                    placeholder="contoh@gmail.com" class="input input-primary" required />
            </div>

            <div class="form-control w-full">
                <label class="label"><span class="label-text font-semibold text-slate-700 text-sm">NIP /
                        Nomor Induk</span></label>
                <input type="number" id="input_nip" name="nip" value="{{ old('nip') }}" placeholder="199503212022031002"
                    class="input input-primary" />
            </div>

            <div class="form-control w-full">
                <label class="label"><span class="label-text font-semibold text-slate-700 text-sm">Pilih
                        Role</span></label>
                <select id="input_role" name="role" class="select select-primary" value="{{ old('role') }}" required>
                    <option value="" disabled selected>-- Pilih Role --</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-control w-full">
                <label class="label"><span class="label-text font-semibold text-slate-700 text-sm">Mata
                        Pelajaran</span></label>
                <div class="flex gap-2">
                    <select id="select_mapel" name="mapel" class="select select-primary w-full">
                        <option value="" disabled selected>-- Pilih Mata Pelajaran --</option>
                        @foreach ($mapels as $mapel)
                            <option value="{{ $mapel->id }}">{{ ucfirst($mapel->nama) }}</option>
                        @endforeach
                    </select>

                    <button type="button" id="btn_tambah_mapel" class="btn btn-primary btn-sm btn-soft">
                        +
                    </button>
                </div>
                <div id="wrapper_mapel_terpilih" class="mt-3 space-y-2">
                </div>
            </div>

            <div class="form-control w-full col-span-1 sm:col-span-2">
                <label class="label">
                    <span class="label-text font-semibold text-slate-700 text-sm">Kata Sandi</span>
                    <span id="password_hint" class="label-text-alt text-amber-600 font-medium hidden">*Kosongkan
                        jika tidak ingin mengubah sandi</span>
                </label>
                <input type="password" id="input_password" name="password" placeholder="••••••••"
                    class="input input-primary w-full" />
            </div>
        </div>
    </x-form-modal>

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
            selectedMapelIds.length = 0
            document.getElementById('wrapper_mapel_terpilih').innerHTML = '';
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
                <span>${mapel.nama}</span>
                
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