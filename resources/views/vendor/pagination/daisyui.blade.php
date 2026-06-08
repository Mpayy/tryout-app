@if ($paginator->hasPages())
    <div class="flex justify-end mt-3">
        <div class="join">
            {{-- Tombol Previous («) --}}
            @if ($paginator->onFirstPage())
                <button class="join-item btn btn-disabled" aria-disabled="true">«</button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="join-item btn btn-primary" rel="prev">«</a>
            @endif

            {{-- Elemen Angka Halaman --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <button class="join-item btn btn-disabled" aria-disabled="true">{{ $element }}</button>
                @endif

                {{-- Array Link Angka --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <button class="join-item btn btn-secondary" aria-current="page">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}" class="join-item btn btn-primary">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Next (») --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="join-item btn btn-primary" rel="next">»</a>
            @else
                <button class="join-item btn btn-disabled" aria-disabled="true">»</button>
            @endif
        </div>
    </div>
@endif