<x-app-layout>
    <div class="container mx-auto px-4 py-8 max-w-6xl">

        {{-- Alert Success jika berhasil update --}}
        @if(session('success'))
            <div class="alert alert-success shadow-sm mb-6">
                <i class="bi bi-check-circle-fill text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-6">

            {{-- SISI KIRI: Ringkasan Profil (Avatar & Role) --}}
            <div class="lg:w-1/3 w-full">
                <div class="card bg-base-100 shadow-md border border-base-200">
                    <div class="card-body items-center text-center py-10">
                        <div class="avatar placeholder mb-4">
                            <div
                                class="w-28 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2 overflow-hidden">
                                @if($user->profileSiswa?->foto)
                                    <img src="{{ asset('storage/' . $user->profileSiswa->foto) }}"
                                        alt="Foto {{ $user->name }}" />
                                @else
                                    <span
                                        class="text-3xl font-bold bg-neutral text-neutral-content w-full h-full flex items-center justify-center">
                                        {{ substr($user->name, 0, 1) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <h2 class="card-title text-xl font-bold text-base-content mb-0">{{ $user->name }}</h2>
                        <p class="text-sm font-semibold text-primary uppercase tracking-wider mt-1">
                            {{ $user->roles->first()->name }}
                        </p>
                        <div class="badge badge-neutral badge-sm mt-2">NISN : {{ $user->profileSiswa?->nis ?? '-' }}</div>

                        <div class="divider w-full my-4"></div>

                        <div class="w-full text-left text-xs text-base-content/60 space-y-2">
                            <div><i class="bi bi-envelope mr-2"></i> Email : {{ $user->email }}</div>
                            <div><i class="bi bi-telephone mr-2"></i> Kelas : {{ $user->profileSiswa?->kelas ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SISI KANAN: Form Edit Data Detail --}}
            <div class="lg:w-2/3 w-full">
                <div class="card bg-base-100 shadow-md border border-base-200">
                    <div class="card-body p-6 sm:p-8">
                        <h3 class="text-lg font-bold text-base-content mb-4 flex items-center gap-2">
                            <i class="bi bi-person-gear text-primary"></i> Detail Informasi Profil
                        </h3>

                        <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                            @csrf
                            @method('PUT')

                            {{-- Input Upload Foto --}}
                            <div class="form-control w-full">
                                <label class="label font-medium"><span class="label-text">Ganti Foto
                                        Profil</span></label>
                                <input type="file" name="foto"
                                    class="file-input file-input-bordered file-input-primary w-full @error('foto') file-input-error @enderror" />
                                <label class="label"><span class="label-text-alt text-base-content/50">Format: JPG,
                                        JPEG, PNG (Maks. 2MB)</span></label>
                                @error('foto') <span class="text-xs text-error mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Grid Data Utama --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="form-control w-full">
                                    <label class="label font-medium"><span class="label-text">Nama
                                            Lengkap</span></label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                        class="input input-bordered focus:input-primary w-full" required />
                                    @error('name') <span class="text-xs text-error mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-control w-full">
                                    <label class="label font-medium"><span class="label-text">Email</span></label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                        class="input input-bordered focus:input-primary w-full" required />
                                    @error('email') <span class="text-xs text-error mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-control w-full">
                                    <label class="label font-medium"><span class="label-text">NISN (Nomor Induk
                                            Siswa)</span></label>
                                    <input type="text" name="nis" value="{{ old('nis', $user->profileSiswa?->nis) }}"
                                        class="input input-bordered focus:input-primary w-full"
                                        placeholder="Contoh: 1982..." />
                                    @error('nis') <span class="text-xs text-error mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-control w-full">
                                    <label class="label font-medium"><span class="label-text">Kelas</span></label>
                                    <input type="text" name="kelas"
                                        value="{{ old('kelas', $user->profileSiswa?->kelas) }}"
                                        class="input input-bordered focus:input-primary w-full"
                                        placeholder="Contoh: X RPL 2" />
                                    @error('kelas') <span class="text-xs text-error mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-control w-full">
                                    <label class="label font-medium"><span class="label-text">Jurusan</span></label>
                                    <input type="text" name="jurusan"
                                        value="{{ old('jurusan', $user->profileSiswa?->jurusan) }}"
                                        class="input input-bordered focus:input-primary w-full"
                                        placeholder="Contoh: Teknik Komputer dan Informatika" />
                                    @error('jurusan') <span class="text-xs text-error mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Tombol Submit --}}
                            <div class="card-actions justify-end pt-4 border-t border-base-200">
                                <button type="submit" class="btn btn-primary px-6 shadow-md">
                                    <i class="bi bi-save mr-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>