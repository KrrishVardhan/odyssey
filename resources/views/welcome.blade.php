<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Odyssey') }}</title>

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>

    @fonts

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body
    class="bg-background text-foreground font-sans antialiased flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">

    <header class="w-full lg:max-w-4xl max-w-83.75 text-sm mb-6 not-has-[nav]:hidden">
        <nav class="flex items-center justify-between gap-4">
            <a href="/" class="font-mono text-sm font-semibold tracking-tight text-foreground">
                odyssey
            </a>

            <div class="flex items-center gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="inline-block px-4 py-1.5 border border-border hover:border-foreground/30 text-foreground rounded-3xl text-sm leading-normal transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-block px-4 py-1.5 text-muted-foreground hover:text-foreground hover:underline rounded-3xl text-sm leading-normal transition-colors">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="inline-block px-4 py-1.5 border border-border hover:border-foreground/30 text-foreground rounded-3xl text-sm leading-normal transition-colors">
                                Sign up
                            </a>
                        @endif
                    @endauth
                @endif

                <button
                    onclick="document.documentElement.classList.toggle('dark'); localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';"
                    class="p-2 rounded-3xl hover:bg-muted text-muted-foreground hover:text-foreground transition">
                    <x-lucide-sun class="w-4 h-4 dark:hidden" />
                    <x-lucide-moon class="w-4 h-4 hidden dark:block" />
                </button>
            </div>
        </nav>
    </header>

    <div
        class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
        <main class="flex flex-col items-center text-center gap-6 max-w-83.75 w-full lg:max-w-2xl">
            <span class="font-mono text-xs uppercase tracking-widest text-muted-foreground">
                Product shipping, simplified
            </span>

            <h1 class="text-3xl lg:text-5xl font-semibold text-foreground tracking-tight leading-tight">
                Ship features your customers actually asked for.
            </h1>

            <p class="text-muted-foreground text-base lg:text-lg max-w-xl">
                Teams, task pipelines, and feature requests — from customer ask to shipped PR, in one place.
            </p>

            <div class="flex items-center gap-3 mt-2">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="inline-flex items-center px-5 py-2.5 bg-primary text-primary-foreground rounded-3xl text-sm font-medium hover:opacity-90 transition">
                        Go to dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center px-5 py-2.5 bg-primary text-primary-foreground rounded-3xl text-sm font-medium hover:opacity-90 transition">
                        Get started
                    </a>
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center px-5 py-2.5 border border-border text-foreground rounded-3xl text-sm font-medium hover:bg-muted transition">
                        Log in
                    </a>
                @endauth
            </div>
        </main>
    </div>

    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif
</body>

</html>
