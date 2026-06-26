<x-app-layout>
    <div class="space-y-6">
        <x-data-tabel>
            <x-slot name="page">Guru</x-slot>
            <x-slot name="header">
                <th class="w-12 text-center">No</th>
                <th>Nama Guru</th>
                <th>NIP & Role</th>
                <th>Pelajaran Yang Diampu</th>
                <th class="w-28 text-center">Aksi</th>
            </x-slot>

            @forelse ($daftarGuru as $guru)
                <tr class="hover align-middle">
                    <td class="text-center font-medium text-base-content/60">
                        {{ ($daftarGuru->currentPage() - 1) * $daftarGuru->perPage() + $loop->iteration }}
                    </td>

                    <td>
                        <div class="flex items-center gap-3">
                            <div class="avatar">
                                @if ($guru->profileGuru->foto)
                                    <div class="w-10 rounded-full">
                                        <img src="{{ asset('storage/' . $guru->profileGuru->foto) }}" alt="{{ $guru->name }}" />
                                    </div>
                                @else
                                    <div class="skeleton h-10 w-10 shrink-0 rounded-full"></div>
                                @endif
                            </div>
                            <div>
                                <div class="font-bold text-base-content">{{ $guru->name }}</div>
                                <div class="text-xs text-base-content/50">{{ $guru->email }}</div>
                            </div>
                        </div>
                    </td>

                    <td>
                        <div class="flex flex-col gap-1 items-start">
                            <span class="text-sm font-semibold font-mono text-base-content/80">
                                {{ $guru->profileGuru->nip ?? '-' }}
                            </span>
                            <span class="badge badge-neutral badge-xs uppercase font-bold tracking-wider px-1.5 rounded">
                                {{ $guru->roles->pluck('name')->implode(', ') }}
                            </span>
                        </div>
                    </td>

                    <td>
                        @if ($guru->profileGuru && $guru->profileGuru->mataPelajarans->isNotEmpty())
                            <div class="flex flex-wrap gap-1 max-w-md">
                                @foreach ($guru->profileGuru->mataPelajarans as $mapel)
                                    <span class="badge badge-primary badge-sm font-medium">
                                        {{ $mapel->nama }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-base-content/30 text-xs italic">Belum ada pelajaran</span>
                        @endif
                    </td>

                    <td class="text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <button type="button"
                                class="btn btn-xs btn-warning text-warning-content shadow-none font-medium px-2.5"
                                onclick="openEditModal({{ $guru }})">
                                Edit
                            </button>
                            <form action="{{ route('admin.guru.destroy', $guru) }}" method="POST"
                                class="inline form-delete">
                                @csrf
                                @method('DELETE')

                                <button type="submit" data-name="{{ $guru->name }}"
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
                                        d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-base-content/70">Belum ada Guru</p>
                                <p class="text-sm text-base-content/40 mt-0.5">Klik "Tambah Guru" untuk menambahkan Guru
                                </p>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-data-tabel>

        {{ $daftarGuru->links() }}
    </div>

    <x-form-modal>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="form-control col-span-1 sm:col-span-2">
                <label class="floating-label">
                    <span>Masukkan Nama</span>
                    <input type="text" id="input_name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama"
                        class="input input-primary w-full" required />
                </label>
                <x-input-error :messages="$errors->get('name')" />
            </div>

            <div class="form-control">
                <label class="floating-label">
                    <span>Email (mail@site.com)</span>
                    <input type="email" id="input_email" name="email" value="{{ old('email') }}"
                        placeholder="mail@site.com" class="input input-primary w-full" required />
                </label>
                <x-input-error :messages="$errors->get('email')" />
            </div>

            <div class="form-control">
                <label class="floating-label">
                    <span>NIP / Nomor Induk</span>
                    <input type="number" id="input_nip" name="nip" value="{{ old('nip') }}"
                        placeholder="NIP / Nomor Induk" class="input input-primary w-full" />
                </label>
                <x-input-error :messages="$errors->get('nip')" />
            </div>

            <div class="form-control">
                <label class="floating-label">
                    <span>Pilih Role</span>
                    <select id="input_role" name="role" class="select select-primary w-full" required>
                        <option value="" disabled selected></option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </label>
                <x-input-error :messages="$errors->get('role')" />
            </div>

            <div class="form-control">
                <div class="join join-horizontal w-full gap-2">
                    <label class="floating-label join-item w-full">
                        <span>Mata Pelajaran</span>
                        <select id="select_mapel" name="mapel" class="select select-primary w-full">
                            <option value="" disabled selected></option>
                            @foreach ($mapels as $mapel)
                                <option value="{{ $mapel->id }}">{{ ucfirst($mapel->nama) }}</option>
                            @endforeach
                        </select>
                    </label>
                    <button type="button" id="btn_tambah_mapel" class="btn btn-primary join-item px-4 rounded-full">
                        +
                    </button>
                </div>
                <div id="wrapper_mapel_terpilih" class="mt-2 flex flex-wrap gap-1">
                </div>
                <x-input-error :messages="$errors->get('mapel')" />
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
                <x-input-error :messages="$errors->get('password')" />
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
            form.action = `{{ route('admin.guru.store') }}`
            method.innerHTML = ''
            form.reset()
            document.getElementById('password_hint').classList.add('hidden');
            selectedMapelIds.length = 0
            document.getElementById('wrapper_mapel_terpilih').innerHTML = '';
            modal.showModal()
        }

        function openEditModal(guru) {
            modalTitle.innerText = 'Edit Guru (' + guru.name + ')'
            form.action = `{{ url('admin/guru') }}/${guru.id}`
            method.innerHTML = `@method('PUT')`

            document.getElementById('input_name').value = guru.name
            document.getElementById('input_email').value = guru.email
            document.getElementById('input_nip').value = guru.profile_guru?.nip || ''
            document.getElementById('input_role').value = guru.roles[0].name || ''
            document.getElementById('input_password').required = false
            document.getElementById('password_hint').classList.remove('hidden');

            const wrapperMapel = document.getElementById('wrapper_mapel_terpilih');

            wrapperMapel.innerHTML = '';

            selectedMapelIds = [];

            if (guru.profile_guru && guru.profile_guru.mata_pelajarans) {
                guru.profile_guru.mata_pelajarans.forEach(mapel => {
                    selectedMapelIds.push(mapel.id.toString());

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
                    modalTitle.innerText = "Modal Form: Edit Guru";
                    form.action = "{{ url('admin/guru') }}/" + "{{ old('id') }}";
                    methodField.innerHTML = `@method('PUT')`;
                @else
                    modalTitle.innerText = "Modal Form: Tambah Guru Baru";
                    form.action = "{{ route('admin.guru.store') }}";
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

                if (!id) {
                    alert('Silakan pilih mata pelajaran terlebih dahulu!');
                    return;
                }
                if (selectedMapelIds.includes(id)) {
                    alert('Mata pelajaran ini sudah ditambahkan!');
                    return;
                }
                selectedMapelIds.push(id);

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

                wrapperMapel.appendChild(itemRow);
                selectMapel.value = "";
            });

            wrapperMapel.addEventListener('click', function (e) {
                if (e.target.classList.contains('btn-hapus-mapel')) {
                    const row = e.target.closest('div');
                    const idYangDihapus = row.getAttribute('data-id');

                    selectedMapelIds = selectedMapelIds.filter(id => id !== idYangDihapus);
                    row.remove();
                }
            });
        });

        function closeModal() {
            modal.close()
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Tangkap semua form yang memiliki class .form-delete
            document.querySelectorAll('.form-delete').forEach(form => {
                form.addEventListener('submit', function (e) {
                    // 1. Tahan form agar tidak langsung mengirim data ke server
                    e.preventDefault();

                    // 2. Ambil nama guru dari atribut data-name tombol yang diklik
                    const namaGuru = this.querySelector('button[data-name]').getAttribute('data-name');

                    // 3. Munculkan SweetAlert2
                    Swal.fire({
                        title: "Apakah anda yakin?",
                        text: `Data guru "${namaGuru}" akan dihapus permanen dari sistem!`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: "Ya, Hapus",
                        cancelButtonText: "Tidak",
                        reverseButtons: true // Tombol 'Tidak' di kiri, 'Ya' di kanan
                    }).then((result) => {
                        // 4. Jika diklik "Ya, Hapus", jalankan submit form aslinya
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>