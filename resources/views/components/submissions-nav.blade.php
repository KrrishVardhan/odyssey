@props(['team', 'type', 'status'])

<div class="w-64 bg-card border border-border flex flex-col shrink-0 mx-3 my-2 rounded-xl px-1">
    <div class="px-4 py-4 border-b border-border">
        <a href="{{ route('teams.show', $team) }}"
            class="flex items-center gap-1.5 text-sm text-muted-foreground hover:text-foreground transition-colors">
            <x-lucide-arrow-left class="h-4 w-4" />
            Back to board
        </a>
    </div>

    <div class="p-3">
        <p class="px-2 text-xs font-medium text-muted-foreground uppercase tracking-wide mb-2">
            Type
        </p>

        @php
            $types = [
                '' => ['label' => 'All', 'icon' => 'layout-grid'],
                'bug' => ['label' => 'Bug', 'icon' => 'bug'],
                'feature' => ['label' => 'Feature', 'icon' => 'lightbulb'],
                'improvement' => ['label' => 'Improvement', 'icon' => 'wrench'],
                'feedback' => ['label' => 'Feedback', 'icon' => 'message-circle'],
                'question' => ['label' => 'Question', 'icon' => 'circle-help'],
            ];
        @endphp

        <div class="flex flex-col gap-0.5">
            @foreach ($types as $value => $meta)
                <a href="{{ route('teams.submissions', array_filter(['team' => $team->id, 'type' => $value, 'status' => $status])) }}"
                    class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-sm transition-colors
                        {{ $type === $value ? 'bg-secondary text-foreground font-medium' : 'text-muted-foreground hover:bg-muted hover:text-foreground' }}">
                    <x-dynamic-component :component="'lucide-' . $meta['icon']"
                        class="h-4 w-4 {{ $value === 'bug' && $type === $value ? 'text-destructive' : '' }}" />
                    {{ $meta['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="p-3 border-t border-border">
        <p class="px-2 text-xs font-medium text-muted-foreground uppercase tracking-wide mb-2">
            Status
        </p>

        @php
            $statuses = [
                '' => ['label' => 'All', 'icon' => 'list'],
                'submitted' => ['label' => 'New', 'icon' => 'circle-dot'],
                'under_review' => ['label' => 'Under review', 'icon' => 'eye'],
                'approved' => ['label' => 'Approved', 'icon' => 'check-circle-2'],
                'rejected' => ['label' => 'Rejected', 'icon' => 'x-circle'],
                'resolved' => ['label' => 'Resolved', 'icon' => 'check-check'],
            ];
        @endphp

        <div class="flex flex-col gap-0.5">
            @foreach ($statuses as $value => $meta)
                <a href="{{ route('teams.submissions', array_filter(['team' => $team->id, 'type' => $type, 'status' => $value])) }}"
                    class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-sm transition-colors
                        {{ $status === $value ? 'bg-secondary text-foreground font-medium' : 'text-muted-foreground hover:bg-muted hover:text-foreground' }}">
                    <x-dynamic-component :component="'lucide-' . $meta['icon']" class="h-4 w-4" />
                    {{ $meta['label'] }}
                </a>
            @endforeach
        </div>
    </div>
</div>
