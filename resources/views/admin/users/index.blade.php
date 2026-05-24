<x-app-layout>
    <x-slot name="header">
        Manajemen User
    </x-slot>

    <!-- AREA UTAMA (Bungkus Card DaisyUI) -->
    <div class="card bg-white shadow-sm border border-gray-100 rounded-xl">
        <div class="card-body p-6">

            <!-- HEADER DAFTAR USER & TOMBOL TAMBAH -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Daftar User Platform</h2>
                    <p class="text-sm text-gray-500">Kelola data login siswa, guru, dan admin sistem.</p>
                </div>

                <!-- Tombol untuk memicu Modal DaisyUI via ID -->
                <button onclick="openCreateModal()" class="btn btn-primary sm:w-auto w-full gap-2 px-5">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Tambah User
                </button>
            </div>

            <!-- AREA TABEL USER (Zebra Striped DaisyUI) -->
            <div class="overflow-x-auto w-full rounded-lg border border-gray-100">
                <table class="table table-zebra w-full text-gray-700">
                    <!-- Head Tabel -->
                    <thead class="bg-gray-50 text-gray-600 font-semibold text-sm">
                        <tr>
                            <th class="py-4">Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            {{-- <th>Status</th> --}}
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <!-- Isi Data User -->
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="font-medium text-gray-900 py-4">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <!-- Deteksi Role untuk Pewarnaan Badge DaisyUI -->
                                    @if ($user->roles->pluck('name')->contains('admin'))
                                        <span
                                            class="badge bg-purple-100 text-purple-700 border-none font-medium px-3 py-1">Admin</span>
                                    @elseif($user->roles->pluck('name')->contains('guru'))
                                        <span
                                            class="badge bg-blue-100 text-blue-700 border-none font-medium px-3 py-1">Guru</span>
                                    @else
                                        <span
                                            class="badge bg-teal-100 text-teal-700 border-none font-medium px-3 py-1">Siswa</span>
                                    @endif
                                </td>
                                {{-- <td>
                                    <!-- Switch Toggle Status Aktif (DaisyUI Toggle) -->
                                    <input type="checkbox" class="toggle toggle-success toggle-sm" checked />
                                </td> --}}
                                <td class="text-center">
                                    <div class="flex justify-center gap-2">
                                        <!-- Tombol Edit -->
                                        <button onclick="openEditModal({{ $user }})"
                                            class="btn btn-ghost btn-xs text-gray-500 hover:text-teal-600 gap-1 font-medium">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </button>
                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                class="btn btn-ghost btn-xs text-gray-400 hover:text-red-500 gap-1 font-medium">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Hapus
                                            </button>
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

    <!-- ========================================== -->
    <!-- MODAL FORM DIALOG (Satu Modal untuk Tambah & Edit) -->
    <!-- ========================================== -->
    <dialog id="user_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box bg-white max-w-md p-6 rounded-xl">
            <!-- Judul Modal Dinamis -->
            <h3 id="modal_title" class="font-bold text-lg text-gray-800 mb-4">Modal Form: Tambah User Baru</h3>
            <!-- Taruh Alert DaisyUI milikmu di sini -->
            @if ($errors->any())
                <div role="alert" class="alert alert-error mb-4 bg-red-50 text-red-800 border-red-200">
                    <div class="flex flex-col gap-1 items-start">
                        @foreach ($errors->all() as $error)
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 shrink-0 stroke-current text-red-600" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm font-medium">{{ $error }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Form Input -->
            <form id="user_form" method="POST" action="">
                @csrf
                <!-- Method Spoofing untuk Edit (Akan diisi lewat JS jika Edit) -->
                <div id="method_field"></div>

                <!-- Input Nama -->
                <div class="form-control w-full mb-4">
                    <label class="label"><span class="label-text font-semibold text-gray-600">Nama
                            Lengkap</span></label>
                    <input type="text" id="input_name" name="name" value="{{ old('name') }}"
                        placeholder="Masukkan nama lengkap..."
                        class="input input-bordered w-full focus:outline-teal-600" />
                </div>

                <!-- Input Email -->
                <div class="form-control w-full mb-4">
                    <label class="label"><span class="label-text font-semibold text-gray-600">Email
                            Address</span></label>
                    <input type="email" id="input_email" name="email" value="{{ old('email') }}"
                        placeholder="contoh@gmail.com"
                        class="input input-bordered w-full focus:outline-teal-600" required />
                </div>

                <!-- Dropdown Pilih Role (DaisyUI Select) -->
                <div class="form-control w-full mb-4">
                    <label class="label"><span class="label-text font-semibold text-gray-600">Pilih Role</span></label>
                    <select id="input_role" name="role" class="select select-bordered w-full focus:outline-teal-600"
                        required>
                        <option value="" disabled selected>-- Pilih Hak Akses --</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Input Password -->
                <div class="form-control w-full mb-6">

                    <label class="label">
                        <span class="label-text font-semibold text-gray-600">Kata Sandi</span>
                        <span id="password_hint" class="label-text-alt text-gray-400 hidden">*Kosongkan jika tidak
                            diubah</span>
                    </label>
                    <input type="password" id="input_password" name="password" placeholder="••••••••"
                        class="input input-bordered w-full focus:outline-teal-600" />
                </div>

                <!-- Tombol Aksi di bagian bawah Modal -->
                <div class="modal-action flex justify-end gap-2 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeUserModal()"
                        class="btn btn-ghost border-gray-200">Batal</button>
                    <button type="submit" class="btn btn-primary px-6">Simpan User</button>
                </div>
            </form>
        </div>

        <!-- Backdrop transparan hitam agar klik di luar otomatis menutup modal -->
        <form method="dialog" class="modal-backdrop bg-black/30">
            <button>close</button>
        </form>
    </dialog>

    <!-- ========================================== -->
    <!-- JAVASCRIPT LOGIC (Untuk Buka-Tutup Modal Dinamis) -->
    <!-- ========================================== -->
    <script>
        const modal = document.getElementById('user_modal');
        const form = document.getElementById('user_form');
        const modalTitle = document.getElementById('modal_title');
        const methodField = document.getElementById('method_field');
        const passwordHint = document.getElementById('password_hint');

        // Fungsi Buka Modal Tambah Data
        function openCreateModal() {
            modalTitle.innerText = "Modal Form: Tambah User Baru";
            form.action = "{{ route('admin.users.store') }}"; // Arahkan ke route store
            methodField.innerHTML = ""; // Bersihkan PUT method
            passwordHint.classList.add('hidden');
            document.getElementById('input_password').required = true;

            form.reset(); // Kosongkan seluruh form inputan
            modal.showModal(); // Fungsi sakti bawaan browser untuk memunculkan <dialog>
        }

        // Fungsi Buka Modal Edit Data (Sambil Lempar Data User terpilih)
        function openEditModal(user) {
            modalTitle.innerText = "Modal Form: Edit User (" + user.name + ")";
            form.action = "/admin/users/" + user.id; // Arahkan ke URL update
            methodField.innerHTML = `@method('PUT')`; // Tambahkan spoofing method PUT Laravel
            passwordHint.classList.remove('hidden');
            document.getElementById('input_password').required = false;

            // Set value input form sesuai data user yang mau diedit
            document.getElementById('input_name').value = user.name;
            document.getElementById('input_email').value = user.email;

            // Mengambil nama role pertamanya untuk di-select otomatis
            if (user.roles && user.roles.length > 0) {
                document.getElementById('input_role').value = user.roles[0].name;
            }

            document.getElementById('input_password').value = ""; // Kosongkan kolom sandi demi keamanan
            modal.showModal();
        }

        // Tambahkan ini di dalam tag <script> kamu, di bawah fungsi openCreateModal / openEditModal
        document.addEventListener("DOMContentLoaded", function() {
            // Mengecek apakah Laravel mengirimkan sinyal error bawaan
            @if ($errors->any())
                const modal = document.getElementById('user_modal');
                const modalTitle = document.getElementById('modal_title');
                const form = document.getElementById('user_form');
                const methodField = document.getElementById('method_field');

                // Opsional: Deteksi apakah ini error dari proses Edit atau Tambah Baru
                // Jika ada input '_method' yang bernilai PUT sebelumnya, kita set ulang stylenya ke Edit
                @if (old('_method') == 'PUT')
                    modalTitle.innerText = "Modal Form: Edit User";
                    form.action = "{{ url('admin/users') }}/" +
                    "{{ old('id') }}"; // Jika kamu mempassing old ID
                    methodField.innerHTML = `@method('PUT')`;
                @else
                    // Jika tidak, kembalikan ke setelan simpan baru
                    modalTitle.innerText = "Modal Form: Tambah User Baru";
                    form.action = "{{ route('admin.users.store') }}";
                    methodField.innerHTML = "";
                @endif

                // JALANKAN PERINTAH SAKTI INI UNTUK MEMBUKA MODAL OTOMATIS
                modal.showModal();
            @endif
        });

        // Fungsi Menutup Modal
        function closeUserModal() {
            modal.close(); // Fungsi bawaan untuk menutup <dialog>
        }
    </script>
</x-app-layout>
