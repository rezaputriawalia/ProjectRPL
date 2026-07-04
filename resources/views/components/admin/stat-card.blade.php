@props([
    'label',
    'value',
    'description' => null,
    'icon' => 'fa-solid fa-chart-simple',
    'accent' => 'green',
])

<section {{ $attributes->class(['admin-stat-card', 'admin-stat-card--' . $accent]) }}>
    <div class="admin-stat-card__body">
        <span class="admin-stat-card__label">{{ $label }}</span>
        <strong class="admin-stat-card__value">{{ number_format($value) }}</strong>
        @if ($description)
            <span class="admin-stat-card__description">{{ $description }}</span>
        @endif
    </div>
    <span class="admin-stat-card__icon" aria-hidden="true">
        <i class="{{ $icon }}"></i>
    </span>
</section>
