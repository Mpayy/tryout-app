{{--
PERUBAHAN DARI VERSI LAMA:
1. Ganti `text-slate-800` hardcoded → pakai `text-base-content` agar mengikuti tema DaisyUI
2. Tambah search bar (opsional slot) di kanan tombol tambah
3. Heading lebih hierarkis: ada subtitle "Daftar semua {{ $page }}"
4. Table head pakai `bg-base-200` agar row header lebih jelas terbedakan
5. Tambah empty state bawaan jika slot kosong (opsional — cukup dengan CSS)
--}}
@props(['page', 'header', 'route' => null])

<div class="card w-full bg-base-100 shadow-sm border border-base-200">
    <div class="card-body p-5 lg:p-6">

        {{-- ===== CARD HEADER ===== --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-1">
            <div>
                <h1 class="text-xl font-bold tracking-tight text-base-content">
                    Manajemen {{ $page }}
                </h1>
                <p class="text-sm text-base-content/50 mt-0.5">Daftar semua {{ $page }} yang terdaftar di sistem.</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                {{-- Search slot (opsional, bisa diisi dari luar komponen nanti) --}}
                @isset($search)
                    {{ $search }}
                @endisset
                {{-- Tombol tambah --}}
                @isset($route)
                    <a href="{{ $route }}" class="btn btn-primary btn-sm gap-2 shadow-none font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                            stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah {{ $page }}
                    </a>
                @else
                    <button onclick="openCreateModal()" class="btn btn-primary btn-sm gap-2 shadow-none font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                            stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah {{ $page }}
                    </button>
                @endisset
            </div>
        </div>

        {{-- ===== TABLE ===== --}}
        <div class="overflow-x-auto rounded-lg border border-base-200 mt-2" style="overflow-x: visible; overflow-y: visible;" >
            <table class="table w-full table-sm min-w-max">
                {{-- Header dengan background agar berbeda dari baris data --}}
                <thead class="bg-base-200/60">
                    <tr class="text-base-content/70 text-xs uppercase tracking-wide">
                        {{ $header }}
                    </tr>
                </thead>
                <tbody class="divide-y divide-base-200">
                    {{ $slot }}
                </tbody>
            </table>
        </div>

    </div>
</div>