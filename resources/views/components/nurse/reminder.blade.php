@props([
    'title',
    'value',
    'description' => null,
    'tone' => 'warning',
    'icon' => 'fa-solid fa-circle-exclamation',
])

<div {{ $attributes->class(['nurse-reminder', 'nurse-reminder--' . $tone]) }}>
    <span class="nurse-reminder__icon" aria-hidden="true"><i class="{{ $icon }}"></i></span>
    <div>
        <strong>{{ $title }}</strong>
        <span>{{ number_format($value) }}</span>
        @if ($description)
            <p>{{ $description }}</p>
        @endif
    </div>
</div>
