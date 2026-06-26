<dialog id="modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box w-full sm:max-w-lg">

        {{-- Header modal --}}
        <div class="flex items-center justify-between pb-4 border-b border-base-200 mb-5">
            <h3 id="modal_title" class="text-lg font-bold text-base-content"></h3>
            <button type="button" onclick="closeModal()"
                class="btn btn-sm btn-circle btn-ghost text-base-content/50">✕</button>
        </div>

        {{-- Form --}}
        <form id="form" method="POST" action="" class="space-y-4">
            @csrf
            <div id="method"></div>

            {{-- Konten form dari tiap halaman --}}
            {{ $slot }}

            {{-- Action buttons --}}
            <div class="modal-action pt-4 border-t border-base-200 mt-5">
                <button type="button" onclick="closeModal()" class="btn btn-ghost btn-sm font-medium">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary btn-sm font-semibold gap-2 px-5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                        stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>

    {{-- Backdrop --}}
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>