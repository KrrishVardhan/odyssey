<x-app-layout :active-team-id="$team->id">
    <div class="p-8">
        <h1 class="text-xl font-semibold text-foreground">{{ $team->name }}</h1>
        <p class="text-sm text-muted-foreground mt-1">{{ $team->description ?? 'No description yet.' }}</p>
        <p class="text-sm text-muted-foreground mt-6">Mission board and My Workspace views land here next.</p>
    </div>
</x-app-layout>
