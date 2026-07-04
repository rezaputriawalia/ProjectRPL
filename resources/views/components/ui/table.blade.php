@props([
    'footer' => null,
])

<div {{ $attributes->class(['sigap-table-card']) }}>
    <div class="sigap-table-card__scroll">
        <table class="sigap-table">
            {{ $slot }}
        </table>
    </div>

    @if ($footer)
        <div class="sigap-table-card__footer">
            {{ $footer }}
        </div>
    @endif
</div>
