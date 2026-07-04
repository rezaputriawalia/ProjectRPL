@props([
    'label' => null,
    'name',
    'type' => 'text',
    'readonly' => false,
])

<label class="sigap-field">
    @if ($label)
        <span class="sigap-field__label">{{ $label }}</span>
    @endif

    <input type="{{ $type }}" name="{{ $name }}" @readonly($readonly) {{ $attributes->class(['sigap-input', 'sigap-input--readonly' => $readonly]) }}>
</label>
