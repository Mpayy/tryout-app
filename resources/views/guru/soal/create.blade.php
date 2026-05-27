<x-app-layout>
    <x-slot name="header">
        Bank Soal: Input Massal (Bulk Mode)
    </x-slot>

    <!-- AREA ATAMA CARD DAISYUI -->
    <div class="card bg-white shadow-sm border border-gray-100 rounded-xl mb-6">
        <div class="card-body p-6">

            <!-- HEADER INFO & UTILITY BUTTONS -->
            <div
                class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6 border-b border-gray-100 pb-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Manajemen Pertanyaan & Pilihan Jawaban</h2>
                </div>
            </div>

            <!-- FORM UTAMA UNTUK BULK DATA -->
            <form action="{{ route('guru.soal.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="form-control w-full max-w-xs bg-white p-4 rounded-xl border border-slate-100 shadow-sm">
                    <label class="label pt-0">
                        <span class="label-text font-semibold text-slate-700">Mata Pelajaran</span>
                    </label>
                    <select name="mapel_id" id="mapel_id"
                        class="select select-bordered w-full bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-xl text-sm font-medium transition"
                        required>
                        <option value="" disabled selected>-- Pilih Mata Pelajaran --</option>
                        @foreach ($mataPelajaranGuru as $mapel)
                            <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="card bg-white shadow-xl border border-slate-100 rounded-2xl overflow-hidden">
                    <div class="overflow-x-auto w-full">
                        <table class="table w-full" id="table-soal">
                            <thead class="bg-slate-50/70 text-slate-600 font-bold text-sm border-b border-slate-100">
                                <tr>
                                    <th class="w-12 text-center py-4">No</th>
                                    <th class="w-1/3 min-w-[320px]">Butir Pertanyaan</th>
                                    <th class="min-w-[160px]">Opsi A</th>
                                    <th class="min-w-[160px]">Opsi B</th>
                                    <th class="min-w-[160px]">Opsi C</th>
                                    <th class="min-w-[160px]">Opsi D</th>
                                    <th class="w-36 min-w-[120px] text-center">Kunci</th>
                                    <th class="w-16 text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody id="container-soal" class="divide-y divide-slate-100">
                                <tr class="hover:bg-slate-50/50 transition dynamic-row" data-index="0">
                                    <td class="text-center font-bold text-slate-400 row-number py-4">1</td>
                                    <td>
                                        <textarea name="soal[0][pertanyaan]" rows="2"
                                            class="textarea textarea-bordered w-full bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-xl text-sm min-h-[3.5rem] resize-y transition"
                                            placeholder="Ketik butir soal ujian disini..." required></textarea>
                                    </td>
                                    <td>
                                        <input type="text" name="soal[0][opsi][A]"
                                            class="input input-bordered input-sm w-full bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg transition"
                                            placeholder="Pilihan A" required />
                                    </td>
                                    <td>
                                        <input type="text" name="soal[0][opsi][B]"
                                            class="input input-bordered input-sm w-full bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg transition"
                                            placeholder="Pilihan B" required />
                                    </td>
                                    <td>
                                        <input type="text" name="soal[0][opsi][C]"
                                            class="input input-bordered input-sm w-full bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg transition"
                                            placeholder="Pilihan C" required />
                                    </td>
                                    <td>
                                        <input type="text" name="soal[0][opsi][D]"
                                            class="input input-bordered input-sm w-full bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg transition"
                                            placeholder="Pilihan D" required />
                                    </td>
                                    <td>
                                        <select name="soal[0][jawaban_benar]"
                                            class="select select-bordered w-full text-center font-bold bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg transition"
                                            required>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                            <option value="D">D</option>
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" onclick="deleteRow(this)"
                                            class="btn btn-ghost btn-sm text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div
                    class="flex flex-col sm:flex-row justify-between items-center gap-4 border-t border-slate-100 pt-5">
                    <button type="button" onclick="addRow()"
                        class="btn btn-outline border-indigo-600 text-indigo-600 hover:bg-indigo-50 hover:border-indigo-600 rounded-xl gap-2 w-full sm:w-auto transition shadow-sm">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Baris Baru
                    </button>

                    <div class="flex gap-3 w-full sm:w-auto justify-end">
                        <a href="{{ route('guru.soal.index') }}"
                            class="btn bg-slate-100 hover:bg-slate-200 text-slate-700 border-none rounded-xl w-1/2 sm:w-auto">Batal</a>
                        <button type="submit"
                            class="btn bg-indigo-600 hover:bg-indigo-700 text-white border-none rounded-xl px-8 w-1/2 sm:w-auto shadow-md shadow-indigo-200">
                            Simpan Semua Soal
                        </button>
                    </div>
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
            <td class="text-center font-bold text-slate-400 row-number py-4"></td>
            <td>
                <textarea name="soal[TEMP][pertanyaan]" rows="2" class="textarea textarea-bordered w-full bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-xl text-sm min-h-[3.5rem] resize-y transition" placeholder="Ketik butir soal ujian disini..." required></textarea>
            </td>
            <td>
                <input type="text" name="soal[TEMP][opsi][A]" class="input input-bordered input-sm w-full bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg transition" placeholder="Pilihan A" required />
            </td>
            <td>
                <input type="text" name="soal[TEMP][opsi][B]" class="input input-bordered input-sm w-full bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg transition" placeholder="Pilihan B" required />
            </td>
            <td>
                <input type="text" name="soal[TEMP][opsi][C]" class="input input-bordered input-sm w-full bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg transition" placeholder="Pilihan C" required />
            </td>
            <td>
                <input type="text" name="soal[TEMP][opsi][D]" class="input input-bordered input-sm w-full bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg transition" placeholder="Pilihan D" required />
            </td>
            <td>
                <select name="soal[TEMP][jawaban_benar]" class="select select-bordered w-full text-center font-bold bg-white border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-lg transition" required>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
            </td>
            <td class="text-center">
                <button type="button" onclick="deleteRow(this)" class="btn btn-ghost btn-sm text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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