@props(['team', 'active'])

<div class="w-64 bg-card border border-border flex flex-col shrink-0 mx-3 my-2 rounded-xl">
    <div class="px-5 py-5">
        <h2 class="font-semibold text-foreground truncate">{{ $team->name }}</h2>
        @if ($team->description)
            <p class="text-xs text-muted-foreground mt-1 line-clamp-2">{{ $team->description }}</p>
        @endif
    </div>
    <div class="w-[90%] h-px bg-border my-2 mx-auto"></div>

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
        <a href="{{ route('teams.chat', $team) }}"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                  {{ $active === 'chat' ? 'bg-secondary text-foreground' : 'text-muted-foreground hover:bg-muted hover:text-foreground' }}">
            @if ($active === 'chat')
                <span class="w-1 h-1 rounded-full" style="background-color: var(--chart-1);"></span>
            @else
                <span class="w-1 h-1"></span>
            @endif
            <x-lucide-hash class="w-4 h-4" />
            general-chat
        </a>
    </nav>

    <div class="w-8 mx-auto h-px bg-border my-2"></div>

    <div class="flex-1 px-3 overflow-y-auto">
        <div class="flex items-center justify-between px-2 mb-2">
            <p class="text-xs font-medium text-muted-foreground uppercase tracking-wide">Members</p>
            @if (in_array(auth()->user()->roleInTeam($team), ['leader', 'co_leader']))
                <button x-data x-on:click="$dispatch('add-member-modal')"
                    class="text-muted-foreground hover:text-foreground transition-colors">
                    <x-lucide-user-plus class="w-3.5 h-3.5" />
                </button>
            @endif
        </div>
        <div class="flex flex-col gap-1">
            @foreach ($team->members as $member)
                <div class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-muted/50">
                    <div class="w-6 h-6 rounded-full bg-secondary flex items-center justify-center shrink-0">
                        <span class="text-[10px] font-mono font-semibold text-secondary-foreground">
                            {{ substr($member->name, 0, 1) }}
                        </span>
                    </div>
                    <span class="text-sm text-foreground truncate">{{ $member->name }}</span>
                    @if ($member->pivot->role === 'leader')
                        <span
                            class="text-[10px] text-chart-1 ml-auto shrink-0 flex items-center gap-1">{{ ucfirst(str_replace('_', ' ', $member->pivot->role)) }}
                            <x-lucide-chess-king class="w-4 h-4" /></span>
                    @endif
                    @if ($member->pivot->role === 'co_leader')
                        <span
                            class="text-[10px] text-chart-3 ml-auto shrink-0 flex items-center gap-1">{{ ucfirst(str_replace('_', ' ', $member->pivot->role)) }}
                            <x-lucide-chess-queen class="w-4 h-4" /></span>
                    @endif

                </div>
            @endforeach
        </div>
    </div>
</div>
@if (in_array(auth()->user()->roleInTeam($team), ['leader', 'co_leader']))
    <x-modal name="add-member-modal" focusable>
        <form method="POST" action="{{ route('teams.members.store', $team) }}" class="p-6">
            @csrf
            <h2 class="text-lg font-semibold text-foreground">Add a member</h2>
            <p class="text-sm text-muted-foreground mt-1">They'll join {{ $team->name }} immediately.</p>

            <div class="mt-5">
                <x-input-label for="member_email" value="Email" />
                <x-text-input id="member_email" name="email" type="email" class="block mt-1.5 w-full" required
                    autofocus placeholder="teammate@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-4 py-2 text-sm text-muted-foreground hover:text-foreground transition-colors">
                    Cancel
                </button>
                <x-primary-button>Add member</x-primary-button>
            </div>
        </form>
    </x-modal>
@endif
