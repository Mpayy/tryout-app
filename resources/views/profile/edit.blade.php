<x-app-layout>
    <div class="space-y-6">

        {{-- ═══════════════════════════════════════════
        HEADER
        ════════════════════════════════════════════ --}}
        <div>
            <h1 class="text-xl font-bold tracking-tight text-base-content">Pengaturan Profil</h1>
            <p class="text-sm text-base-content/50 mt-0.5">Perbarui informasi pribadi, foto profil, dan keamanan akun.
            </p>
        </div>

        {{-- ═══════════════════════════════════════════
        ALERT SUKSES / ERROR
        ════════════════════════════════════════════ --}}
        @if(session('status') === 'profile-updated')
            <div role="alert" class="alert alert-success alert-soft text-sm gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="size-5 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
                Profil berhasil diperbarui.
            </div>
        @endif

        @if(session('status') === 'password-updated')
            <div role="alert" class="alert alert-success alert-soft text-sm gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="size-5 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
                Password berhasil diperbarui.
            </div>
        @endif

        {{-- ═══════════════════════════════════════════
        FORM PROFIL
        ════════════════════════════════════════════ --}}
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                {{-- ─── Kolom kiri: Avatar card ─────────── --}}
                <div class="card bg-base-100 border border-base-200 shadow-sm">
                    <div class="card-body p-6 flex flex-col items-center text-center gap-4">

                        {{-- Avatar + tombol ganti foto --}}
                        <div class="relative group">
                            <div class="avatar">
                                <div
                                    class="w-28 h-28 rounded-full ring-2 ring-primary/20 ring-offset-2 ring-offset-base-100 overflow-hidden bg-base-200">
                                    @php
                                        $fotoPath = auth()->user()->hasRole('guru')
                                            ? auth()->user()->profileGuru?->foto
                                            : auth()->user()->profileSiswa?->foto;
                                    @endphp
                                    <img id="avatar_preview" src="{{ $fotoPath
    ? asset('storage/' . $fotoPath)
    : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=EEF2FF&color=4F46E5' }}"
                                        alt="Foto Profil" class="object-cover w-full h-full" />
                                </div>
                            </div>

                            {{-- Tombol kamera --}}
                            <label for="foto_input" class="absolute bottom-0.5 right-0.5 w-8 h-8 bg-primary hover:bg-primary/80
                                   text-primary-content rounded-full shadow-md flex items-center justify-center
                                   cursor-pointer transition-colors" title="Ganti foto profil">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                                </svg>
                            </label>
                            <input type="file" id="foto_input" name="foto" class="hidden" accept="image/*"
                                onchange="previewImage(this)" />
                        </div>

                        {{-- Nama + role --}}
                        <div>
                            <h3 class="text-base font-bold text-base-content leading-tight">
                                {{ auth()->user()->name }}
                            </h3>
                            <span
                                class="badge badge-primary badge-sm font-semibold uppercase tracking-wider mt-1.5 px-3">
                                {{ auth()->user()->roles->pluck('name')->implode(', ') }}
                            </span>
                        </div>

                        {{-- Info tambahan read-only (kelas/mapel) --}}
                        @if(auth()->user()->hasRole('guru'))
                            <div class="w-full bg-base-200/50 border border-base-200 rounded-xl px-4 py-3 text-left">
                                <span
                                    class="text-[10px] font-bold uppercase tracking-wider text-base-content/40 block mb-1">
                                    Mata Pelajaran
                                </span>
                                <p class="text-sm font-medium text-base-content/70">
                                    {{ auth()->user()->profileGuru?->mataPelajarans->pluck('nama')->implode(', ') ?: '—' }}
                                </p>
                            </div>
                        @else
                            <div class="w-full bg-base-200/50 border border-base-200 rounded-xl px-4 py-3 text-left">
                                <span
                                    class="text-[10px] font-bold uppercase tracking-wider text-base-content/40 block mb-1">
                                    Kelas
                                </span>
                                <p class="text-sm font-medium text-base-content/70">
                                    {{ auth()->user()->profileSiswa?->kelas?->nama ?: '—' }}
                                </p>
                            </div>
                        @endif

                        <p class="text-[10px] text-base-content/30 leading-relaxed">
                            Foto maks. 2MB. Format JPG, PNG, atau WebP.
                        </p>
                    </div>
                </div>

                {{-- ─── Kolom kanan: Form informasi akun ── --}}
                <div class="md:col-span-2 card bg-base-100 border border-base-200 shadow-sm">
                    <div class="card-body p-5 lg:p-6 space-y-5">

                        <div class="border-b border-base-200 pb-3">
                            <h2 class="text-sm font-bold text-base-content">Informasi Akun</h2>
                            <p class="text-xs text-base-content/40 mt-0.5">Perbarui nama dan email akun Anda.</p>
                        </div>

                        {{-- Error validasi --}}
                        @if($errors->any() && !$errors->has('current_password') && !$errors->has('password'))
                            <div class="space-y-1.5">
                                @foreach($errors->all() as $error)
                                    <div role="alert" class="alert alert-error alert-soft py-2 text-xs">{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Nama --}}
                            <div class="form-control">
                                <label class="floating-label">
                                    <span class="label-text font-semibold text-base-content/70 text-sm">Nama
                                        Lengkap</span>
                                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                                        placeholder="Masukkan nama lengkap"
                                        class="input input-primary w-full @error('name') input-error @enderror"
                                        required />
                                </label>
                            </div>

                            {{-- Email --}}
                            <div class="form-control">
                                <label class="floating-label">
                                    <span class="label-text font-semibold text-base-content/70 text-sm">Alamat
                                        Email</span>
                                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                                        placeholder="nama@email.com"
                                        class="input input-primary w-full @error('email') input-error @enderror"
                                        readonly />
                                </label>
                            </div>

                            {{-- Field khusus Guru --}}
                            @if(auth()->user()->hasRole('guru'))

                                <div class="form-control">
                                    <label class="floating-label">
                                        <span class="label-text font-semibold text-base-content/70 text-sm">NIP</span>
                                        <input type="text" name="nip"
                                            value="{{ old('nip', auth()->user()->profileGuru?->nip) }}"
                                            placeholder="Nomor Induk Pegawai" class="input input-primary w-full" readonly />
                                    </label>
                                </div>

                                {{-- Mapel — read only, diset admin --}}
                                <div class="form-control">
                                    <label class="label py-0 pb-1.5">
                                        <span class="text-xs font-semibold text-base-content/40 flex items-center gap-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor" class="size-3.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                            </svg>
                                            Mata Pelajaran
                                            <span class="text-[10px] text-base-content/30 font-normal">(diatur admin)</span>
                                        </span>
                                    </label>
                                    <div
                                        class="bg-base-200/50 border border-base-200 rounded-xl px-3.5 py-2.5 text-sm text-base-content/50 font-medium min-h-10 flex items-center">
                                        {{ auth()->user()->profileGuru?->mataPelajarans->pluck('nama')->implode(', ') ?: 'Belum diplot oleh admin' }}
                                    </div>
                                </div>

                                {{-- Field khusus Siswa --}}
                            @else

                                <div class="form-control">
                                    <label class="floating-label">
                                        <span class="label-text font-semibold text-base-content/70 text-sm">NIS</span>
                                        <input type="text" name="nis"
                                            value="{{ old('nis', auth()->user()->profileSiswa?->nis) }}"
                                            placeholder="Nomor Induk Siswa" class="input input-primary w-full" readonly />
                                    </label>
                                </div>

                                {{-- Kelas — read only, diset admin --}}
                                <div class="form-control">
                                    <label class="label py-0 pb-1.5">
                                        <span class="text-xs font-semibold text-base-content/40 flex items-center gap-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor" class="size-3.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                            </svg>
                                            Kelas
                                            <span class="text-[10px] text-base-content/30 font-normal">(diatur admin)</span>
                                        </span>
                                    </label>
                                    <div
                                        class="bg-base-200/50 border border-base-200 rounded-xl px-3.5 py-2.5 text-sm text-base-content/50 font-medium min-h-10 flex items-center">
                                        {{ auth()->user()->profileSiswa?->kelas?->nama ?: 'Belum dimasukkan ke kelas' }}
                                    </div>
                                </div>

                            @endif
                        </div>

                        {{-- Tombol simpan --}}
                        <div class="flex justify-end pt-3 border-t border-base-200">
                            <button type="submit" class="btn btn-primary btn-sm px-6 font-semibold gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- ═══════════════════════════════════════════
        FORM GANTI PASSWORD
        ════════════════════════════════════════════ --}}
        <div class="card bg-base-100 border border-base-200 shadow-sm">
            <div class="card-body p-5 lg:p-6 space-y-5">

                <div class="border-b border-base-200 pb-3">
                    <h2 class="text-sm font-bold text-base-content">Keamanan Akun</h2>
                    <p class="text-xs text-base-content/40 mt-0.5">Ganti password secara berkala untuk menjaga keamanan
                        akun.</p>
                </div>

                {{-- Error password --}}
                @if($errors->has('current_password') || $errors->has('password'))
                    <div class="space-y-1.5">
                        @foreach($errors->get('current_password') as $err)
                            <div role="alert" class="alert alert-error alert-soft py-2 text-xs">{{ $err }}</div>
                        @endforeach
                        @foreach($errors->get('password') as $err)
                            <div role="alert" class="alert alert-error alert-soft py-2 text-xs">{{ $err }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                        <div class="form-control">
                            <label class="floating-label">
                                <span class="label-text font-semibold text-base-content/70 text-sm">Password Saat
                                    Ini</span>
                                <input type="password" name="current_password" placeholder="Password saat ini"
                                    class="input input-primary w-full @error('current_password') input-error @enderror"
                                    autocomplete="current-password" required />
                            </label>
                        </div>

                        <div class="form-control">
                            <label class="floating-label">
                                <span class="label-text font-semibold text-base-content/70 text-sm">Password Baru</span>
                                <input type="password" name="password" placeholder="Minimal 8 karakter"
                                    class="input input-primary w-full @error('password') input-error @enderror"
                                    autocomplete="new-password" required />
                            </label>
                        </div>

                        <div class="form-control">
                            <label class="floating-label">
                                <span class="label-text font-semibold text-base-content/70 text-sm">Konfirmasi Password
                                    Baru</span>
                                <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                                    class="input input-primary w-full" autocomplete="new-password" required />
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 mt-1 border-t border-base-200">
                        <button type="submit" class="btn btn-neutral btn-sm px-6 font-semibold gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            Ganti Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('avatar_preview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>