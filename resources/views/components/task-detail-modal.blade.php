<div x-show="selectedTask" x-cloak class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center px-4"
    x-on:click.self="selectedTask = null" x-on:keydown.escape.window="selectedTask = null">

    <div x-show="selectedTask" class="bg-card border border-border rounded-xl shadow-xl w-full max-w-lg p-6"
        x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100">

        <template x-if="selectedTask">
            <div>
                <div class="flex items-start justify-between gap-4">
                    <h2 class="text-lg font-semibold text-foreground" x-text="selectedTask.title"></h2>
                    <button x-on:click="selectedTask = null"
                        class="text-muted-foreground hover:text-foreground shrink-0">
                        <x-lucide-x class="w-5 h-5" />
                    </button>
                </div>

                <p class="text-sm text-muted-foreground mt-3" x-show="selectedTask.description"
                    x-text="selectedTask.description"></p>
                <p class="text-sm text-muted-foreground mt-3 italic" x-show="!selectedTask.description">No description
                    provided.</p>

                <div class="grid grid-cols-2 gap-4 mt-6 pt-5 border-t border-border">
                    <div>
                        <p class="text-xs text-muted-foreground uppercase tracking-wide">Priority</p>
                        <p class="text-sm font-medium mt-1 capitalize"
                            :class="{
                                'text-destructive': selectedTask.priority === 'high',
                                'text-foreground': selectedTask.priority === 'medium',
                                'text-emerald-500': selectedTask.priority === 'low'
                            }"
                            x-text="selectedTask.priority"></p>
                    </div>
                    <div>
                        <p class="text-xs text-muted-foreground uppercase tracking-wide">Due date</p>
                        <p class="text-sm font-medium text-foreground mt-1"
                            x-text="selectedTask.due_date || 'No due date'"></p>
                    </div>
                    <div>
                        <p class="text-xs text-muted-foreground uppercase tracking-wide">Assigned to</p>
                        <p class="text-sm font-medium text-foreground mt-1"
                            x-text="selectedTask.assignee_name || 'Unassigned'"></p>
                    </div>
                    <div>
                        <p class="text-xs text-muted-foreground uppercase tracking-wide">Status</p>
                        <p class="text-sm font-medium text-foreground mt-1 capitalize"
                            x-text="(selectedTask.status || 'pending').replace('_', ' ')"></p>
                    </div>
                    <div>
                        <p class="text-xs text-muted-foreground uppercase tracking-wide">Created by</p>
                        <p class="text-sm font-medium text-foreground mt-1" x-text="selectedTask.creator_name"></p>
                    </div>
                </div>

                <div x-show="selectedTask.rejection_reason"
                    class="mt-4 bg-destructive/10 border border-destructive/20 rounded-lg p-3">
                    <p class="text-xs font-medium text-destructive">Decline reason</p>
                    <p class="text-sm text-foreground mt-1" x-text="selectedTask.rejection_reason"></p>
                </div>
            </div>
        </template>
    </div>
</div>
