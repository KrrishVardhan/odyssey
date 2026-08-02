<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-foreground leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <span class="text-sm text-muted-foreground">
                {{ now()->format('l, F j') }}
            </span>
        </div>
    </x-slot>

    <div class="py-8 bg-background min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Greeting --}}
            <div>
                <h1 class="text-2xl font-semibold text-foreground">
                    Welcome back, {{ explode(' ', auth()->user()->name)[0] }}
                </h1>
                <p class="text-sm text-muted-foreground mt-1">
                    Here's what's happening across your teams.
                </p>
            </div>

            {{-- Stats row --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                    $stats = [
                        ['label' => 'Active Tasks', 'value' => 0, 'icon' => 'list-checks'],
                        ['label' => 'My Teams', 'value' => 0, 'icon' => 'users'],
                        ['label' => 'Pending Requests', 'value' => 0, 'icon' => 'inbox'],
                        ['label' => 'Completed This Week', 'value' => 0, 'icon' => 'check-circle-2'],
                    ];
                @endphp

                @foreach ($stats as $stat)
                    <div class="bg-card border border-border rounded-sm p-5 flex items-start justify-between">
                        <div>
                            <p class="text-sm text-muted-foreground">{{ $stat['label'] }}</p>
                            <p class="text-2xl font-semibold text-card-foreground mt-1">{{ $stat['value'] }}</p>
                        </div>
                        <div class="bg-muted rounded-lg p-2">
                            <x-dynamic-component :component="'lucide-' . $stat['icon']" class="w-5 h-5 text-muted-foreground" />
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Main grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- My Tasks --}}
                <div class="lg:col-span-2 bg-card border border-border rounded-sm">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-border">
                        <h3 class="font-medium text-card-foreground">My Tasks</h3>
                        <a href="#" class="text-sm text-primary hover:underline">View all</a>
                    </div>

                    <div class="divide-y divide-border">
                        {{-- Empty state, replace with @forelse once tasks exist --}}
                        <div class="px-5 py-10 text-center">
                            <x-lucide-clipboard-list class="w-8 h-8 text-muted-foreground mx-auto mb-3" />
                            <p class="text-sm text-muted-foreground">No tasks assigned to you yet.</p>
                        </div>

                        {{-- Example row structure for when data exists:
                        <div class="px-5 py-4 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-card-foreground">Task title</p>
                                <p class="text-xs text-muted-foreground mt-0.5">Team name · Due date</p>
                            </div>
                            <span class="text-xs font-medium px-2 py-1 rounded-full bg-secondary text-secondary-foreground">
                                In Progress
                            </span>
                        </div>
                        --}}
                    </div>
                </div>

                {{-- My Teams --}}
                <div class="bg-card border border-border rounded-sm">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-border">
                        <h3 class="font-medium text-card-foreground">My Teams</h3>
                        <a href="#" class="text-sm text-primary hover:underline">Manage</a>
                    </div>

                    <div class="divide-y divide-border">
                        <div class="px-5 py-10 text-center">
                            <x-lucide-users class="w-8 h-8 text-muted-foreground mx-auto mb-3" />
                            <p class="text-sm text-muted-foreground">You're not part of a team yet.</p>
                            <a href="#"
                                class="inline-block mt-3 text-sm font-medium bg-primary text-primary-foreground px-3 py-1.5 rounded-3xl hover:opacity-90">
                                Create a team
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
