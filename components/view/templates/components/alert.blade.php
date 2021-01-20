@props([
    'type' => 'info',
    'message'
])

<div {{ $attributes->merge(['class' => 'alert alert-' . $type]) }}>
    {{ $message }}
</div>
