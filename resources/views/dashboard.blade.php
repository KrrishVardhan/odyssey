<x-app-layout>
    <div class="flex flex-col items-center justify-center h-[calc(100vh-2rem)] text-center px-6">
        <div class="w-16 h-16 rounded-2xl bg-muted flex items-center justify-center mb-4">
            <x-lucide-home class="w-7 h-7 text-muted-foreground" />
        </div>
        <h1 class="text-xl font-semibold text-foreground">
            Welcome back, {{ explode(' ', auth()->user()->name)[0] }}
        </h1>
        <p class="text-sm text-muted-foreground mt-2 max-w-sm">
            This is your home base. We'll surface activity across all your teams here soon.
        </p>
    </div>
</x-app-layout>
