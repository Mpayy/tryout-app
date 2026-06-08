{{-- <nav class="navbar w-full bg-base-100 shadow-sm">
    <label for="my-drawer-1" class="btn drawer-button"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
            stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor"
            class="my-1.5 inline-block size-4">
            <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
            <path d="M9 4v16"></path>
            <path d="M14 10l2 2l-2 2"></path>
        </svg>
    </label>
    <div class="flex-1 px-2">
        @role('admin')
        <a href="{{ route('admin.dashboard') }}">Tryout App</a>
        @endrole
        @role('guru')
        <a href="{{ route('guru.dashboard') }}" class="btn btn-ghost text-xl">Tryout App</a>
        @endrole
        @role('siswa')
        <a href="{{ route('siswa.dashboard') }}" class="btn btn-ghost text-xl">Tryout App</a>
        @endrole
    </div>
    <div class="flex-none">
        <div class="dropdown dropdown-end">
            <button tabindex="0" class="btn btn-square btn-ghost">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    class="inline-block h-5 w-5 stroke-current">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z">
                    </path>
                </svg>
            </button>
            <ul tabindex="-1" class="dropdown-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </ul>

        </div>
    </div>
</nav> --}}
<nav class="navbar w-full bg-base-100">
    <label for="my-drawer-4" aria-label="open sidebar" class="btn btn-square btn-ghost mr-2">
        <!-- Sidebar toggle icon -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            class="inline-block h-5 w-5 stroke-current">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </label>
    <div class="flex-1 text-2xl font-bold">
        <div>Tryout App</div>
    </div>
    {{-- <div class="flex-none">
        <ul class="menu menu-horizontal px-1">
            <li>
                <details>
                    <summary>{{ Auth::user()->name }}</summary>
                    <ul class="bg-base-100 rounded-t-none p-2">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit">{{ __('Log Out') }}</button>
                            </form>
                        </li>
                    </ul>
                </details>
            </li>
        </ul>
    </div> --}}
</nav>