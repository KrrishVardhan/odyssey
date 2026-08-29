<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $team->name }} — Odyssey</title>

    <script>
        if (
            localStorage.theme === 'dark' ||
            (!('theme' in localStorage) &&
                window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            document.documentElement.classList.add('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-background text-foreground min-h-screen" x-data="{ modal: null, moreOpen: false }">

    {{-- Navbar --}}
    <header class="border-b border-border px-6 py-4 flex items-center justify-between">
        <a href="/" class="font-mono text-sm font-semibold tracking-tight">
            odyssey
        </a>

        <a href="{{ route('products.index') }}"
            class="text-sm text-muted-foreground hover:text-foreground transition-colors">
            All products
        </a>
    </header>


    {{-- Banner --}}
    <div class="w-full h-56 rounded-xl overflow-hidden p-1">
        @if ($team->banner_image_url)
            <img src="{{ $team->banner_image_url }}" alt="{{ $team->name }} banner"
                class="w-full h-full object-cover rounded-xl">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <div class="flex flex-col items-center gap-2 text-muted-foreground">
                    <x-lucide-image class="w-5 h-5" />

                    <span class="text-xs font-mono">
                        No banner set
                    </span>
                </div>
            </div>
        @endif
    </div>


    <div class="max-w-7xl mx-auto px-6">

        {{-- Product identity --}}
        <div class="flex items-end gap-5 -mt-10">

            {{-- Logo --}}
            <div
                class="w-24 h-24 rounded-2xl bg-card border-2 border-background flex items-center justify-center shrink-0 overflow-hidden">
                @if ($team->icon_path)
                    <img src="{{ $team->icon_path }}" alt="{{ $team->name }} logo" class="w-full h-full object-cover">
                @else
                    <span class="font-mono text-2xl font-semibold text-muted-foreground">
                        {{ substr($team->name, 0, 2) }}
                    </span>
                @endif
            </div>

            {{-- Name --}}
            <div class="pb-2 mt-12">
                <h1 class="text-2xl font-semibold text-foreground">
                    {{ $team->name }}
                </h1>

                <p class="text-sm text-muted-foreground mt-0.5">
                    {{ $followerCount }}
                    {{ Str::plural('follower', $followerCount) }}
                </p>
            </div>

        </div>


        {{-- Actions + tags --}}
        <div class="flex flex-wrap items-center gap-3 mt-5 pb-6 border-b border-border">

            {{-- Tags --}}
            {{-- Add your team's tag data here when available. --}}

            @auth
                <form method="POST" action="{{ route('products.follow', $team) }}">
                    @csrf

                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-medium transition
                            {{ $isFollowing
                                ? 'bg-secondary text-secondary-foreground hover:bg-muted'
                                : 'bg-primary text-primary-foreground hover:opacity-90' }}">
                        <x-lucide-user-plus class="w-4 h-4" />

                        {{ $isFollowing ? 'Following' : 'Follow' }}
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-medium bg-primary text-primary-foreground hover:opacity-90 transition">
                    <x-lucide-user-plus class="w-4 h-4" />

                    Follow
                </a>
            @endauth


            {{-- Contact --}}
            <a href="mailto:{{ $team->owner->email }}"
                class="inline-flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-medium border border-border text-foreground hover:bg-muted transition">
                <x-lucide-mail class="w-4 h-4" />

                Contact
            </a>


            {{-- More --}}
            <div class="relative ml-auto">

                <button type="button" x-on:click="moreOpen = !moreOpen" x-on:click.outside="moreOpen = false"
                    class="w-9 h-9 flex items-center justify-center rounded-lg border border-border text-muted-foreground hover:text-foreground hover:bg-muted transition"
                    aria-label="More actions">
                    <x-lucide-more-vertical class="w-4 h-4" />
                </button>

                <div x-show="moreOpen" x-cloak x-transition
                    class="absolute right-0 mt-2 w-40 bg-card border border-border rounded-lg shadow-lg py-1 z-10">
                    <button type="button"
                        x-on:click="navigator.clipboard.writeText(window.location.href); moreOpen = false"
                        class="w-full flex items-center gap-2 text-left px-3 py-2 text-sm text-foreground hover:bg-muted">
                        <x-lucide-link class="w-4 h-4 text-muted-foreground" />

                        Copy link
                    </button>
                </div>

            </div>

        </div>


        {{-- Main content --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 py-8">

            {{-- Main column --}}
            <main class="lg:col-span-2">

                <div class="border border-border rounded-xl p-6 bg-card">

                    <div class="flex items-center gap-2 mb-4">
                        <x-lucide-info class="w-4 h-4 text-muted-foreground" />

                        <h2 class="text-sm font-medium text-muted-foreground uppercase tracking-wide">
                            About
                        </h2>
                    </div>

                    @if ($team->about)
                        <div class="prose prose-odyssey">
                            {!! Str::markdown($team->about) !!}
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center text-center py-16">
                            <x-lucide-file-text class="w-5 h-5 text-muted-foreground mb-3" />

                            <p class="text-sm text-muted-foreground">
                                No description yet.
                            </p>
                        </div>
                    @endif

                </div>

            </main>


            {{-- Sidebar --}}
            <aside class="space-y-6">

                {{-- Featured / latest release --}}
                <div class="border border-border rounded-xl p-4 bg-card">

                    <div class="flex items-center gap-2">
                        <x-lucide-rocket class="w-4 h-4 text-muted-foreground" />

                        <p class="text-xs font-medium text-muted-foreground uppercase tracking-wide">
                            Latest release
                        </p>
                    </div>

                    <p class="text-sm font-medium text-foreground mt-3">
                        Coming soon
                    </p>

                    <p class="text-xs text-muted-foreground mt-1">
                        Changelog isn't wired up yet.
                    </p>

                </div>


                {{-- Feedback --}}
                <div class="border border-border rounded-xl p-4 bg-card">

                    <div class="flex items-start gap-3 mb-4">
                        <div class="shrink-0">
                            <x-lucide-message-square-plus class="w-5 h-5 text-muted-foreground" />
                        </div>

                        <div>
                            <p class="text-sm font-medium text-foreground">
                                Have something to say?
                            </p>

                            <p class="text-xs text-muted-foreground mt-0.5">
                                Help make {{ $team->name }} better.
                            </p>
                        </div>
                    </div>


                    <div class="space-y-2">

                        {{-- Feature --}}
                        <button type="button" x-on:click="modal = 'feature'"
                            class="w-full flex items-center gap-3 px-3 py-2.5 bg-muted hover:bg-secondary rounded-lg text-sm font-medium text-foreground transition">
                            <x-lucide-lightbulb class="w-4 h-4 text-muted-foreground" />

                            <span>
                                Request a feature
                            </span>
                        </button>


                        {{-- Bug --}}
                        <button type="button" x-on:click="modal = 'bug'"
                            class="w-full flex items-center gap-3 px-3 py-2.5 bg-muted hover:bg-secondary rounded-lg text-sm font-medium text-foreground transition">
                            <x-lucide-bug class="w-4 h-4 text-destructive" />

                            <span>
                                Report a bug
                            </span>
                        </button>


                        {{-- Feedback --}}
                        <button type="button" x-on:click="modal = 'feedback'"
                            class="w-full flex items-center gap-3 px-3 py-2.5 bg-muted hover:bg-secondary rounded-lg text-sm font-medium text-foreground transition">
                            <x-lucide-message-circle class="w-4 h-4 text-muted-foreground" />

                            <span>
                                Give feedback
                            </span>
                        </button>

                    </div>


                    {{-- Stats --}}
                    <div class="border-t border-border mt-4 pt-4 grid grid-cols-3">

                        <div class="text-center">
                            <p class="font-semibold text-foreground">
                                {{ $stats['requests'] }}
                            </p>

                            <p class="text-xs text-muted-foreground mt-0.5">
                                requests
                            </p>
                        </div>

                        <div class="text-center border-l border-border">
                            <p class="font-semibold text-foreground">
                                {{ $stats['planned'] }}
                            </p>

                            <p class="text-xs text-muted-foreground mt-0.5">
                                planned
                            </p>
                        </div>

                        <div class="text-center border-l border-border">
                            <p class="font-semibold text-foreground">
                                {{ $stats['shipped'] }}
                            </p>

                            <p class="text-xs text-muted-foreground mt-0.5">
                                shipped
                            </p>
                        </div>

                    </div>

                </div>


                {{-- Useful links --}}
                <div>

                    <div class="flex items-center gap-2 mb-2">
                        <x-lucide-link class="w-4 h-4 text-muted-foreground" />

                        <p class="text-xs font-medium text-muted-foreground uppercase tracking-wide">
                            Useful links
                        </p>
                    </div>

                    <div class="flex flex-col gap-1.5 text-sm">

                        <span class="flex items-center gap-2 text-muted-foreground opacity-50">
                            <x-lucide-book-open class="w-4 h-4" />
                            Documentation
                        </span>

                        <span class="flex items-center gap-2 text-muted-foreground opacity-50">
                            <x-lucide-activity class="w-4 h-4" />
                            Status
                        </span>

                        <span class="flex items-center gap-2 text-muted-foreground opacity-50">
                            <x-lucide-scroll-text class="w-4 h-4" />
                            Changelog
                        </span>

                    </div>

                </div>

            </aside>

        </div>

    </div>


    {{-- Submission modal --}}
    <div x-show="modal" x-cloak class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center px-4"
        x-on:click.self="modal = null" x-on:keydown.escape.window="modal = null">

        <div x-show="modal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="bg-card border border-border rounded-xl shadow-xl w-full max-w-md overflow-hidden">

            {{-- Modal header --}}
            <div class="px-6 py-5 border-b border-border flex items-center gap-3">

                {{-- Feature icon --}}
                <div x-show="modal === 'feature'" class="shrink-0">
                    <x-lucide-lightbulb class="w-5 h-5 text-muted-foreground" />
                </div>

                {{-- Bug icon --}}
                <div x-show="modal === 'bug'" class="shrink-0">
                    <x-lucide-bug class="w-5 h-5 text-destructive" />
                </div>

                {{-- Feedback icon --}}
                <div x-show="modal === 'feedback'" class="shrink-0">
                    <x-lucide-message-circle class="w-5 h-5 text-muted-foreground" />
                </div>

                <div>
                    <h2 class="text-base font-semibold text-foreground"
                        x-text="
                            modal === 'bug'
                                ? 'Report a bug'
                                : (modal === 'feature'
                                    ? 'Request a feature'
                                    : 'Share feedback')
                        ">
                    </h2>

                    <p class="text-xs text-muted-foreground">
                        to {{ $team->name }}
                    </p>
                </div>

            </div>


            {{-- Form --}}
            <form method="POST" action="{{ route('products.submissions.store', $team) }}" class="p-6">
                @csrf

                <input type="hidden" name="type" x-bind:value="modal">


                <x-input-label for="sub_title" value="Title" />

                <x-text-input id="sub_title" name="title" type="text" class="block mt-1.5 w-full" required
                    x-bind:placeholder="modal === 'bug'
                        ?
                        'Something broke when I...' :
                        (modal === 'feature' ?
                            'It would be great if...' :
                            'I think that...')" />

                <x-input-error :messages="$errors->get('title')" class="mt-2" />


                <div class="mt-4">

                    <x-input-label for="sub_description" value="Details" />

                    <textarea id="sub_description" name="description" rows="4" required
                        class="w-full mt-1.5 rounded-lg border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring"
                        placeholder="The more detail, the faster we can act on it."></textarea>

                    <x-input-error :messages="$errors->get('description')" class="mt-2" />

                </div>


                @guest
                    <div class="mt-4">

                        <x-input-label for="sub_email" value="Email (optional)" />

                        <x-text-input id="sub_email" name="email" type="email" class="block mt-1.5 w-full"
                            placeholder="So we can follow up" />

                    </div>
                @endguest


                {{-- Modal actions --}}
                <div class="mt-6 flex justify-end gap-3">

                    <button type="button" x-on:click="modal = null"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm text-muted-foreground hover:text-foreground transition-colors">
                        <x-lucide-x class="w-4 h-4" />

                        Cancel
                    </button>

                    <x-primary-button class="inline-flex items-center gap-2 px-4 py-2 text-sm text-muted-foreground hover:text-foreground transition-colors">
                        <x-lucide-send class="w-4 h-4" />

                        Submit
                    </x-primary-button>

                </div>

            </form>

        </div>

    </div>

</body>

</html>
