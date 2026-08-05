<aside class="fixed inset-y-0 left-0 w-20 bg-card border-r border-border flex flex-col items-center py-4 z-40">

    {{-- Home --}}
    <a href="{{ route('dashboard') }}"
        class="relative flex items-center justify-center w-12 h-12 rounded-2xl mb-2 transition-all duration-200
              {{ request()->routeIs('dashboard')
                  ? 'bg-primary text-primary-foreground rounded-xl'
                  : 'bg-muted text-muted-foreground hover:bg-primary hover:text-primary-foreground hover:rounded-xl' }}">
        @if (request()->routeIs('dashboard'))
            <span class="absolute -left-5 w-1 h-8 rounded-r-full" style="background-color: var(--chart-1);"></span>
        @endif
        <x-lucide-home class="w-5 h-5" />
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
        <button
            class="flex items-center justify-center w-12 h-12 shrink-0 rounded-2xl border-2 border-dashed border-border text-muted-foreground hover:border-primary hover:text-primary hover:rounded-xl transition-all duration-200">
            <x-lucide-plus class="w-5 h-5" />
        </button>
    </div>

    <x-user-card />
</aside>
