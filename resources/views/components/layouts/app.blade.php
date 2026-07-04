@props([
    'title' => 'SIGAP',
    'role' => 'perawat',
    'brand' => null,
    'subtitle' => 'Rumah Sakit Jiwa',
    'navItems' => [],
    'active' => null,
    'userName' => 'Pengguna SIGAP',
    'userRole' => null,
    'searchPlaceholder' => 'Cari data rekam medis...',
    'showSearch' => true,
    'showFooter' => true,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} - SIGAP</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|lora:600,700" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="sigap-body">
    <div class="sigap-shell">
        <x-layouts.sidebar :role="$role" :brand="$brand" :subtitle="$subtitle" :items="$navItems" :active="$active" />

        <div class="sigap-main">
            <x-layouts.navbar :title="$title" :user-name="$userName" :user-role="$userRole ?? $role" :search-placeholder="$searchPlaceholder" :show-search="$showSearch" />

            <main class="sigap-content">
                {{ $slot }}
            </main>

            @if ($showFooter)
                <x-layouts.footer />
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>
