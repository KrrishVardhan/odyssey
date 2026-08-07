<x-app-layout :active-team-id="$team->id">
    <div class="flex h-screen relative">
        <x-team-nav :team="$team" active="mission" />

        <div class="flex-1 overflow-x-auto p-6">
            <div class="flex items-center justify-between">
                @if (in_array(auth()->user()->roleInTeam($team), ['leader', 'co_leader']))
                    <button x-data x-on:click="$dispatch('create-task-modal')"
                        class="absolute bottom-6 right-6 z-20 inline-flex items-center gap-2
                   p-4 rounded-full bg-primary text-primary-foreground
                   shadow-lg hover:scale-[1.02] transition">
                        <x-lucide-plus class="w-8 h-8" />
                    </button>
                @endif
            </div>

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

                        {{-- Kanban board ye rha --}}
                        <div class="flex-1 bg-muted/40 rounded-xl p-2 space-y-2 overflow-y-auto border-2 border-dashed border-border">
                            @forelse ($columns[$key] as $task)
                                <div
                                    class="bg-card border border-border rounded-lg p-3 hover:border-foreground/20 transition-colors cursor-pointer">
                                    <div class="flex items-start justify-between gap-2">
                                        <p class="text-sm font-medium text-card-foreground leading-snug">
                                            {{ $task->title }}</p>
                                        @if ($task->priority === 'high')
                                            <span class="w-1.5 h-1.5 rounded-full bg-chart-1 shrink-0 mt-1.5"></span>
                                        @elseif ($task->priority === 'low')
                                            <span
                                                class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0 mt-1.5"></span>
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
                                        @elseif (in_array(auth()->user()->roleInTeam($team), ['leader', 'co_leader']))
                                            <form method="POST" action="{{ route('tasks.assign', $task) }}"
                                                onclick="event.stopPropagation()">
                                                @csrf
                                                <select name="user_id" onchange="this.form.submit()"
                                                    class="text-xs bg-muted border border-border rounded-md px-1.5 py-1 text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring">
                                                    <option value="">Assign to...</option>
                                                    @foreach ($team->members as $member)
                                                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                                                    @endforeach
                                                </select>
                                            </form>
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
    <x-modal name="create-task-modal" focusable>
        <form method="POST" action="{{ route('teams.tasks.store', $team) }}" class="p-6">
            @csrf
            <h2 class="text-lg font-semibold text-foreground">New task</h2>
            <p class="text-sm text-muted-foreground mt-1">Add a task to {{ $team->name }}'s board.</p>

            <div class="mt-5">
                <x-input-label for="task_title" value="Title" />
                <x-text-input id="task_title" name="title" type="text" class="block mt-1.5 w-full" required
                    autofocus placeholder="Set up CI pipeline" />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="task_description" value="Description (optional)" />
                <textarea id="task_description" name="description" rows="3"
                    class="w-full mt-1.5 rounded-lg border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring"></textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <x-input-label for="task_priority" value="Priority" />
                    <select id="task_priority" name="priority"
                        class="w-full mt-1.5 rounded-lg border border-input bg-background px-3 py-2 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-ring">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div>
                    <x-input-label for="task_due_date" value="Due date (optional)" />
                    <x-text-input id="task_due_date" name="due_date" type="date" class="block mt-1.5 w-full" />
                    <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-4 py-2 text-sm text-muted-foreground hover:text-foreground transition-colors">
                    Cancel
                </button>
                <x-primary-button>Create task</x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
