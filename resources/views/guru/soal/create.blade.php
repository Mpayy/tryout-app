<x-app-layout>
    <x-slot name="header">
        Bank Soal: Input Massal (Bulk Mode)
    </x-slot>

    <!-- AREA ATAMA CARD DAISYUI -->
    <div class="card bg-white shadow-sm border border-gray-100 rounded-xl mb-6">
        <div class="card-body p-6">
            
            <!-- HEADER INFO & UTILITY BUTTONS -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6 border-b border-gray-100 pb-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Manajemen Pertanyaan & Pilihan Jawaban</h2>
                </div>
            </div>

            <!-- FORM UTAMA UNTUK BULK DATA -->
            <form action="{{ route('guru.soal.store') }}" method="POST">
                @csrf
                <select name="mapel_id" id="mapel_id" class="select select-bordered w-full max-w-xs">
                    <option value="">Pilih Mata Pelajaran</option>
                    @foreach ($mapels as $mapel)
                        <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                    @endforeach
                </select>
                <!-- TABEL MATRIKS SOAL DYNAMIC -->
                <div class="overflow-x-auto w-full border border-gray-200/70 rounded-xl">
                    <table class="table w-full text-gray-700" id="table-soal">
                        <!-- Head Tabel -->
                        <thead class="bg-gray-50 text-gray-600 font-semibold text-sm border-b border-gray-200">
                            <tr>
                                <th class="w-12 text-center py-4">No</th>
                                <th class="w-1/3 min-w-[300px]">Pertanyaan (Input Teks)</th>
                                <th class="min-w-[150px]">Opsi A</th>
                                <th class="min-w-[150px]">Opsi B</th>
                                <th class="min-w-[150px]">Opsi C</th>
                                <th class="min-w-[150px]">Opsi D</th>
                                <th class="w-40 min-w-[130px] text-center">Jawaban Benar</th>
                                <th class="w-16 text-center">Aksi</th>
                            </tr>
                        </thead>
                        
                        <!-- Body Tempat Suntik Baris Soal (Default Berisi 1 Baris Kosong) -->
                        <tbody id="container-soal" class="divide-y divide-gray-100">
                            <tr class="hover:bg-gray-50/40 transition dynamic-row" data-index="0">
                                <td class="text-center font-medium text-gray-400 row-number">1</td>
                                <td>
                                    <!-- Textarea fleksibel agar muat tulisan panjang -->
                                    <textarea name="soal[0][pertanyaan]" rows="2" class="textarea textarea-bordered w-full resize-y text-sm focus:outline-teal-600 min-h-[3.5rem]" placeholder="Ketik butir soal ujian disini..." required></textarea>
                                </td>
                                <td>
                                    <input type="text" name="soal[0][opsi][A]" class="input input-bordered input-sm w-full focus:outline-teal-600" placeholder="Pilihan A" required />
                                </td>
                                <td>
                                    <input type="text" name="soal[0][opsi][B]" class="input input-bordered input-sm w-full focus:outline-teal-600" placeholder="Pilihan B" required />
                                </td>
                                <td>
                                    <input type="text" name="soal[0][opsi][C]" class="input input-bordered input-sm w-full focus:outline-teal-600" placeholder="Pilihan C" required />
                                </td>
                                <td>
                                    <input type="text" name="soal[0][opsi][D]" class="input input-bordered input-sm w-full focus:outline-teal-600" placeholder="Pilihan D" required />
                                </td>
                                <td class="text-center">
                                    <select name="soal[0][jawaban_benar]" class="select select-bordered w-full text-center font-semibold text-gray-700 focus:outline-teal-600" required>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                    </select>
                                </td>
                                <td class="text-center">
                                    <button type="button" onclick="deleteRow(this)" class="btn btn-ghost btn-xs text-gray-400 hover:text-red-500">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- CONTROL BUTTONS (BAGIAN BAWAH) -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-6 border-t border-gray-100 pt-4">
                    <!-- Tombol Tambah Baris Dinamis -->
                    <button type="button" onclick="addRow()" class="btn btn-outline border-teal-600 text-teal-600 hover:bg-teal-50 hover:border-teal-600 gap-2 w-full sm:w-auto">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Baris Baru
                    </button>

                    <!-- Aksi Simpan Masal / Batal -->
                    <div class="flex gap-2 w-full sm:w-auto justify-end">
                        <a href="{{ route('guru.soal.index') }}" class="btn btn-ghost border-gray-200 w-1/2 sm:w-auto">Batal</a>
                        <button type="submit" class="btn btn-primary px-8 w-1/2 sm:w-auto">Simpan Perubahan (Bulk)</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <!-- ========================================== -->
    <!-- JAVASCRIPT LOGIC UNTUK MANIPULASI BARIS TABEL -->
    <!-- ========================================== -->
    <script>
        let rowIndex = 1; // Mulai dari index 1 karena index 0 sudah ada di HTML bawaan

        // Fungsi Menambah Baris Baru Secara Instan
        function addRow() {
            const container = document.getElementById('container-soal');
            const newRow = document.createElement('tr');
            newRow.className = "hover:bg-gray-50/40 transition dynamic-row";
            newRow.setAttribute('data-index', rowIndex);

            newRow.innerHTML = `
                <td class="text-center font-medium text-gray-400 row-number"></td>
                <td>
                    <textarea name="soal[${rowIndex}][pertanyaan]" rows="2" class="textarea textarea-bordered w-full resize-y text-sm focus:outline-teal-600 min-h-[3.5rem]" placeholder="Ketik butir soal ujian disini..." required></textarea>
                </td>
                <td>
                    <input type="text" name="soal[${rowIndex}][opsi][A]" class="input input-bordered input-sm w-full focus:outline-teal-600" placeholder="Pilihan A" required />
                </td>
                <td>
                    <input type="text" name="soal[${rowIndex}][opsi][B]" class="input input-bordered input-sm w-full focus:outline-teal-600" placeholder="Pilihan B" required />
                </td>
                <td>
                    <input type="text" name="soal[${rowIndex}][opsi][C]" class="input input-bordered input-sm w-full focus:outline-teal-600" placeholder="Pilihan C" required />
                </td>
                <td>
                    <input type="text" name="soal[${rowIndex}][opsi][D]" class="input input-bordered input-sm w-full focus:outline-teal-600" placeholder="Pilihan D" required />
                </td>
                <td class="text-center">
                    <select name="soal[${rowIndex}][jawaban_benar]" class="select select-bordered w-full text-center font-semibold text-gray-700 focus:outline-teal-600" required>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                </td>
                <td class="text-center">
                    <button type="button" onclick="deleteRow(this)" class="btn btn-ghost btn-xs text-gray-400 hover:text-red-500">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </td>
            `;

            container.appendChild(newRow);
            rowIndex++;
            updateRowNumbers(); // Susun ulang penomoran
        }

        // Fungsi Menghapus Baris Terpilih
        function deleteRow(button) {
            const row = button.closest('tr');
            const container = document.getElementById('container-soal');
            
            // Sisakan minimal 1 baris di tabel agar form tidak kosong melompong
            if (container.querySelectorAll('.dynamic-row').length > 1) {
                row.remove();
                updateRowNumbers();
            } else {
                alert('Gagal menghapus! Minimal harus ada 1 baris soal yang diisi.');
            }
        }

        // Fungsi Otomatis Mengurutkan Angka No. 1, 2, 3... saat ada baris ditambah/dihapus
        function updateRowNumbers() {
            const rows = document.querySelectorAll('#container-soal .dynamic-row');
            rows.forEach((row, index) => {
                row.querySelector('.row-number').innerText = index + 1;
            });
        }

        // Jalankan kalkulasi urutan nomor di awal load halaman
        document.addEventListener("DOMContentLoaded", updateRowNumbers);
    </script>
</x-app-layout>