<x-app-layout :active-team-id="$team->id">
    <div class="flex h-screen">
        <x-team-nav :team="$team" active="chat" />

        <div class="flex-1 flex flex-col" x-data="chat({{ $team->id }}, {{ $oldestId ?? 'null' }})">

            <div class="px-6 py-4 border-b border-border flex items-center gap-2">
                <x-lucide-hash class="w-4 h-4 text-muted-foreground" />
                <h2 class="font-medium text-foreground">general-chat</h2>
            </div>

            <div x-ref="scrollArea" @scroll="onScroll" class="flex-1 overflow-y-auto px-6 py-4 flex flex-col">

                <div class="text-center" x-show="loadingMore">
                    <span class="text-xs text-muted-foreground">Loading older messages…</span>
                </div>
                <div class="text-center" x-show="!hasMore && !loadingMore">
                    <span class="text-xs text-muted-foreground">You've reached the start of #general-chat.</span>
                </div>

                <template x-for="message in messages" :key="message.id">
                    <div class="flex gap-3 py-1.5" :class="message.is_me ? 'flex-row-reverse' : ''">
                        {{-- Avatar --}}
                        <div class="w-8 h-8 rounded-full bg-secondary flex items-center justify-center shrink-0 mt-0.5">
                            <span class="text-xs font-mono font-semibold text-secondary-foreground"
                                x-text="message.user_name.charAt(0)"></span>
                        </div>
                        <div :class="message.is_me ? 'items-end' : 'items-start'" class="flex flex-col max-w-md">
                            <div class="flex items-baseline gap-2" :class="message.is_me ? 'flex-row-reverse' : ''">
                                <span class="text-sm font-medium text-foreground" x-text="message.user_name"></span>
                                <span class="text-xs text-muted-foreground" x-text="message.created_at"></span>
                            </div>
                            <div class="mt-1 px-3 py-2 rounded-lg text-sm"
                                :class="message.is_me ? 'bg-primary text-primary-foreground' : 'bg-muted text-foreground'"
                                x-text="message.body"></div>
                        </div>
                    </div>
                </template>

                <div x-show="messages.length === 0" class="flex-1 flex items-center justify-center">
                    <p class="text-sm text-muted-foreground">No messages yet. Say hi to {{ $team->name }}.</p>
                </div>
            </div>

            <form @submit.prevent="send" class="p-4 border-t border-border flex gap-3">
                <input x-model="draft" type="text" placeholder="Message #general-chat"
                    class="flex-1 rounded-lg border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-transparent">
                <button type="submit"
                    class="p-4 bg-primary text-primary-foreground rounded-lg text-sm font-medium hover:opacity-90 transition">
                    <x-lucide-send class="w-4 h-4" />
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
@php
    $chatMessages = $messages->map(
        fn($m) => [
            'id' => $m->id,
            'body' => $m->body,
            'user_name' => $m->user->name,
            'is_me' => $m->user_id === auth()->id(),
            'created_at' => $m->created_at->format('g:i A'),
        ],
    );
@endphp

<script>
    function chat(teamId, oldestId) {
        return {
            messages: @json($chatMessages),
            oldestId: oldestId,
            hasMore: true,
            loadingMore: false,
            draft: '',

            init() {
                this.$nextTick(() => this.scrollToBottom());

                // reverb
                window.Echo.private(`team.${teamId}`)
                    .listen('MessageSent', (e) => {
                        this.messages.push({
                            id: e.id,
                            body: e.body,
                            user_name: e.user_name,
                            is_me: e.user_id === {{ auth()->id() }},
                            created_at: e.created_at,
                        });
                        this.$nextTick(() => this.scrollToBottom());
                    });
            },

            scrollToBottom() {
                this.$refs.scrollArea.scrollTop = this.$refs.scrollArea.scrollHeight;
            },

            onScroll() {
                if (this.$refs.scrollArea.scrollTop < 100 && this.hasMore && !this.loadingMore) {
                    this.loadOlder();
                }
            },

            async loadOlder() {
                if (!this.oldestId) return;
                this.loadingMore = true;

                const prevHeight = this.$refs.scrollArea.scrollHeight;

                const res = await fetch(`/teams/${teamId}/chat/older?before=${this.oldestId}`);
                const data = await res.json();

                this.messages = [...data.messages, ...this.messages];
                this.hasMore = data.has_more;
                this.oldestId = data.messages[0]?.id ?? this.oldestId;
                this.loadingMore = false;

                this.$nextTick(() => {
                    this.$refs.scrollArea.scrollTop = this.$refs.scrollArea.scrollHeight - prevHeight;
                });
            },

            async send() {
                if (!this.draft.trim()) return;

                const body = this.draft;
                this.draft = '';

                try {
                    const res = await fetch(`/teams/${teamId}/chat`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            body
                        }),
                    });

                    if (!res.ok) throw new Error(`Send failed: ${res.status}`);

                    const message = await res.json();
                    console.log('received back from server:', message);
                    console.log('messages array before push:', this.messages.length);


                    console.log('messages array after push:', this.messages.length);
                    this.$nextTick(() => this.scrollToBottom());
                } catch (e) {
                    console.error('send() failed:', e);
                    this.draft = body;
                }
            }
        }
    }
</script>
