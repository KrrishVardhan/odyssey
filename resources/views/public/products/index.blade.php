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
    <header class="px-6 py-4 flex items-center justify-between">
        <a href="/" class="font-mono text-sm font-semibold tracking-tight">
            odyssey
        </a>
    </header>

    <div class="max-w-3xl mx-auto px-6 py-16 text-center">
        <h1 class="text-3xl font-semibold text-foreground">Products on Odyssey</h1>
        <p class="text-muted-foreground mt-2">Browse what teams are building.</p>
    </div>

    <div class="max-w-3xl mx-auto px-6 pb-16 space-y-3">
        @forelse ($products as $product)
            <a href="{{ route('products.show', $product) }}"
                class="flex items-center gap-4 bg-card border border-border rounded-xl p-4 hover:border-foreground/20 transition-colors">
                <div
                    class="w-12 h-12 rounded-xl bg-secondary flex items-center justify-center shrink-0 overflow-hidden">
                    @if ($product->icon_path)
                        <img src="{{ $product->icon_path }}" class="w-full h-full object-cover">
                    @else
                        <span
                            class="font-mono text-sm font-semibold text-secondary-foreground">{{ substr($product->name, 0, 2) }}</span>
                    @endif
                </div>
                <div>
                    <p class="font-medium text-foreground">{{ $product->name }}</p>
                    <p class="text-sm text-muted-foreground line-clamp-1">{{ $product->description }}</p>
                </div>
            </a>
        @empty
            <p class="text-center text-sm text-muted-foreground py-12">No public products yet.</p>
        @endforelse
    </div>
</body>

</html>
