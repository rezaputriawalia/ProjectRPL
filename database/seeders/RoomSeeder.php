<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\Ward;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [

            // Bangsal Anggrek
            ['ward' => 'Anggrek', 'name' => 'Anggrek A', 'capacity' => 4, 'status' => 'available'],
            ['ward' => 'Anggrek', 'name' => 'Anggrek B', 'capacity' => 4, 'status' => 'full'],

            // Bangsal Melati
            ['ward' => 'Melati', 'name' => 'Melati A', 'capacity' => 6, 'status' => 'available'],
            ['ward' => 'Melati', 'name' => 'Melati B', 'capacity' => 6, 'status' => 'maintenance'],

            // Bangsal Mawar
            ['ward' => 'Mawar', 'name' => 'Mawar A', 'capacity' => 4, 'status' => 'available'],
            ['ward' => 'Mawar', 'name' => 'Mawar B', 'capacity' => 4, 'status' => 'full'],

            // Bangsal Cempaka
            ['ward' => 'Cempaka', 'name' => 'Cempaka A', 'capacity' => 8, 'status' => 'available'],

            // Bangsal Kenanga
            ['ward' => 'Kenanga', 'name' => 'Kenanga A', 'capacity' => 5, 'status' => 'available'],

            // Bangsal Flamboyan
            ['ward' => 'Flamboyan', 'name' => 'Flamboyan A', 'capacity' => 5, 'status' => 'maintenance'],

            // Bangsal Teratai
            ['ward' => 'Teratai', 'name' => 'Teratai A', 'capacity' => 4, 'status' => 'available'],

            // Bangsal Dahlia
            ['ward' => 'Dahlia', 'name' => 'Dahlia A', 'capacity' => 4, 'status' => 'available'],

            // ICU
            ['ward' => 'ICU', 'name' => 'ICU-01', 'capacity' => 2, 'status' => 'available'],

            // NICU
            ['ward' => 'NICU', 'name' => 'NICU-01', 'capacity' => 2, 'status' => 'available'],

            // Isolasi
            ['ward' => 'Isolasi', 'name' => 'ISO-01', 'capacity' => 1, 'status' => 'full'],
        ];

        foreach ($rooms as $item) {

            $ward = Ward::where('name', $item['ward'])->first();

            if (!$ward) {
                continue;
            }

            Room::updateOrCreate(

                [
                    'ward_id' => $ward->id,
                    'name' => $item['name'],
                ],

                [
                    'capacity' => $item['capacity'],
                    'status' => $item['status'],
                ]

            );
        }
    }
}