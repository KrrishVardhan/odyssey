<x-app-layout :active-team-id="$team->id">
    <div class="flex h-screen relative">
        <x-team-nav :team="$team" active="workspace" />

        <div class="flex-1 overflow-x-auto p-6" x-data="{ selectedTask: null }">
            <div class="flex items-center justify-between">
                <button x-data x-on:click="$dispatch('create-workspace-task-modal')"
                    class="absolute right-6 bottom-6 z-20 inline-flex items-center gap-2
                   p-4 rounded-full bg-primary text-primary-foreground
                   shadow-lg hover:scale-[1.02] transition">
                    <x-lucide-plus class="w-8 h-8" />
                </button>
            </div>

            <div class="flex gap-5 h-full min-w-max">
                @php
                    $columnMeta = [
                        'pending' => ['label' => 'Needs Response', 'icon' => 'bell'],
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

                        {{-- Kanban --}}
                        <div class="flex-1 bg-muted/40 rounded-xl p-2 space-y-2 overflow-y-auto border-2 border-dashed">
                            @forelse ($columns[$key] as $assignment)
                                @php
                                    $task = $assignment->task;

                                    $taskData = [
                                        'title' => $task->title,
                                        'description' => $task->description,
                                        'priority' => $task->priority,
                                        'due_date' => $task->due_date?->format('M j, Y'),
                                        'assignee_name' => $assignment->assignee?->name,
                                        'status' => $assignment->status,
                                        'creator_name' => $task->creator->name,
                                        'rejection_reason' => $assignment->rejection_reason,
                                    ];
                                @endphp

                                <div class="bg-card border border-border rounded-lg p-3 hover:border-foreground/20 transition-colors cursor-pointer"
                                    x-on:click="selectedTask = @js($taskData)">
                                    <p class="text-sm font-medium text-card-foreground leading-snug">
                                        {{ $assignment->task->title }}</p>
                                    @if ($assignment->task->description)
                                        <p class="text-xs text-muted-foreground mt-1.5 line-clamp-2">
                                            {{ $assignment->task->description }}</p>
                                    @endif

                                    @if ($key === 'pending')
                                        <div class="flex gap-2 mt-3">
                                            <form method="POST"
                                                action="{{ route('assignments.respond', $assignment) }}" class="flex-1">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="accepted">
                                                <button
                                                    class="w-full text-xs bg-primary text-primary-foreground rounded-md py-1.5 hover:opacity-90 transition">
                                                    Accept
                                                </button>
                                            </form>
                                            <form method="POST"
                                                action="{{ route('assignments.respond', $assignment) }}" class="flex-1"
                                                onsubmit="return this.rejection_reason.value = prompt('Reason for declining?') ?? ''">
                                                @csrf
                                                @method('PATCH')

                                                <input type="hidden" name="status" value="rejected">
                                                <input type="hidden" name="rejection_reason">
                                                <button
                                                    class="w-full text-xs border border-border text-muted-foreground rounded-md py-1.5 hover:bg-muted transition">
                                                    Decline
                                                </button>
                                            </form>
                                        </div>
                                    @elseif ($key === 'in_progress')
                                        <form method="POST" action="{{ route('assignments.complete', $assignment) }}"
                                            class="mt-3">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                class="w-full text-xs bg-secondary text-secondary-foreground rounded-md py-1.5 hover:opacity-90 transition">
                                                Mark Complete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @empty
                                <p class="text-xs text-muted-foreground text-center py-6">Nothing here yet.</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
            <x-task-detail-modal />
        </div>
    </div>

    <x-modal name="create-workspace-task-modal" focusable>
        <form method="POST" action="{{ route('teams.workspace.tasks.store', $team) }}" class="p-6">
            @csrf
            <h2 class="text-lg font-semibold text-foreground">New task</h2>
            <p class="text-sm text-muted-foreground mt-1">Create a task for yourself — it's assigned automatically.</p>

            <div class="mt-5">
                <x-input-label for="ws_task_title" value="Title" />
                <x-text-input id="ws_task_title" name="title" type="text" class="block mt-1.5 w-full" required
                    autofocus />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="ws_task_description" value="Description (optional)" />
                <textarea id="ws_task_description" name="description" rows="3"
                    class="w-full mt-1.5 rounded-lg border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <x-input-label for="ws_task_priority" value="Priority" />
                    <select id="ws_task_priority" name="priority"
                        class="w-full mt-1.5 rounded-lg border border-input bg-background px-3 py-2 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-ring">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div>
                    <x-input-label for="ws_task_due_date" value="Due date (optional)" />
                    <x-text-input id="ws_task_due_date" name="due_date" type="date" class="block mt-1.5 w-full" />
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
