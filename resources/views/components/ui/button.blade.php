@props([
    'type' => 'button',
    'href' => null,
    'variant' => 'primary',
    'size' => 'md',
])

@php
    $classes = ['sigap-button', 'sigap-button--' . $variant, 'sigap-button--' . $size];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}>{{ $slot }}</button>
@endif
