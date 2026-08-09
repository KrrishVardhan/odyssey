<aside
    class="fixed inset-y-0 left-0 w-20 bg-card border border-border flex flex-col items-center py-4 z-40 m-2 rounded-xl">

    {{-- Home --}}
    <a href="{{ route('dashboard') }}"
        class="relative flex items-center justify-center w-12 h-12 rounded-2xl mb-2 transition-all duration-200
              {{ request()->routeIs('dashboard')
                  ? 'bg-primary text-primary-foreground rounded-xl'
                  : 'bg-muted text-muted-foreground hover:bg-primary hover:text-primary-foreground hover:rounded-xl' }}">
        <x-application-logo class="block h-9 w-auto fill-current" />
        @if ($unreadCount > 0)
            <span
                class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-destructive text-white text-[10px] font-semibold flex items-center justify-center">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif

    </a>

    <div class="w-8 h-px bg-border my-2"></div>

    {{-- Teams --}}
    <div class="flex-1 flex flex-col items-center gap-2 overflow-y-auto w-full px-2">
        @foreach ($teams as $team)
            <div class="relative w-12 h-12">
                @if ($activeTeamId === $team->id)
                    <span
                        class="absolute -left-3 top-1/2 -translate-y-1/2
                   w-1 h-7 rounded-r-full bg-chart-1">
                    </span>
                @endif
                <a href="{{ route('teams.show', $team) }}" title="{{ $team->name }}"
                    class="relative flex items-center justify-center w-12 h-12 shrink-0 overflow-hidden transition-all duration-200
                      {{ $activeTeamId === $team->id ? 'rounded-xl' : 'rounded-2xl hover:rounded-xl' }}">

                    @if ($team->icon_path)
                        <img src="{{ $team->icon_path }}" alt="{{ $team->name }}" class="w-full h-full object-cover">
                    @else
                        <div
                            class="w-full h-full flex items-center justify-center bg-secondary text-secondary-foreground font-mono text-sm font-semibold">
                            {{ substr($team->name, 0, 2) }}
                        </div>
                    @endif
                </a>
            </div>
        @endforeach

        {{-- Add team — placeholder, questionnaire flow comes later --}}
        <button x-data x-on:click="$dispatch('create-team-modal')"
            class="flex items-center justify-center w-12 h-12 shrink-0 rounded-2xl border-2 border-dashed border-border text-muted-foreground hover:border-primary hover:text-primary hover:rounded-xl transition-all duration-200 cursor-pointer">
            <x-lucide-plus class="w-5 h-5" />
        </button>
        {{-- Discover Teams --}}
        <button x-data x-on:click=""
            class="flex items-center justify-center w-12 h-12 shrink-0 rounded-2xl text-primary-foreground bg-primary hover:border-primary hover:rounded-xl transition-all duration-200 cursor-pointer">
            <x-lucide-compass class="w-5 h-5" />
        </button>

    </div>

    <x-user-card />
</aside>

<x-modal name="create-team-modal" focusable>
    <form method="POST" action="{{ route('teams.store') }}" class="p-6">
        @csrf
        <h2 class="text-lg font-semibold text-foreground">Create a team</h2>
        <p class="text-sm text-muted-foreground mt-1">Give it a name — you'll be the leader.</p>

        <div class="mt-5">
            <x-input-label for="team_name" value="Team name" />
            <x-text-input id="team_name" name="name" type="text" class="block mt-1.5 w-full" required autofocus
                placeholder="Odyssey Core" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="team_description" value="Description (optional)" />
            <textarea id="team_description" name="description" rows="3"
                class="w-full mt-1.5 rounded-lg border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring"
                placeholder="What is this team working on?"></textarea>
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" x-on:click="$dispatch('close')"
                class="px-4 py-2 text-sm text-muted-foreground hover:text-foreground transition-colors">
                Cancel
            </button>
            <x-primary-button>Create team</x-primary-button>
        </div>
    </form>
</x-modal>
