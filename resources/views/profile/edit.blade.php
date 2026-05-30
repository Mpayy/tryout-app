<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6 py-4">
        
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">Pengaturan Profil</h1>
            <p class="text-sm text-slate-500">Perbarui informasi pribadi, foto profil, dan keamanan akun Anda.</p>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="card bg-white border border-slate-200/80 shadow-sm rounded-xl p-6 text-center flex flex-col items-center justify-center">
                    <div class="relative group">
                        <div class="avatar">
                            <div class="w-32 h-32 rounded-full ring ring-indigo-100 ring-offset-2 overflow-hidden bg-slate-100">
                                @php
                                    // Cek foto berdasarkan role
                                    $fotoPath = auth()->user()->hasRole('guru') 
                                        ? auth()->user()->profileGuru?->foto 
                                        : auth()->user()->profileSiswa?->foto;
                                @endphp
                                <img id="avatar_preview" 
                                     src="{{ $fotoPath ? asset('storage/' . $fotoPath) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=EEF2FF&color=4F46E5' }}" 
                                     alt="Foto Profil" 
                                     class="object-cover" />
                            </div>
                        </div>
                        <label for="foto_input" class="absolute bottom-0 right-0 bg-indigo-600 hover:bg-indigo-700 text-white p-2.5 rounded-full shadow-md cursor-pointer transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                            </svg>
                        </label>
                        <input type="file" id="foto_input" name="foto" class="hidden" accept="image/*" onchange="previewImage(this)" />
                    </div>

                    <h3 class="text-base font-bold text-slate-800 mt-4 leading-tight">{{ auth()->user()->name }}</h3>
                    <span class="badge bg-indigo-50 border border-indigo-100 text-indigo-600 font-semibold text-xs mt-1.5 px-2.5 py-1.5 uppercase tracking-wide rounded-md">
                        {{ auth()->user()->roles->pluck('name')->implode(', ') }}
                    </span>
                </div>

                <div class="md:col-span-2 card bg-white border border-slate-200/80 shadow-sm rounded-xl p-6 space-y-4">
                    <h2 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3">Informasi Akun</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label py-1"><span class="label-text font-semibold text-slate-600 text-xs">Nama Lengkap</span></label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="input input-bordered input-sm rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" required />
                        </div>

                        <div class="form-control">
                            <label class="label py-1"><span class="label-text font-semibold text-slate-600 text-xs">Alamat Email</span></label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="input input-bordered input-sm rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" required />
                        </div>

                        @if(auth()->user()->hasRole('guru'))
                            <div class="form-control">
                                <label class="label py-1"><span class="label-text font-semibold text-slate-600 text-xs">NIP (Nomor Induk Pegawai)</span></label>
                                <input type="text" name="nip" value="{{ old('nip', auth()->user()->profileGuru?->nip) }}" class="input input-bordered input-sm rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>

                            <div class="form-control">
                                <label class="label py-1">
                                    <span class="label-text font-semibold text-slate-400 text-xs flex items-center gap-1">
                                        🔒 Mata Pelajaran Diampu <span class="text-[10px] text-slate-400 font-normal">(Admin Only)</span>
                                    </span>
                                </label>
                                <div class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-sm text-slate-500 font-medium">
                                    {{ auth()->user()->profileGuru?->mataPelajarans->pluck('nama')->implode(', ') ?: 'Belum diplot oleh admin' }}
                                </div>
                            </div>

                        @else
                            <div class="form-control">
                                <label class="label py-1"><span class="label-text font-semibold text-slate-600 text-xs">NIS (Nomor Induk Siswa)</span></label>
                                <input type="text" name="nis" value="{{ old('nis', auth()->user()->profileSiswa?->nis) }}" class="input input-bordered input-sm rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>

                            <div class="form-control">
                                <label class="label py-1">
                                    <span class="label-text font-semibold text-slate-400 text-xs flex items-center gap-1">
                                        🔒 Kelas Saat Ini <span class="text-[10px] text-slate-400 font-normal">(Admin Only)</span>
                                    </span>
                                </label>
                                <div class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-sm text-slate-500 font-medium">
                                    {{ auth()->user()->profileSiswa?->kelas?->nama_kelas ?: 'Belum dimasukkan ke kelas' }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-end pt-2 border-t border-slate-100">
                        <button type="submit" class="btn btn-sm bg-indigo-600 hover:bg-indigo-700 text-white border-none rounded-lg px-4 font-semibold normal-case shadow-sm">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <div class="card bg-white border border-slate-200/80 shadow-sm rounded-xl p-6">
            <h2 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3">Perbarui Password</h2>
            
            <form action="{{ route('password.update') }}" method="POST" class="space-y-4 mt-4">
                @csrf
                @method('put')

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="form-control">
                        <label class="label py-1"><span class="label-text font-semibold text-slate-600 text-xs">Password Saat Ini</span></label>
                        <input type="password" name="current_password" class="input input-bordered input-sm rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" required />
                    </div>
                    
                    <div class="form-control">
                        <label class="label py-1"><span class="label-text font-semibold text-slate-600 text-xs">Password Baru</span></label>
                        <input type="password" name="password" class="input input-bordered input-sm rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" required />
                    </div>

                    <div class="form-control">
                        <label class="label py-1"><span class="label-text font-semibold text-slate-600 text-xs">Konfirmasi Password Baru</span></label>
                        <input type="password" name="password_confirmation" class="input input-bordered input-sm rounded-lg border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" required />
                    </div>
                </div>

                <div class="flex justify-end pt-2 border-t border-slate-100">
                    <button type="submit" class="btn btn-sm bg-slate-800 hover:bg-slate-900 text-white border-none rounded-lg px-4 font-semibold normal-case shadow-sm">
                        Ganti Password
                    </button>
                </div>
            </form>
        </div>

    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar_preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>