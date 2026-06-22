<x-app-layout>
    <div class="space-y-6">
        <x-data-tabel :route="route('guru.soal.create')">
            <x-slot name="page">Soal</x-slot>

            <x-slot name="header">
                <th class="w-12 text-center">No</th>
                <th class="w-36">Mata Pelajaran</th>
                <th class="min-w-[250px] max-w-md">Pertanyaan</th>
                <th class="w-48">Pilihan Jawaban (Hover)</th>
                <th class="w-24 text-center">Kunci</th>
                <th class="w-32 text-center">Aksi</th>
            </x-slot>

            @forelse($soals as $index => $soal)
                <tr class="hover align-top">
                    <td class="text-center font-medium">
                        {{ ($soals->currentPage() - 1) * $soals->perPage() + $loop->iteration }}
                    </td>

                    <td>
                        <div class="badge badge-neutral badge-sm font-semibold whitespace-nowrap">
                            {{ $soal->mataPelajaran->nama ?? '-' }}
                        </div>
                    </td>

                    <td class="max-w-md">
                        <div class="line-clamp-2 text-sm font-medium text-base-content" title="{{ $soal->konten }}">
                            {{ $soal->konten }}
                        </div>
                    </td>

                    <td>
                        <div class="dropdown dropdown-hover dropdown-right dropdown-end">
                            <div tabindex="0" role="button" class="btn btn-xs btn-ghost gap-1 text-primary normal-case">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                </svg>
                                Lihat Opsi
                            </div>
                            <ul tabindex="-1"
                                class="dropdown-content menu p-3 shadow-xl bg-base-100 rounded-box w-64 z-1 border border-base-200">
                                @foreach($soal->pilihanJawaban as $pilihan)
                                    <li class="text-xs py-0.5 border-b border-base-100 last:border-none">
                                        <span class="p-1 block">
                                            <b class="text-primary">{{ $pilihan->label }}.</b>
                                            {{ Str::limit($pilihan->konten, 20) }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </td>

                    <td class="text-center">
                        <div class="tooltip tooltip-primary" data-tip="{{ Str::limit($soal->jawabanBenar->konten, 20) }}">
                            <span class="badge badge-success text-success-content font-bold px-2.5">
                                {{ $soal->jawabanBenar->label }}
                            </span>
                        </div>
                    </td>

                    <td class="text-center">
                        <div class="flex items-center justify-center gap-1">
                            <button type="button" class="btn btn-xs btn-warning text-warning-content shadow-none"
                                onclick="openEditModal( {{ $soal->load('pilihanJawaban', 'jawabanBenar') }} )">
                                Edit
                            </button>

                            <form action="{{ route('guru.soal.destroy', $soal) }}" method="POST"
                                onsubmit="return confirm('Hapus soal ini?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-outline btn-error shadow-none">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <div class="flex flex-col items-center gap-3 py-16 text-center">
                            <div
                                class="w-16 h-16 rounded-full bg-base-200 border border-base-300 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" class="w-7 h-7 text-base-content/30">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-base-content/70">Belum ada Soal</p>
                                <p class="text-sm text-base-content/40 mt-0.5">Klik "Tambah Soal" untuk membuat soal baru.
                                </p>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-data-tabel>
        {{ $soals->links() }}
    </div>

    <x-form-modal>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <div class="form-control w-full col-span-1 sm:col-span-2">
                <label class="label"><span class="label-text font-semibold text-slate-700 text-sm">
                        Pertanyaan</span></label>
                <input type="text" id="edit_pertanyaan" name="pertanyaan" value="{{ old('pertanyaan') }}"
                    placeholder="Masukkan pertanyaan" class="input input-neutral w-full" required />
            </div>

            <div class="form-control w-full">
                <label class="label"><span class="label-text font-semibold text-slate-700 text-sm">Opsi A</span></label>
                <input type="text" id="edit_opsi_a" name="opsi_a" value="{{ old('opsi_a') }}"
                    placeholder="Masukkan opsi A" class="input input-neutral" required />
            </div>

            <div class="form-control w-full">
                <label class="label"><span class="label-text font-semibold text-slate-700 text-sm">Opsi B</span></label>
                <input type="text" id="edit_opsi_b" name="opsi_b" value="{{ old('opsi_b') }}"
                    placeholder="Masukkan opsi B" class="input input-neutral" required />
            </div>

            <div class="form-control w-full">
                <label class="label"><span class="label-text font-semibold text-slate-700 text-sm">Opsi C</span></label>
                <input type="text" id="edit_opsi_c" name="opsi_c" value="{{ old('opsi_c') }}"
                    placeholder="Masukkan opsi C" class="input input-neutral" required />
            </div>

            <div class="form-control w-full">
                <label class="label"><span class="label-text font-semibold text-slate-700 text-sm">Opsi D</span></label>
                <input type="text" id="edit_opsi_d" name="opsi_d" value="{{ old('opsi_d') }}"
                    placeholder="Masukkan opsi D" class="input input-neutral" required />
            </div>

            <div class="form-control w-full">
                <label class="label"><span class="label-text font-semibold text-slate-700 text-sm">Kunci
                        Jawaban</span></label>
                <select id="edit_jawaban_benar" name="jawaban_benar" class="select select-neutral"
                    value="{{ old('jawaban_benar') }}" required>
                    <option value="" disabled selected>-- Pilih Kunci Jawaban --</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
            </div>
        </div>
    </x-form-modal>

    <script>
        const modal = document.getElementById('modal')
        const form = document.getElementById('form')
        const modalTitle = document.getElementById('modal_title')
        const method = document.getElementById('method')

        function openEditModal(soal) {
            modalTitle.innerText = 'Edit Soal'
            form.action = `{{ url('guru/soal') }}/` + soal.id
            method.innerHTML = `@method('PUT')`
            document.getElementById('edit_pertanyaan').value = soal.konten;
            soal.pilihan_jawaban.forEach(jawaban => {
                if (jawaban.label === 'A') document.getElementById('edit_opsi_a').value = soal.pilihan_jawaban[0].konten;
                if (jawaban.label === 'B') document.getElementById('edit_opsi_b').value = soal.pilihan_jawaban[1].konten;
                if (jawaban.label === 'C') document.getElementById('edit_opsi_c').value = soal.pilihan_jawaban[2].konten;
                if (jawaban.label === 'D') document.getElementById('edit_opsi_d').value = soal.pilihan_jawaban[3].konten;

                // Jika baris jawaban ini adalah yang benar, otomatis pilih di dropdown select
                if (jawaban.is_correct == 1) {
                    document.getElementById('edit_jawaban_benar').value = jawaban.label;
                }
            });

            modal.showModal()
        }

        function closeModal() {
            modal.close()
        }
    </script>
</x-app-layout>