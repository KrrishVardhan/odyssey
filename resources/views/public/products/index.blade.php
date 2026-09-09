<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Products — Odyssey</title>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-background text-foreground min-h-screen">
    <header class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
        <a href="/" class="font-mono text-sm font-semibold tracking-tight">
            odyssey
        </a>

        <div class="flex items-center gap-4">
            <span class="hidden sm:inline text-xs text-muted-foreground">
                {{ $products->count() }} {{ Str::plural('product', $products->count()) }}
            </span>

            @auth
                <a href="{{ url('/dashboard') }}"
                    class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                    Log in
                </a>
            @endauth
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 pb-20">
        <section class="py-16 lg:py-24 max-w-2xl">
            <div class="flex items-center gap-2 text-xs font-mono uppercase tracking-widest text-muted-foreground">
                <span class="w-6 h-px bg-border"></span>
                The marketplace
            </div>

            <h1 class="text-4xl lg:text-5xl font-semibold text-foreground tracking-tight leading-tight mt-5">
                Discover what teams are building.
            </h1>

            <p class="text-muted-foreground text-base lg:text-lg mt-4 max-w-xl">
                Explore products in progress, follow the teams behind them, and help shape what ships next.
            </p>
        </section>

        <div class="flex items-center justify-between border-b border-border pb-3 mb-6">
            <p class="text-sm font-medium text-foreground">All products</p>
            <p class="text-xs font-mono text-muted-foreground">{{ $products->count() }} listed</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse ($products as $product)
            <a href="{{ route('products.show', $product) }}"
                class="group bg-card border border-border rounded-xl overflow-hidden hover:border-foreground/30 hover:shadow-sm transition-all">
                <div class="aspect-[2.4/1] bg-muted overflow-hidden">
                    @if ($product->banner)
                        <img src="{{ $product->banner }}" alt="{{ $product->name }} banner"
                            class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <x-lucide-panels-top-left class="w-5 h-5 text-muted-foreground/60" />
                        </div>
                    @endif
                </div>

                <div class="px-5 pb-5">
                    <div class="flex items-end gap-3 -mt-8">
                        <div class="w-16 h-16 rounded-xl bg-card border-2 border-card flex items-center justify-center shrink-0 overflow-hidden shadow-sm">
                            @if ($product->icon_path)
                                <img src="{{ $product->icon_path }}" alt="{{ $product->name }} logo"
                                    class="w-full h-full object-cover">
                            @else
                                <span class="font-mono text-sm font-semibold text-muted-foreground">
                                    {{ substr($product->name, 0, 2) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4">
                        <p class="font-medium text-foreground truncate">{{ $product->name }}</p>
                        <p class="text-sm text-muted-foreground mt-1 line-clamp-2 min-h-10">
                            {{ $product->description ?: Str::limit(strip_tags($product->about ?? ''), 120) ?: 'A new product from the Odyssey community.' }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2 mt-5 pt-4 border-t border-border text-xs text-muted-foreground">
                        <span>View product</span>
                        <x-lucide-arrow-up-right class="w-3.5 h-3.5 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" />
                    </div>
                </div>
            </a>
        @empty
            <div class="md:col-span-2 lg:col-span-3 border border-dashed border-border rounded-xl py-20 text-center">
                <x-lucide-package-open class="w-6 h-6 text-muted-foreground mx-auto mb-4" />
                <p class="text-sm font-medium text-foreground">No public products yet.</p>
                <p class="text-sm text-muted-foreground mt-1">Check back soon for new launches.</p>
            </div>
        @endforelse
        </div>
    </main>
</body>

</html>
