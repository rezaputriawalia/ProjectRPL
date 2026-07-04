<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Registration;
use App\Models\Patient;
use App\Models\User;
use App\Models\Room;

class RegistrationSeeder extends Seeder
{
    public function run(): void
    {
        $doctor = User::whereHas('role', function ($q) {
            $q->where('name', 'doctor');
        })->first();

        $nurse = User::whereHas('role', function ($q) {
            $q->where('name', 'nurse');
        })->first();

        $rooms = Room::all();

        foreach (Patient::all() as $index => $patient) {

            Registration::updateOrCreate(

                [
                    'patient_id' => $patient->id,
                    'status' => 'active',
                ],

                [
                    'doctor_id' => $doctor->id,

                    'nurse_id' => $nurse->id,

                    'room_id' => $rooms[$index % $rooms->count()]->id,

                    'admission_date' => now(),

                    'notes' => 'Registrasi awal',
                ]

            );

        }
    }
}