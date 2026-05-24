<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                <i class="bi bi-plus-circle text-indigo-500 mr-2"></i> Buat Paket Ujian
            </h2>
            <a href="{{ route('guru.paket-ujian.index') }}" class="btn btn-sm btn-ghost">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="card bg-white shadow-xl border border-gray-100 rounded-2xl">
                <div class="card-body p-8">
                    <form action="{{ route('guru.paket-ujian.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Nama Paket -->
                            <div class="form-control w-full md:col-span-2">
                                <label class="label"><span class="label-text font-bold text-gray-700">Nama Paket Ujian</span></label>
                                <input type="text" name="nama" placeholder="Contoh: Tryout Soshum #1" class="input input-bordered w-full rounded-xl focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('nama') }}" required />
                                @error('nama') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Mata Pelajaran -->
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text font-bold text-gray-700">Mata Pelajaran</span></label>
                                <select name="mata_pelajaran_id" class="select select-bordered w-full rounded-xl" required>
                                    <option disabled selected>Pilih Mata Pelajaran...</option>
                                    @foreach($mapels as $mapel)
                                        <option value="{{ $mapel->id }}" {{ old('mata_pelajaran_id') == $mapel->id ? 'selected' : '' }}>{{ $mapel->nama }}</option>
                                    @endforeach
                                </select>
                                @error('mata_pelajaran_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Durasi -->
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text font-bold text-gray-700">Durasi Pengerjaan (Menit)</span></label>
                                <input type="number" name="durasi" placeholder="Misal: 90" class="input input-bordered w-full rounded-xl" value="{{ old('durasi') }}" required />
                                @error('durasi') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Tanggal Mulai -->
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text font-bold text-gray-700">Waktu Mulai</span></label>
                                <input type="datetime-local" name="tanggal_mulai" class="input input-bordered w-full rounded-xl" value="{{ old('tanggal_mulai') }}" required />
                                @error('tanggal_mulai') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Tanggal Selesai -->
                            <div class="form-control w-full">
                                <label class="label"><span class="label-text font-bold text-gray-700">Waktu Selesai</span></label>
                                <input type="datetime-local" name="tanggal_selesai" class="input input-bordered w-full rounded-xl" value="{{ old('tanggal_selesai') }}" required />
                                @error('tanggal_selesai') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Opsi Acak -->
                            <div class="form-control w-full">
                                <label class="cursor-pointer label justify-start gap-3 mt-4">
                                    <input type="checkbox" name="acak_soal" value="1" class="checkbox checkbox-primary rounded-lg" {{ old('acak_soal') ? 'checked' : '' }} />
                                    <span class="label-text font-medium text-gray-700">Acak Urutan Soal saat Ujian</span>
                                </label>
                            </div>
                            
                            <div class="form-control w-full">
                                <label class="cursor-pointer label justify-start gap-3 mt-4">
                                    <input type="checkbox" name="acak_jawaban" value="1" class="checkbox checkbox-primary rounded-lg" {{ old('acak_jawaban') ? 'checked' : '' }} />
                                    <span class="label-text font-medium text-gray-700">Acak Pilihan Jawaban (A/B/C/D)</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="btn bg-indigo-600 hover:bg-indigo-700 text-white border-none shadow-lg shadow-indigo-200 px-8 rounded-xl">
                                Simpan & Lanjut Kelola Soal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
