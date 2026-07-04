@props([
    'title',
    'description' => null,
    'tone' => 'dark',
])

<div {{ $attributes->class(['sigap-section-title', 'sigap-section-title--green' => $tone === 'green']) }}>
    <h1>{{ $title }}</h1>
    @if ($description)
        <p>{{ $description }}</p>
    @endif
</div>
