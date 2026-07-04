@props([
    'title',
    'description' => null,
    'tone' => 'success',
    'icon' => 'fa-solid fa-circle-info',
])

<div {{ $attributes->class(['admin-notification', 'admin-notification--' . $tone]) }}>
    <span class="admin-notification__icon" aria-hidden="true">
        <i class="{{ $icon }}"></i>
    </span>
    <div>
        <strong>{{ $title }}</strong>
        @if ($description)
            <p>{{ $description }}</p>
        @endif
    </div>
</div>
