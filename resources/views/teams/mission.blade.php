<x-app-layout :active-team-id="$team->id">
    <div class="flex h-screen">
        <x-team-nav :team="$team" active="mission" />

        <div class="flex-1 overflow-x-auto p-6">
            <div class="flex gap-5 h-full min-w-max">

                @php
                    $columnMeta = [
                        'todo' => ['label' => 'To Do', 'icon' => 'circle-dashed'],
                        'in_progress' => ['label' => 'In Progress', 'icon' => 'loader'],
                        'completed' => ['label' => 'Completed', 'icon' => 'check-circle-2'],
                    ];
                @endphp

                @foreach ($columnMeta as $key => $meta)
                    <div class="w-72 shrink-0 flex flex-col">
                        <div class="flex items-center gap-2 px-1 mb-3">
                            <x-dynamic-component :component="'lucide-' . $meta['icon']" class="w-4 h-4 text-muted-foreground" />
                            <h3 class="text-sm font-medium text-foreground">{{ $meta['label'] }}</h3>
                            <span class="text-xs text-muted-foreground bg-muted rounded-full px-1.5 py-0.5 ml-auto">
                                {{ $columns[$key]->count() }}
                            </span>
                        </div>

                        <div class="flex-1 bg-muted/40 rounded-xl p-2 space-y-2 overflow-y-auto">
                            @forelse ($columns[$key] as $task)
                                <div
                                    class="bg-card border border-border rounded-lg p-3 hover:border-foreground/20 transition-colors cursor-pointer">
                                    <div class="flex items-start justify-between gap-2">
                                        <p class="text-sm font-medium text-card-foreground leading-snug">
                                            {{ $task->title }}</p>
                                        @if ($task->priority === 'high')
                                            <span
                                                class="w-1.5 h-1.5 rounded-full bg-destructive shrink-0 mt-1.5"></span>
                                        @endif
                                    </div>

                                    @if ($task->description)
                                        <p class="text-xs text-muted-foreground mt-1.5 line-clamp-2">
                                            {{ $task->description }}</p>
                                    @endif

                                    <div class="flex items-center justify-between mt-3">
                                        @if ($task->due_date)
                                            <span
                                                class="text-xs text-muted-foreground">{{ $task->due_date->format('M j') }}</span>
                                        @else
                                            <span></span>
                                        @endif

                                        @php $assignee = $task->assignments->sortByDesc('created_at')->first()?->assignee; @endphp
                                        @if ($assignee)
                                            <div class="w-6 h-6 rounded-full bg-secondary flex items-center justify-center"
                                                title="{{ $assignee->name }}">
                                                <span
                                                    class="text-[10px] font-mono font-semibold text-secondary-foreground">
                                                    {{ substr($assignee->name, 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-muted-foreground text-center py-6">Nothing here yet.</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</x-app-layout>
