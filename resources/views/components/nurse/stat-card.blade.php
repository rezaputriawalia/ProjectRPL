@props([
    'label',
    'value',
    'meta' => null,
    'icon' => 'fa-solid fa-chart-simple',
    'tone' => 'green',
])

<section {{ $attributes->class(['nurse-stat-card', 'nurse-stat-card--' . $tone]) }}>
    <div>
        <span class="nurse-stat-card__label">{{ $label }}</span>
        <strong class="nurse-stat-card__value">{{ number_format($value) }}</strong>
        @if ($meta)
            <span class="nurse-stat-card__meta">{{ $meta }}</span>
        @endif
    </div>
    <span class="nurse-stat-card__icon" aria-hidden="true">
        <i class="{{ $icon }}"></i>
    </span>
</section>
