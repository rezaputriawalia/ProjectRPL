@props([
    'label',
    'value',
    'meta' => null,
    'tone' => 'primary',
    'icon' => null,
])

<x-ui.card {{ $attributes->class(['sigap-stat-card', 'sigap-stat-card--' . $tone]) }}>
    <div>
        <span class="sigap-stat-card__label">{{ $label }}</span>
        <strong class="sigap-stat-card__value">{{ $value }}</strong>
        @if ($meta)
            <span class="sigap-stat-card__meta">{{ $meta }}</span>
        @endif
    </div>

    <span class="sigap-stat-card__icon" aria-hidden="true">
        @if ($icon)
            {!! $icon !!}
        @endif
    </span>
</x-ui.card>
