<x-app-layout :active-team-id="$team->id">
    <div class="flex h-screen">
        <x-team-nav :team="$team" active="settings" />

        <div class="flex-1 overflow-y-auto p-6 space-y-6">

            <div class="flex items-center gap-2">
                <x-lucide-settings class="h-5 w-5 text-muted-foreground" />
                <h1 class="text-lg font-semibold text-foreground">
                    {{ $team->name }} settings
                </h1>
            </div>

            @php
                $canEdit = auth()->user()->roleInTeam($team) === 'leader';
                $canKick = in_array(auth()->user()->roleInTeam($team), ['leader', 'co_leader']);
            @endphp

            {{-- Overview --}}
            <div class="bg-card border border-border rounded-xl p-5">
                <p class="text-sm font-medium text-foreground mb-3">
                    Overview
                </p>

                <div class="flex items-center gap-4 mb-4">
                    <div
                        class="w-16 h-16 rounded-2xl bg-secondary flex items-center justify-center overflow-hidden shrink-0">
                        @if ($team->icon_path)
                            <img src="{{ $team->icon_path }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-lg font-mono font-semibold text-secondary-foreground">
                                {{ substr($team->name, 0, 2) }}
                            </span>
                        @endif
                    </div>

                    @if ($canEdit)
                        <form method="POST" action="{{ route('teams.settings.icon', $team) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="icon" accept="image/*" onchange="this.form.submit()"
                                class="text-sm text-muted-foreground file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border file:border-border file:bg-muted file:text-foreground file:text-sm hover:file:bg-secondary">
                        </form>
                    @endif
                </div>

                @if ($canEdit)
                    <form method="POST" action="{{ route('teams.settings.update', $team) }}" class="space-y-3">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="team_name" value="Team name" />
                            <x-text-input id="team_name" name="name" type="text" class="block mt-1.5 w-full"
                                value="{{ old('name', $team->name) }}" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-1" />
                        </div>

                        <div>
                            <x-input-label for="team_description" value="Description" />
                            <textarea id="team_description" name="description" rows="3"
                                class="w-full mt-1.5 rounded-lg border border-input bg-background px-3 py-2 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-ring">{{ old('description', $team->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-1" />
                        </div>

                        <x-primary-button>Save changes</x-primary-button>
                    </form>
                @else
                    <p class="text-sm text-foreground">{{ $team->name }}</p>
                    <p class="text-sm text-muted-foreground mt-1">{{ $team->description ?? 'No description.' }}</p>
                    <p class="text-xs text-muted-foreground mt-3">
                        Only the team leader can edit these details.
                    </p>
                @endif
            </div>

            {{-- Members --}}
            <div class="bg-card border border-border rounded-xl p-5">
                <p class="text-sm font-medium text-foreground mb-3">
                    Members
                </p>

                <div class="divide-y divide-border">
                    @foreach ($team->members as $member)
                        <div class="flex items-center justify-between py-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-secondary flex items-center justify-center shrink-0 overflow-hidden">
                                    @if ($member->avatar)
                                        <img src="{{ $member->avatar }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-xs font-mono font-semibold text-secondary-foreground">
                                            {{ substr($member->name, 0, 1) }}
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm text-foreground">
                                        {{ $member->name }}
                                        @if ($member->id === $team->owner_id)
                                            <span class="text-xs text-muted-foreground">(owner)</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-muted-foreground">
                                        {{ ucfirst(str_replace('_', ' ', $member->pivot->role)) }}
                                    </p>
                                </div>
                            </div>

                            @if ($canKick && $member->id !== $team->owner_id)
                                <div class="flex items-center gap-2">
                                    <form method="POST" action="{{ route('teams.members.role', [$team, $member]) }}">
                                        @csrf
                                        @method('PATCH')

                                        <select name="role" onchange="this.form.submit()"
                                            class="text-xs bg-muted border border-border rounded-md px-2 py-1 text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring">
                                            <option value="member" @selected($member->pivot->role === 'member')>Member</option>
                                            <option value="co_leader" @selected($member->pivot->role === 'co_leader')>Co-leader</option>
                                        </select>
                                    </form>

                                    <form method="POST" action="{{ route('teams.members.kick', [$team, $member]) }}"
                                        onsubmit="return confirm('Remove {{ $member->name }} from {{ $team->name }}?')">
                                        @csrf
                                        @method('DELETE')

                                        <button class="text-muted-foreground hover:text-destructive transition-colors">
                                            <x-lucide-user-x class="h-4 w-4" />
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Products placeholder --}}
            <div class="bg-card border border-border rounded-xl p-5">
                <p class="text-sm font-medium text-foreground mb-1">
                    Products
                </p>
                <p class="text-sm text-muted-foreground">
                    Coming soon — a team will be able to manage multiple public-facing products here.
                </p>
            </div>

            @if ($canEdit)
                <div class="border border-destructive/30 rounded-xl p-5">
                    <p class="text-sm font-medium text-destructive mb-1">
                        Danger zone
                    </p>
                    <p class="text-sm text-muted-foreground">
                        Deleting a team is not available yet.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
