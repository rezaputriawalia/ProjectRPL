@props([
    'title' => 'SIGAP',
    'userName' => 'Pengguna SIGAP',
    'userRole' => 'Perawat',
    'searchPlaceholder' => 'Cari data rekam medis...',
    'showSearch' => true,
    'avatar' => null,
])

<header class="sigap-navbar">
    <div class="sigap-navbar__left">
        @if ($showSearch)
            <form action="#" method="GET" class="sigap-search" role="search">
                <span class="sigap-search__icon" aria-hidden="true"></span>
                <input type="search" name="q" placeholder="{{ $searchPlaceholder }}" autocomplete="off">
            </form>
        @else
            <span class="sigap-navbar__title">{{ $title }}</span>
        @endif
    </div>

    <div class="sigap-navbar__right">
        <button type="button" class="sigap-icon-button sigap-icon-button--bell" aria-label="Notifikasi">
            <span class="sigap-notification-dot"></span>
        </button>
        <button type="button" class="sigap-icon-button sigap-icon-button--gear" aria-label="Pengaturan"></button>
        <span class="sigap-navbar__divider"></span>
        <div class="sigap-user">
            <div class="sigap-user__meta">
                <strong>{{ $userName }}</strong>
                <span>{{ str($userRole)->title() }}</span>
            </div>
            @if ($avatar)
                <img src="{{ $avatar }}" alt="{{ $userName }}" class="sigap-user__avatar">
            @else
                <span class="sigap-user__avatar sigap-user__avatar--empty" aria-hidden="true">{{ str($userName)->substr(0, 1)->upper() }}</span>
            @endif
        </div>
    </div>
</header>