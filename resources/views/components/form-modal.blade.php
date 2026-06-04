<dialog id="modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box w-full max-w-3xl">
        <h3 id="modal_title" class="text-lg font-bold"></h3>
        @if ($errors->any())
            <div class="space-y-2 my-2">
                @foreach ($errors->all() as $error)
                    <div role="alert" class="alert alert-error alert-soft">
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <form id="form" method="POST" action="" class="space-y-5">
            @csrf
            <div id="method"></div>

            {{ $slot }}
            <div class="modal-action">
                <button type="submit" class="btn btn-primary btn-soft">Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>