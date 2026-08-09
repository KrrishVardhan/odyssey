<x-app-layout>
    <div class="max-w-2xl px-6 py-4">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-semibold text-foreground">
                    Welcome back, {{ explode(' ', auth()->user()->name)[0] }}
                </h1>
                <p class="text-sm text-muted-foreground mt-1">
                    {{ $unreadCount > 0 ? "{$unreadCount} unread" : 'You\'re all caught up' }}
                </p>
            </div>

            @if ($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.readAll') }}">
                    @csrf
                    <button class="text-sm text-primary hover:underline">Mark all read</button>
                </form>
            @endif
        </div>

        <div class="space-y-2">
            @forelse ($notifications as $notification)
                <div
                    class="flex items-start gap-3 bg-card border border-border rounded-lg p-4 {{ $notification->read_at ? 'opacity-60' : '' }}">

                    @php
                        $iconMap = [
                            'task_assigned' => 'user-plus',
                            'task_accepted' => 'check-circle-2',
                            'task_rejected' => 'x-circle',
                            'task_completed' => 'party-popper',
                        ];
                    @endphp

                    <div class="w-8 h-8 rounded-lg bg-muted flex items-center justify-center shrink-0 mt-0.5">
                        <x-dynamic-component :component="'lucide-' . ($iconMap[$notification->type] ?? 'bell')" class="w-4 h-4 text-muted-foreground" />
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-foreground">{{ $notification->message }}</p>
                        <p class="text-xs text-muted-foreground mt-1">
                            {{ $notification->team->name }} · {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>

                    @unless ($notification->read_at)
                        <form method="POST" action="{{ route('notifications.read', $notification) }}">
                            @csrf @method('PATCH')
                            <button class="w-2 h-2 rounded-full shrink-0 mt-2" style="background-color: var(--chart-1);"
                                title="Mark as read"></button>
                        </form>
                    @endunless
                </div>
            @empty
                <div class="text-center py-16">
                    <x-lucide-inbox class="w-8 h-8 text-muted-foreground mx-auto mb-3" />
                    <p class="text-sm text-muted-foreground">Nothing in your inbox yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
