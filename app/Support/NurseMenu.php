<?php

namespace App\Support;

class NurseMenu
{
    public static function items(): array
    {
        return [

            [
                'key' => 'dashboard',
                'label' => 'Dashboard',
                'href' => route('perawat.dashboard'),
                'icon' => 'fa-solid fa-house',
            ],

            [
                'key' => 'patients',
                'label' => 'Data Pasien',
                'href' => route('perawat.patients.index'),
                'icon' => 'fa-solid fa-bed-pulse',
            ],

        ];
    }
}