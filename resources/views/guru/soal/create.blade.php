<x-app-layout>
    <!-- AREA ATAMA CARD DAISYUI -->
    <div class="card bg-white shadow-sm">
        <div class="card-body p-6">

            <!-- HEADER INFO & UTILITY BUTTONS -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-5">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-800">Manajemen Pertanyaan & Pilihan Jawaban
                    </h1>
                </div>
                <div>
                    <a href="{{ route('guru.soal.index') }}"
                        class="btn btn-primary shadow-sm font-semibold normal-case">
                        Kembali
                    </a>
                </div>
            </div>

            <!-- FORM UTAMA UNTUK BULK DATA -->
            <form action="{{ route('guru.soal.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="form-control w-full max-w-xs">
                    <label class="label pt-0 pb-1">
                        <span class="label-text font-bold text-base-content/80">Mata Pelajaran</span>
                    </label>
                    <select name="mapel_id" id="mapel_id" class="select select-sm select-primary w-full" required>
                        <option value="" disabled selected>-- Pilih Mata Pelajaran --</option>
                        @foreach ($mataPelajaranGuru as $mapel)
                            <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="card bg-base-100 border border-base-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto w-full">
                        <table class="table w-full" id="table-soal">
                            <thead class="text-xs text-primary-content bg-primary uppercase tracking-wider">
                                <tr>
                                    <th class="w-12 text-center">No</th>
                                    <th class="w-2/5">Butir Pertanyaan</th>
                                    <th class="w-2/5">Pilihan Jawaban (A, B, C, D)</th>
                                    <th class="w-24 text-center">Kunci</th>
                                    <th class="w-16 text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody id="container-soal" class="divide-y divide-base-200">
                                <tr class="hover:bg-base-200/30 transition dynamic-row align-top" data-index="0">
                                    <td class="text-center font-bold text-base-content/40 row-number pt-5">1</td>

                                    <td class="p-3">
                                        <textarea name="soal[0][pertanyaan]" rows="3"
                                            class="textarea textarea-bordered textarea-primary textarea-sm w-full leading-relaxed"
                                            placeholder="Ketik butir soal ujian disini..." required></textarea>
                                    </td>

                                    <td class="p-3">
                                        <div class="grid grid-cols-2 gap-2">
                                            <div class="join join-horizontal w-full">
                                                <span
                                                    class="join-item bg-primary text-primary-content border border-base-300 text-xs font-bold px-2.5 flex items-center">A</span>
                                                <input type="text" name="soal[0][opsi][A]"
                                                    class="input input-bordered input-primary input-sm join-item w-full"
                                                    placeholder="Isi opsi A" required />
                                            </div>
                                            <div class="join join-horizontal w-full">
                                                <span
                                                    class="join-item bg-primary text-primary-content border border-base-300 text-xs font-bold px-2.5 flex items-center">B</span>
                                                <input type="text" name="soal[0][opsi][B]"
                                                    class="input input-bordered input-primary input-sm join-item w-full"
                                                    placeholder="Isi opsi B" required />
                                            </div>
                                            <div class="join join-horizontal w-full">
                                                <span
                                                    class="join-item bg-primary text-primary-content border border-base-300 text-xs font-bold px-2.5 flex items-center">C</span>
                                                <input type="text" name="soal[0][opsi][C]"
                                                    class="input input-bordered input-primary input-sm join-item w-full"
                                                    placeholder="Isi opsi C" required />
                                            </div>
                                            <div class="join join-horizontal w-full">
                                                <span
                                                    class="join-item bg-primary text-primary-content border border-base-300 text-xs font-bold px-2.5 flex items-center">D</span>
                                                <input type="text" name="soal[0][opsi][D]"
                                                    class="input input-bordered input-primary input-sm join-item w-full"
                                                    placeholder="Isi opsi D" required />
                                            </div>
                                        </div>
                                    </td>

                                    <td class="p-3">
                                        <select name="soal[0][jawaban_benar]"
                                            class="select select-bordered select-primary select-sm w-full text-center font-bold text-sm"
                                            required>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                            <option value="D">D</option>
                                        </select>
                                    </td>

                                    <td class="text-center p-3">
                                        <button type="button"
                                            class="btn btn-sm btn-ghost text-error hover:bg-error/10 p-1 btn-square"
                                            onclick="deleteRow(this)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-2">
                    <button type="button" onclick="addRow()"
                        class="btn btn-sm btn-outline btn-secondary gap-2 normal-case font-medium">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Baris Baru
                    </button>

                    <button type="submit" class="btn btn-sm btn-primary px-6 normal-case font-medium shadow-md">
                        Simpan Semua Soal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- JAVASCRIPT LOGIC UNTUK MANIPULASI BARIS TABEL -->
    <!-- ========================================== -->
    <script>
        // Fungsi Menambah Baris Baru Secara Instan
        function addRow() {
            const container = document.getElementById('container-soal');

            // Buat elemen baris baru (tr)
            const newRow = document.createElement('tr');
            newRow.className = "hover:bg-slate-50/50 transition dynamic-row";

            // Cetak HTML komponen di dalam baris baru (Gunakan index sementara, nanti disusun ulang oleh updateFormStructures)
            newRow.innerHTML = `
            <td class="text-center font-bold text-base-content/40 row-number pt-5"></td>

            <td class="p-3">
                <textarea name="soal[TEMP][pertanyaan]" rows="3"
                    class="textarea textarea-bordered textarea-primary textarea-sm w-full leading-relaxed"
                    placeholder="Ketik butir soal ujian disini..." required></textarea>
            </td>
            <td class="p-3">
                <div class="grid grid-cols-2 gap-2">
                    <div class="join join-horizontal w-full">
                        <span
                            class="join-item bg-primary text-primary-content border border-base-300 text-xs font-bold px-2.5 flex items-center">A</span>
                        <input type="text" name="soal[TEMP][opsi][A]"
                            class="input input-bordered input-primary input-sm join-item w-full"
                            placeholder="Isi opsi A" required />
                    </div>
                    <div class="join join-horizontal w-full">
                        <span
                            class="join-item bg-primary text-primary-content border border-base-300 text-xs font-bold px-2.5 flex items-center">B</span>
                        <input type="text" name="soal[TEMP][opsi][B]"
                            class="input input-bordered input-primary input-sm join-item w-full"
                            placeholder="Isi opsi B" required />
                    </div>
                    <div class="join join-horizontal w-full">
                        <span
                            class="join-item bg-primary text-primary-content border border-base-300 text-xs font-bold px-2.5 flex items-center">C</span>
                        <input type="text" name="soal[TEMP][opsi][C]"
                            class="input input-bordered input-primary input-sm join-item w-full"
                            placeholder="Isi opsi C" required />
                    </div>
                    <div class="join join-horizontal w-full">
                        <span
                            class="join-item bg-primary text-primary-content border border-base-300 text-xs font-bold px-2.5 flex items-center">D</span>
                        <input type="text" name="soal[TEMP][opsi][D]"
                            class="input input-bordered input-primary input-sm join-item w-full"
                            placeholder="Isi opsi D" required />
                    </div>
                </div>
            </td>
            <td class="p-3">
                <select name="soal[TEMP][jawaban_benar]"
                    class="select select-bordered select-primary select-sm w-full text-center font-bold text-sm"
                    required>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
            </td>
            <td class="text-center p-3">
                <button type="button"
                    class="btn btn-sm btn-ghost text-error hover:bg-error/10 p-1 btn-square"
                    onclick="deleteRow(this)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                </button>
            </td>
        `;

            container.appendChild(newRow);
            updateFormStructures(); // Jalankan penyusunan ulang nomor & name attribute
        }

        // Fungsi Menghapus Baris Terpilih
        function deleteRow(button) {
            const row = button.closest('tr');
            const container = document.getElementById('container-soal');

            // Proteksi: Sisakan minimal 1 baris
            if (container.querySelectorAll('.dynamic-row').length > 1) {
                row.remove();
                updateFormStructures(); // Tata ulang susunan setelah ada yang dihapus
            } else {
                alert('Gagal menghapus! Harus menyisakan minimal 1 baris soal untuk diisi.');
            }
        }

        // 🔥 FITUR BEST PRACTICE: Otomatis Menyusun Urutan Nomor dan Name Atribut Input HTML
        function updateFormStructures() {
            const rows = document.querySelectorAll('#container-soal .dynamic-row');

            rows.forEach((row, index) => {
                // 1. Update Visual Angka Nomor (1, 2, 3...)
                row.querySelector('.row-number').innerText = index + 1;

                // 2. Amankan Atribut Data-Index pada elemen TR
                row.setAttribute('data-index', index);

                // 3. Rekonstruksi Ulang Struktur Atribut Name agar Berurutan (0, 1, 2, 3...)
                row.querySelector(`textarea[name^="soal"]`).setAttribute('name', `soal[${index}][pertanyaan]`);
                row.querySelector(`input[name$="[opsi][A]"]`).setAttribute('name', `soal[${index}][opsi][A]`);
                row.querySelector(`input[name$="[opsi][B]"]`).setAttribute('name', `soal[${index}][opsi][B]`);
                row.querySelector(`input[name$="[opsi][C]"]`).setAttribute('name', `soal[${index}][opsi][C]`);
                row.querySelector(`input[name$="[opsi][D]"]`).setAttribute('name', `soal[${index}][opsi][D]`);
                row.querySelector(`select[name$="[jawaban_benar]"]`).setAttribute('name', `soal[${index}][jawaban_benar]`);
            });
        }

        // Jalankan kalkulasi urutan nomor pertama kali saat halaman dibuka
        document.addEventListener("DOMContentLoaded", updateFormStructures);
    </script>
</x-app-layout>