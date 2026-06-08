@props(['page', 'header', 'data'])

<div class="card w-full bg-base-100 shadow-sm">
    <div class="card-body">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            @if(isset($page))
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-800">Manajemen {{ $page }}</h1>
            </div>
            <div>
                <x-primary-button onclick="openCreateModal()">
                    Tambah {{ $page }}
                </x-primary-button>
            </div>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr>
                        {{ $header }}
                    </tr>
                </thead>
                <tbody>
                    {{ $slot }}
                </tbody>
            </table>
        </div>
    </div>
</div>