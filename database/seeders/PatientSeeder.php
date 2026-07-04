<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\Room;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $patients = [

            [
                'name' => 'Budi Santoso',
                'nik' => '6471010101010001',
                'gender' => 'L',
                'birth_date' => '1994-05-20',
                'address' => 'Tarakan Barat',
                'phone' => '081234567801',
                'status' => 'rawat_inap',
            ],

            [
                'name' => 'Siti Aisyah',
                'nik' => '6471010101010002',
                'gender' => 'P',
                'birth_date' => '1998-10-15',
                'address' => 'Tarakan Tengah',
                'phone' => '081234567802',
                'status' => 'rawat_inap',
            ],

            [
                'name' => 'Ahmad Rizki',
                'nik' => '6471010101010003',
                'gender' => 'L',
                'birth_date' => '1988-07-12',
                'address' => 'Tarakan Timur',
                'phone' => '081234567803',
                'status' => 'rawat_jalan',
            ],

            [
                'name' => 'Dewi Lestari',
                'nik' => '6471010101010004',
                'gender' => 'P',
                'birth_date' => '2001-01-30',
                'address' => 'Tarakan Utara',
                'phone' => '081234567804',
                'status' => 'rawat_inap',
            ],

            [
                'name' => 'Rudi Hartono',
                'nik' => '6471010101010005',
                'gender' => 'L',
                'birth_date' => '1992-09-11',
                'address' => 'Tarakan Barat',
                'phone' => '081234567805',
                'status' => 'rawat_inap',
            ],

        ];

        foreach ($patients as $index => $patient) {

            Patient::updateOrCreate(

                [
                    'medical_record_number' => 'RM-' . str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                ],

                [
                    'name'       => $patient['name'],
                    'nik'        => $patient['nik'],
                    'gender'     => $patient['gender'],
                    'birth_date' => $patient['birth_date'],
                    'address'    => $patient['address'],
                    'phone'      => $patient['phone'],
                ]

            );
        }
    }
}
