<?php

namespace App\Support;

class DoctorMenu
{
    public static function items(): array
    {
        return [

            [
                'label' => 'Dashboard',
                'icon'  => 'fa-solid fa-house',
                'route' => route('doctor.dashboard'),
            ],

            [
                'label' => 'Verifikasi CPPT',
                'icon'  => 'fa-solid fa-file-medical',
                'route' => route('doctor.cppts.index'),
            ],

        ];
    }
}