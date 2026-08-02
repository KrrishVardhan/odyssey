@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'text-sm font-medium text-primary bg-secondary rounded-lg px-3 py-2']) }}>
        {{ $status }}
    </div>
@endif
