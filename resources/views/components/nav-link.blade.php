@props(['active'])
@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-muted-foreground hover:text-foreground hover:border-border focus:outline-none focus:text-foreground focus:border-border transition duration-150 ease-in-out';
@endphp
<a {{ $attributes->merge(['class' => $classes]) }} @if($active ?? false) style="border-color: var(--chart-1); color: var(--chart-1);" @endif>
    {{ $slot }}
</a>
