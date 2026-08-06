<div class="w-full px-2 pt-3 relative">
    <button onclick="document.getElementById('user-menu').classList.toggle('hidden')"
        class="flex items-center justify-center w-12 h-12 mx-auto rounded-3xl bg-secondary hover:rounded-xl transition-all duration-200 overflow-hidden cursor-pointer">
        @if (auth()->user()->avatar)
            <img src="{{ auth()->user()->avatar }}" class="w-full h-full object-cover">
        @else
            <span class="font-mono text-sm font-semibold text-secondary-foreground">
                {{ substr(auth()->user()->name, 0, 1) }}
            </span>
        @endif
    </button>

    <div id="user-menu"
        class="hidden absolute bottom-2 left-16 bg-card border border-border rounded-lg shadow-lg py-1 z-50">
        <div class="px-3 py-2 border-b border-border flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-card-foreground truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-muted-foreground truncate">{{ auth()->user()->email }}</p>
            </div>
            <button
                onclick="document.documentElement.classList.toggle('dark'); localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';"
                class="p-2 rounded-3xl hover:bg-muted text-muted-foreground hover:text-foreground transition">
                <x-lucide-sun class="w-4 h-4 dark:hidden" />
                <x-lucide-moon class="w-4 h-4 hidden dark:block" />
            </button>

        </div>
        <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm text-foreground hover:bg-muted">Profile</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-3 py-2 text-sm text-destructive hover:bg-muted">Log
                out</button>
        </form>
    </div>
</div>
