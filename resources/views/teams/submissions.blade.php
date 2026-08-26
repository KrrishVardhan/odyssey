<x-app-layout :active-team-id="$team->id">
    <div class="flex h-screen">
        <x-submissions-nav :team="$team" :type="$type" :status="$status" />

        <div class="flex-1 overflow-y-auto p-6" x-data="{ taskModal: null }">
            <h1 class="text-lg font-semibold text-foreground mb-6">
                Submissions
            </h1>

            @php
                $typeIcon = [
                    'bug' => 'bug',
                    'feature' => 'lightbulb',
                    'improvement' => 'wrench',
                    'feedback' => 'message-circle',
                    'question' => 'circle-help',
                ];

                $statusStyle = [
                    'submitted' => 'bg-secondary text-secondary-foreground',
                    'under_review' => 'bg-muted text-muted-foreground',
                    'approved' => 'bg-primary/10 text-primary',
                    'rejected' => 'bg-destructive/10 text-destructive',
                    'resolved' => 'bg-muted text-muted-foreground',
                ];
            @endphp

            <div class="space-y-3 max-w-2xl">
                @forelse ($submissions as $submission)
                    <div class="bg-card border border-border rounded-lg p-4">
                        <div class="flex items-start justify-between gap-3">

                            <div class="flex gap-2">
                                <div
                                    class="mt-0.5 {{ $submission->type === 'bug' ? 'text-destructive' : 'text-muted-foreground' }}">
                                    @if (isset($typeIcon[$submission->type]))
                                        <x-dynamic-component :component="'lucide-' . $typeIcon[$submission->type]" class="h-4 w-4" />
                                    @else
                                        <x-lucide-file-text class="h-4 w-4" />
                                    @endif
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-foreground">
                                        {{ $submission->title }}
                                    </p>

                                    <p class="text-xs text-muted-foreground mt-0.5">
                                        {{ $submission->isAnonymous() ? $submission->submitter_email ?? 'Anonymous' : $submission->submitter->name }}
                                        · {{ $submission->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            <span
                                class="text-xs font-medium px-2 py-1 rounded-full shrink-0 {{ $statusStyle[$submission->status] }}">
                                {{ str_replace('_', ' ', $submission->status) }}
                            </span>
                        </div>

                        <p class="text-sm text-muted-foreground mt-3">
                            {{ $submission->description }}
                        </p>

                        @if ($submission->tasks->isNotEmpty())
                            <div class="mt-3 pt-3 border-t border-border">
                                <p class="text-xs text-muted-foreground mb-1.5 flex items-center gap-1.5">
                                    <x-lucide-check-square class="h-3.5 w-3.5" />
                                    {{ $submission->tasks->count() }}
                                    {{ Str::plural('task', $submission->tasks->count()) }} created
                                </p>

                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($submission->tasks as $task)
                                        <span class="text-xs bg-muted text-muted-foreground rounded-md px-2 py-1">
                                            {{ $task->title }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @elseif (in_array($submission->status, ['submitted', 'under_review']))
                            <div class="flex gap-2 mt-3">
                                <button type="button" x-on:click="taskModal = {{ $submission->id }}"
                                    class="flex items-center gap-1.5 text-xs bg-primary text-primary-foreground rounded-md px-3 py-1.5 hover:opacity-90 transition">
                                    <x-lucide-plus class="h-3.5 w-3.5" />
                                    Create tasks
                                </button>

                                <form method="POST" action="{{ route('submissions.reject', $submission) }}">
                                    @csrf
                                    @method('PATCH')

                                    <button
                                        class="flex items-center gap-1.5 text-xs border border-border text-muted-foreground rounded-md px-3 py-1.5 hover:bg-muted transition">
                                        <x-lucide-x class="h-3.5 w-3.5" />
                                        Reject
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    {{-- Task-creation modal for this submission --}}
                    <div x-show="taskModal === {{ $submission->id }}" x-cloak
                        class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center px-4"
                        x-on:click.self="taskModal = null" x-on:keydown.escape.window="taskModal = null"
                        x-data="{ tasks: [{ title: '{{ addslashes($submission->title) }}', description: '', priority: 'medium', due_date: '' }] }">
                        <div
                            class="bg-card border border-border rounded-xl shadow-xl w-full max-w-lg max-h-[85vh] overflow-y-auto">
                            <form method="POST" action="{{ route('submissions.tasks.store', $submission) }}"
                                class="p-6">
                                @csrf

                                <h2 class="text-lg font-semibold text-foreground">
                                    Create tasks
                                </h2>

                                <p class="text-sm text-muted-foreground mt-1">
                                    From: {{ $submission->title }}
                                </p>

                                <template x-for="(task, index) in tasks" :key="index">
                                    <div class="mt-4 p-3 border border-border rounded-lg space-y-2 relative">
                                        <button type="button" x-show="tasks.length > 1"
                                            x-on:click="tasks.splice(index, 1)"
                                            class="absolute top-2 right-2 text-muted-foreground hover:text-destructive">
                                            <x-lucide-x class="h-3.5 w-3.5" />
                                        </button>

                                        <input :name="`tasks[${index}][title]`" x-model="task.title" type="text"
                                            required placeholder="Task title"
                                            class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-ring">

                                        <textarea :name="`tasks[${index}][description]`" x-model="task.description" rows="2"
                                            placeholder="Description (optional)"
                                            class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-ring"></textarea>

                                        <div class="grid grid-cols-2 gap-2">
                                            <select :name="`tasks[${index}][priority]`" x-model="task.priority"
                                                class="rounded-lg border border-input bg-background px-2 py-1.5 text-xs text-foreground focus:outline-none focus:ring-2 focus:ring-ring">
                                                <option value="low">Low</option>
                                                <option value="medium">Medium</option>
                                                <option value="high">High</option>
                                            </select>

                                            <input :name="`tasks[${index}][due_date]`" x-model="task.due_date"
                                                type="date"
                                                class="rounded-lg border border-input bg-background px-2 py-1.5 text-xs text-foreground focus:outline-none focus:ring-2 focus:ring-ring">
                                        </div>
                                    </div>
                                </template>

                                <button type="button"
                                    x-on:click="tasks.push({ title: '', description: '', priority: 'medium', due_date: '' })"
                                    class="mt-3 flex items-center gap-1.5 text-sm text-primary hover:underline">
                                    <x-lucide-plus class="h-3.5 w-3.5" />
                                    Add another task
                                </button>

                                <div class="mt-6 flex justify-end gap-3">
                                    <button type="button" x-on:click="taskModal = null"
                                        class="px-4 py-2 text-sm text-muted-foreground hover:text-foreground transition-colors">
                                        Cancel
                                    </button>

                                    <x-primary-button>
                                        Create all tasks
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center text-center py-16">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-muted mb-3">
                            <x-lucide-inbox class="h-5 w-5 text-muted-foreground" />
                        </div>

                        <p class="text-sm text-muted-foreground">
                            No submissions match these filters.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
