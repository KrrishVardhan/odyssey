<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Odyssey') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-background text-foreground">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-12">

        <a href="/" class="mb-8 flex items-center gap-2 text-foreground">
            <span class="font-mono text-lg font-semibold tracking-tight">odyssey</span>
        </a>

        <div class="w-full max-w-sm bg-card border border-border rounded-xl shadow-sm p-8">
            {{ $slot }}
        </div>

        <p class="mt-8 text-xs text-muted-foreground font-mono">
            © {{ date('Y') }} Odyssey
        </p>
    </div>
</body>
</html>
