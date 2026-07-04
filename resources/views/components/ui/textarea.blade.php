@props([
    'label' => null,
    'name',
])

<label class="sigap-field">
    @if ($label)
        <span class="sigap-field__label">{{ $label }}</span>
    @endif

    <textarea name="{{ $name }}" {{ $attributes->class(['sigap-textarea']) }}>{{ $slot }}</textarea>
</label>
