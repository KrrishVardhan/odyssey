<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $team->name }} — Odyssey</title>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-background text-foreground min-h-screen" x-data="{ modal: null }">

    <header class="border-b border-border px-6 py-4 flex items-center justify-between">
        <a href="/" class="font-mono text-sm font-semibold tracking-tight">odyssey</a>
        <a href="{{ route('products.index') }}" class="text-sm text-muted-foreground hover:text-foreground">All
            products</a>
    </header>

    <div class="max-w-2xl mx-auto px-6 py-20 text-center">
        <div class="w-16 h-16 rounded-2xl bg-secondary flex items-center justify-center mx-auto mb-5 overflow-hidden">
            @if ($team->icon_path)
                <img src="{{ $team->icon_path }}" class="w-full h-full object-cover">
            @else
                <span
                    class="font-mono text-lg font-semibold text-secondary-foreground">{{ substr($team->name, 0, 2) }}</span>
            @endif
        </div>
        <h1 class="text-3xl font-semibold text-foreground">{{ $team->name }}</h1>
        <p class="text-muted-foreground mt-2">{{ $team->description }}</p>
    </div>

    @if ($team->about)
        <div class="max-w-2xl mx-auto px-6 py-10 border-t border-border">
            <h2 class="text-sm font-medium text-muted-foreground uppercase tracking-wide mb-3">About</h2>
            <p class="text-foreground leading-relaxed whitespace-pre-line">{{ $team->about }}</p>
        </div>
    @endif

    <div class="max-w-2xl mx-auto px-6 py-10 border-t border-border">
        <h2 class="text-sm font-medium text-muted-foreground uppercase tracking-wide mb-1">Need to tell us something?
        </h2>
        <p class="text-sm text-muted-foreground mb-6">We read every submission.</p>

        <div class="grid grid-cols-3 gap-4">
            <button x-on:click="modal = 'bug'"
                class="flex flex-col items-center gap-2 p-5 bg-card border border-border rounded-xl hover:border-foreground/20 transition-colors">
                <x-lucide-bug class="w-8 h-8" />
                <span class="text-sm font-medium text-foreground">Report Bug</span>
            </button>
            <button x-on:click="modal = 'feature'"
                class="flex flex-col items-center gap-2 p-5 bg-card border border-border rounded-xl hover:border-foreground/20 transition-colors">
                <x-lucide-lightbulb class="w-8 h-8" />
                <span class="text-sm font-medium text-foreground">Request Feature</span>
            </button>
            <button x-on:click="modal = 'feedback'"
                class="flex flex-col items-center gap-2 p-5 bg-card border border-border rounded-xl hover:border-foreground/20 transition-colors">
                <x-lucide-messages-square class="w-8 h-8" />
                <span class="text-sm font-medium text-foreground">Share Feedback</span>
            </button>
        </div>
    </div>

    {{-- Submission modal — one shared modal, type set by which button was clicked --}}
    <div x-show="modal" x-cloak class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center px-4"
        x-on:click.self="modal = null" x-on:keydown.escape.window="modal = null">
        <div x-show="modal" class="bg-card border border-border rounded-xl shadow-xl w-full max-w-md p-6"
            x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">

            <form method="POST" action="{{ route('products.submissions.store', $team) }}">
                @csrf
                <input type="hidden" name="type" x-bind:value="modal">

                <h2 class="text-lg font-semibold text-foreground capitalize"
                    x-text="modal === 'bug' ? 'Report a bug' : (modal === 'feature' ? 'Request a feature' : 'Share feedback')">
                </h2>

                <div class="mt-5">
                    <x-input-label for="sub_title" value="Title" />
                    <x-text-input id="sub_title" name="title" type="text" class="block mt-1.5 w-full" required />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="sub_description" value="Details" />
                    <textarea id="sub_description" name="description" rows="4" required
                        class="w-full mt-1.5 rounded-lg border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring"></textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                @guest
                    <div class="mt-4">
                        <x-input-label for="sub_email" value="Email (optional — so we can follow up)" />
                        <x-text-input id="sub_email" name="email" type="email" class="block mt-1.5 w-full" />
                    </div>
                @endguest

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" x-on:click="modal = null"
                        class="px-4 py-2 text-sm text-muted-foreground hover:text-foreground transition-colors">
                        Cancel
                    </button>
                    <x-primary-button>Submit</x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-6 py-10 border-t border-border">
        <h2 class="text-sm font-medium text-muted-foreground uppercase tracking-wide mb-4">Useful links</h2>
        <div class="flex gap-6 text-sm text-muted-foreground">
            <span class="opacity-50">Documentation</span>
            <span class="opacity-50">Status</span>
            <span class="opacity-50">Changelog</span>
        </div>
        <p class="text-xs text-muted-foreground mt-3">Coming soon.</p>
    </div>

</body>

</html>
