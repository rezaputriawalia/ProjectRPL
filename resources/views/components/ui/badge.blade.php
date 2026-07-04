@props([
    'tone' => 'primary',
])

<span {{ $attributes->class(['sigap-badge', 'sigap-badge--' . $tone]) }}>
    {{ $slot }}
</span>
