@props([
    'variant' => 'white',
    'padding' => 'md',
])

<section {{ $attributes->class([
    'sigap-card',
    'sigap-card--muted' => $variant === 'muted',
    'sigap-card--primary' => $variant === 'primary',
    'sigap-card--warning' => $variant === 'warning',
    'sigap-card--compact' => $padding === 'sm',
    'sigap-card--loose' => $padding === 'lg',
]) }}>
    {{ $slot }}
</section>
