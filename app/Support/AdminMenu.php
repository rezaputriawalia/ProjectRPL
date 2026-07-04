<?php

namespace App\Support;

class AdminMenu
{
    public static function items(): array
    {
        return [

            [
                'key' => 'beranda',
                'label' => 'Dashboard',
                'href' => route('admin.dashboard'),
                'icon' => 'fa-solid fa-house',
            ],

            [
                'key' => 'users',
                'label' => 'Manajemen User',
                'href' => route('admin.users.index'),
                'icon' => 'fa-solid fa-users',
            ],

            [
                'key' => 'wards',
                'label' => 'Kelola Bangsal',
                // 'href' => '#',
                'href' => route('admin.wards.index'),
                'icon' => 'fa-solid fa-hospital',
            ],

            [
                'key' => 'rooms',
                'label' => 'Kelola Ruangan',
                // 'href' => '#',
                'href' => route('admin.rooms.index'),
                'icon' => 'fa-solid fa-door-open',
            ],

            [
                'key' => 'patients',
                'label' => 'Data Pasien',
                'href' => route('admin.patients.index'),
                'icon' => 'fa-solid fa-bed',
            ],

        ];
    }
}
