@props(['team', 'active'])

<div class="w-64 bg-card border border-border flex flex-col shrink-0 mx-3 my-2 rounded-xl">
    <div class="px-5 py-5 border-b border-border">
        <h2 class="font-semibold text-foreground truncate">{{ $team->name }}</h2>
        @if ($team->description)
            <p class="text-xs text-muted-foreground mt-1 line-clamp-2">{{ $team->description }}</p>
        @endif
    </div>

    <nav class="flex flex-col p-3 gap-1">
        <a href="{{ route('teams.show', $team) }}"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                  {{ $active === 'mission' ? 'bg-secondary text-foreground' : 'text-muted-foreground hover:bg-muted hover:text-foreground' }}">
            @if ($active === 'mission')
                <span class="w-1 h-1 rounded-full" style="background-color: var(--chart-1);"></span>
            @else
                <span class="w-1 h-1"></span>
            @endif
            <x-lucide-target class="w-4 h-4" />
            Mission Board
        </a>

        <a href="{{ route('teams.workspace', $team) }}"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                  {{ $active === 'workspace' ? 'bg-secondary text-foreground' : 'text-muted-foreground hover:bg-muted/50 hover:text-foreground' }}">
            @if ($active === 'workspace')
                <span class="w-1 h-1 rounded-full" style="background-color: var(--chart-1);"></span>
            @else
                <span class="w-1 h-1"></span>
            @endif
            <x-lucide-layout-list class="w-4 h-4" />
            My Workspace
        </a>
    </nav>

    <div class="w-8 mx-auto h-px bg-border my-2"></div>

    <div class="flex-1 px-3 overflow-y-auto">
        <p class="px-2 text-xs font-medium text-muted-foreground uppercase tracking-wide mb-2">Members</p>
        <div class="flex flex-col gap-1">
            @foreach ($team->members as $member)
                <div class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-muted/50">
                    <div class="w-6 h-6 rounded-full bg-secondary flex items-center justify-center shrink-0">
                        <span class="text-[10px] font-mono font-semibold text-secondary-foreground">
                            {{ substr($member->name, 0, 1) }}
                        </span>
                    </div>
                    <span class="text-sm text-foreground truncate">{{ $member->name }}</span>
                    @if ($member->pivot->role !== 'member')
                        <span
                            class="text-[10px] text-chart-1 ml-auto shrink-0 flex items-center gap-1">{{ ucfirst(str_replace('_', ' ', $member->pivot->role)) }}
                            <x-lucide-crown class="w-4 h-4" /></span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
