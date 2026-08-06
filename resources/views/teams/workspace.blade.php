<x-app-layout :active-team-id="$team->id">
    <div class="flex h-[calc(100vh-2rem)]">
        <x-team-nav :team="$team" active="workspace" />

        <div class="flex-1 p-8">
            <h1 class="text-lg font-semibold text-foreground">My Workspace</h1>
            <p class="text-sm text-muted-foreground mt-1">Your personal assignments in {{ $team->name }} — built next.
            </p>
        </div>
    </div>
</x-app-layout>
