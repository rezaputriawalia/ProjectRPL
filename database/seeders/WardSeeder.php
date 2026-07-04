<?php

namespace Database\Seeders;

use App\Models\Ward;
use Illuminate\Database\Seeder;

class WardSeeder extends Seeder
{
    public function run(): void
    {
        $wards = [
            ['name' => 'Anggrek',    'capacity' => 20],
            ['name' => 'Melati',     'capacity' => 24],
            ['name' => 'Mawar',      'capacity' => 18],
            ['name' => 'Cempaka',    'capacity' => 22],
            ['name' => 'Flamboyan',  'capacity' => 30],
            ['name' => 'Kenanga',    'capacity' => 16],
            ['name' => 'Bougenville','capacity' => 20],
            ['name' => 'Teratai',    'capacity' => 25],
            ['name' => 'Dahlia',     'capacity' => 15],
            ['name' => 'ICU',        'capacity' => 8],
            ['name' => 'NICU',       'capacity' => 6],
            ['name' => 'Isolasi',    'capacity' => 10],
        ];

        foreach ($wards as $ward) {

            Ward::updateOrCreate(

                ['name' => $ward['name']],

                [
                    'capacity' => $ward['capacity'],
                    'description' => 'Bangsal ' . $ward['name'],
                ]

            );

        }
    }
}