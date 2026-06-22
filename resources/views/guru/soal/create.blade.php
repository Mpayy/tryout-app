<x-app-layout>
    <div class="card bg-base-100 border border-base-200 shadow-sm">
        <div class="card-body p-5 lg:p-6">

            {{-- Header --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-5">
                <div>
                    <h1 class="text-xl font-bold tracking-tight text-base-content">Tambah Soal ke Bank Soal</h1>
                    <p class="text-sm text-base-content/50 mt-0.5">Isi butir pertanyaan dan pilihan jawaban secara massal.</p>
                </div>
                <a href="{{ route('guru.soal.index') }}"
                    class="btn btn-ghost bg-base-200 hover:bg-base-300 text-base-content btn-sm md:btn-md font-semibold gap-2 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                        stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    Kembali ke Bank Soal
                </a>
            </div>

            <form action="{{ route('guru.soal.store') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Pilih Mata Pelajaran --}}
                <div class="form-control w-full max-w-xs">
                    <label class="floating-label">
                        <span class="label-text font-semibold text-base-content/70 text-sm">Mata Pelajaran</span>
                        <select name="mapel_id" id="mapel_id" class="select select-primary select-sm w-full" required>
                            <option value="" disabled selected>-- Pilih Mata Pelajaran --</option>
                            @foreach ($mataPelajaranGuru as $mapel)
                                <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>

                {{-- Tabel input soal --}}
                <div class="border border-base-200 rounded-xl overflow-hidden">
                    <div class="overflow-x-auto w-full">
                        <table class="table w-full" id="table-soal">
                            {{--
                                PERUBAHAN: thead tidak lagi bg-primary karena warnanya "berebut"
                                dengan warna input di dalam baris. bg-base-200/60 lebih netral dan
                                lebih nyaman di mata saat scroll panjang.
                            --}}
                            <thead class="bg-base-200/60">
                                <tr class="text-xs font-bold uppercase tracking-wide text-base-content/60">
                                    <th class="w-12 text-center">No</th>
                                    <th class="w-2/5">Butir Pertanyaan</th>
                                    <th class="w-2/5">Pilihan Jawaban (A, B, C, D)</th>
                                    <th class="w-24 text-center">Kunci</th>
                                    <th class="w-16 text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody id="container-soal" class="divide-y divide-base-200">
                                <tr class="hover:bg-base-200/20 transition dynamic-row align-top" data-index="0">
                                    <td class="text-center font-bold text-base-content/30 row-number pt-5">1</td>

                                    <td class="p-3">
                                        <textarea name="soal[0][pertanyaan]" rows="3"
                                            class="textarea textarea-primary textarea-sm w-full leading-relaxed"
                                            placeholder="Ketik butir soal ujian di sini..." required></textarea>
                                    </td>

                                    <td class="p-3">
                                        <div class="grid grid-cols-2 gap-2">
                                            @foreach(['A','B','C','D'] as $opsi)
                                            <div class="join join-horizontal w-full">
                                                <span class="join-item bg-primary text-primary-content border border-primary text-xs font-bold px-2.5 flex items-center">{{ $opsi }}</span>
                                                <input type="text" name="soal[0][opsi][{{ $opsi }}]"
                                                    class="input input-primary input-sm join-item w-full"
                                                    placeholder="Isi opsi {{ $opsi }}" required />
                                            </div>
                                            @endforeach
                                        </div>
                                    </td>

                                    <td class="p-3">
                                        <select name="soal[0][jawaban_benar]"
                                            class="select select-primary select-sm w-full text-center font-bold" required>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                            <option value="D">D</option>
                                        </select>
                                    </td>

                                    <td class="text-center p-3">
                                        <button type="button"
                                            class="btn btn-sm btn-ghost text-error hover:bg-error/10 btn-square p-1"
                                            onclick="deleteRow(this)" title="Hapus baris ini">
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

                {{-- Action bar bawah --}}
                <div class="flex justify-between items-center">
                    <button type="button" onclick="addRow()"
                        class="btn btn-sm btn-outline btn-secondary gap-2 font-medium">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Baris Soal
                    </button>

                    <button type="submit" class="btn btn-primary btn-sm px-6 font-semibold gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                            stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        Simpan Semua Soal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Template HTML baris baru — dipisah sebagai fungsi agar tidak duplikasi kode
        function getBarisSoalHTML() {
            return `
                <td class="text-center font-bold text-base-content/30 row-number pt-5"></td>
                <td class="p-3">
                    <textarea name="soal[TEMP][pertanyaan]" rows="3"
                        class="textarea textarea-primary textarea-sm w-full leading-relaxed"
                        placeholder="Ketik butir soal ujian di sini..." required></textarea>
                </td>
                <td class="p-3">
                    <div class="grid grid-cols-2 gap-2">
                        ${['A','B','C','D'].map(opsi => `
                        <div class="join join-horizontal w-full">
                            <span class="join-item bg-primary text-primary-content border border-primary text-xs font-bold px-2.5 flex items-center">${opsi}</span>
                            <input type="text" name="soal[TEMP][opsi][${opsi}]"
                                class="input input-primary input-sm join-item w-full"
                                placeholder="Isi opsi ${opsi}" required />
                        </div>`).join('')}
                    </div>
                </td>
                <td class="p-3">
                    <select name="soal[TEMP][jawaban_benar]"
                        class="select select-primary select-sm w-full text-center font-bold" required>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                </td>
                <td class="text-center p-3">
                    <button type="button"
                        class="btn btn-sm btn-ghost text-error hover:bg-error/10 btn-square p-1"
                        onclick="deleteRow(this)" title="Hapus baris ini">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </button>
                </td>
            `;
        }

        function addRow() {
            const container = document.getElementById('container-soal');
            const newRow = document.createElement('tr');
            newRow.className = "hover:bg-base-200/20 transition dynamic-row align-top";
            newRow.innerHTML = getBarisSoalHTML();
            container.appendChild(newRow);
            updateFormStructures();
        }

        function deleteRow(button) {
            const row = button.closest('tr');
            const container = document.getElementById('container-soal');
            if (container.querySelectorAll('.dynamic-row').length > 1) {
                row.remove();
                updateFormStructures();
            } else {
                alert('Harus menyisakan minimal 1 baris soal.');
            }
        }

        function updateFormStructures() {
            const rows = document.querySelectorAll('#container-soal .dynamic-row');
            rows.forEach((row, index) => {
                row.querySelector('.row-number').innerText = index + 1;
                row.setAttribute('data-index', index);
                row.querySelector(`textarea[name^="soal"]`).setAttribute('name', `soal[${index}][pertanyaan]`);
                row.querySelector(`input[name$="[opsi][A]"]`).setAttribute('name', `soal[${index}][opsi][A]`);
                row.querySelector(`input[name$="[opsi][B]"]`).setAttribute('name', `soal[${index}][opsi][B]`);
                row.querySelector(`input[name$="[opsi][C]"]`).setAttribute('name', `soal[${index}][opsi][C]`);
                row.querySelector(`input[name$="[opsi][D]"]`).setAttribute('name', `soal[${index}][opsi][D]`);
                row.querySelector(`select[name$="[jawaban_benar]"]`).setAttribute('name', `soal[${index}][jawaban_benar]`);
            });
        }

        document.addEventListener("DOMContentLoaded", updateFormStructures);
    </script>
</x-app-layout>