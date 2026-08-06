@props(['name' => 'modal', 'show' => false, 'maxWidth' => '2xl'])

@php
    $id = $name . '-modal';
    $maxWidth = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
    ][$maxWidth];
@endphp

<div x-data="{ show: @js($show), focusables() { let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])'; return [...$el.querySelectorAll(selector)].filter(el => !el.hasAttribute('disabled')) }, firstFocusable() { return this.focusables()[0] }, lastFocusable() { let focusable = this.focusables(); return focusable[focusable.length - 1] }, nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() }, prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() }, nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) }, prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) - 1 } }" x-on:close.stop="show = false" x-on:keydown.escape.window="show = false"
    x-on:{{ $name }}.window="show = true" x-trap.inert.noscroll="show" x-show="show"
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" style="display: none;">
    <div x-show="show" class="fixed inset-0 bg-black/50 transform transition-all z-40" x-on:click="show = false"
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    <div x-show="show"
        class="mb-6 bg-card border border-border rounded-xl overflow-hidden shadow-xl transform transition-all sm:w-full {{ $maxWidth }} sm:mx-auto z-60 relative"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        {{ $slot }}
    </div>
</div>
