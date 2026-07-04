@props([
    'label' => null,
    'name',
])

<label class="sigap-field">
    @if ($label)
        <span class="sigap-field__label">{{ $label }}</span>
    @endif

    <select name="{{ $name }}" {{ $attributes->class(['sigap-select']) }}>
        {{ $slot }}
    </select>
</label>
