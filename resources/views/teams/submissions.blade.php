<x-app-layout :active-team-id="$team->id">
    <div class="flex h-screen">
        <x-team-nav :team="$team" active="submissions" />

        <div class="flex-1 overflow-y-auto p-6 max-w-3xl">
            <h1 class="text-lg font-semibold text-foreground mb-1">
                Submissions
            </h1>

            <p class="text-sm text-muted-foreground mb-6">
                What customers are telling you about {{ $team->name }}.
            </p>

            @php
                $typeIcon = [
                    'bug' => 'bug',
                    'feature' => 'lightbulb',
                    'feedback' => 'message-circle',
                    'improvement' => 'wrench',
                    'question' => 'circle-help',
                ];
            @endphp

            @foreach (['submitted', 'under_review', 'approved', 'resolved', 'rejected'] as $status)
                @if (($submissions[$status] ?? collect())->isNotEmpty())
                    <div class="mb-6">
                        <p class="text-xs font-medium text-muted-foreground uppercase tracking-wide mb-2">
                            {{ str_replace('_', ' ', $status) }}
                        </p>

                        <div class="space-y-2">
                            @foreach ($submissions[$status] as $submission)
                                <div class="bg-card border border-border rounded-lg p-4">
                                    <div class="flex items-start justify-between gap-3">

                                        <div class="flex gap-2">
                                            <div class="mt-0.5 text-muted-foreground">
                                                @if (isset($typeIcon[$submission->type]))
                                                    <div
                                                        class="mt-0.5 {{ $submission->type === 'bug' ? 'text-destructive' : 'text-muted-foreground' }}">
                                                        @if (isset($typeIcon[$submission->type]))
                                                            <x-dynamic-component :component="'lucide-' . $typeIcon[$submission->type]" class="h-4 w-4" />
                                                        @else
                                                            <x-lucide-file-text class="h-4 w-4" />
                                                        @endif
                                                    </div>
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

                                        <form method="POST"
                                            action="{{ route('submissions.updateStatus', $submission) }}">
                                            @csrf
                                            @method('PATCH')

                                            <select name="status" onchange="this.form.submit()"
                                                class="text-xs bg-muted border border-border rounded-md px-2 py-1 text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring">
                                                <option value="submitted" @selected($submission->status === 'submitted')>
                                                    Submitted
                                                </option>

                                                <option value="under_review" @selected($submission->status === 'under_review')>
                                                    Under review
                                                </option>

                                                <option value="approved" @selected($submission->status === 'approved')>
                                                    Approved
                                                </option>

                                                <option value="resolved" @selected($submission->status === 'resolved')>
                                                    Resolved
                                                </option>

                                                <option value="rejected" @selected($submission->status === 'rejected')>
                                                    Rejected
                                                </option>
                                            </select>
                                        </form>
                                    </div>

                                    <p class="text-sm text-muted-foreground mt-3">
                                        {{ $submission->description }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            @if ($submissions->isEmpty())
                <div class="flex flex-col items-center justify-center text-center py-16">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-muted mb-3">
                        <x-lucide-inbox class="h-5 w-5 text-muted-foreground" />
                    </div>

                    <p class="text-sm text-muted-foreground">
                        No submissions yet.
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
