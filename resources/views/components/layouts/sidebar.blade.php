@props([
    'role' => 'perawat',
    'brand' => null,
    'subtitle' => 'Rumah Sakit Jiwa',
    'items' => [],
    'active' => null,
    'ctaLabel' => 'Pendaftaran Baru',
    'ctaHref' => '#',
])

@php
    $brandText = $brand ?? 'SIGAP ' . str($role)->title();
@endphp

<aside class="sigap-sidebar">
    <div>
        <a href="{{ url('/') }}" class="sigap-brand" aria-label="SIGAP">
            <span class="sigap-brand__title">{{ $brandText }}</span>
            <span class="sigap-brand__subtitle">{{ $subtitle }}</span>
        </a>

        <nav class="sigap-sidebar__nav" aria-label="Navigasi utama">
            @foreach ($items as $item)
                @php
                    $key = $item['key'] ?? ($item['label'] ?? '');
                    $isActive = $active === $key || ($item['active'] ?? false);
                    $href = $item['route'] ?? $item['href'] ?? '#';
                    $icon = $item['icon'] ?? null;
                @endphp

                <a href="{{ $href }}" @class(['sigap-nav-item', 'is-active' => $isActive])>
                    <span class="sigap-nav-item__icon" aria-hidden="true">
                        @if ($icon)
                            <i class="{{ $icon }}"></i>
                        @else
                            <span class="sigap-icon-placeholder"></span>
                        @endif
                    </span>
                    <span>{{ $item['label'] ?? $key }}</span>
                </a>
            @endforeach
        </nav>
    </div>

    {{-- @if ($role !== 'admin') --}}
    <div class="sigap-sidebar__bottom">

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="sigap-nav-item sigap-sidebar__logout">

                <span class="sigap-nav-item__icon">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </span>

                <span>Logout</span>

            </button>

        </form>

    </div>
    {{-- @endif --}}
</aside>
