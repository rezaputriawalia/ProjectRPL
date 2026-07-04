<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalRecord>
 */
class MedicalRecordFactory extends Factory
{
    public function definition(): array
    {
        $faker = fake('id_ID');

        return [
            'patient_id' => Patient::query()->inRandomOrder()->value('id') ?? Patient::factory(),
            'registration_id' => Registration::query()->inRandomOrder()->value('id') ?? Registration::factory(),
            'doctor_id' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'diagnosis_primary' => $faker->randomElement([
                'Hipertensi esensial',
                'Diabetes melitus tipe 2',
                'Bronkopneumonia',
                'Gastritis akut',
                'Demam tifoid',
                'Infeksi saluran kemih',
            ]),
            'diagnosis_secondary' => $faker->optional(0.4)->randomElement([
                'Anemia ringan',
                'Dehidrasi ringan',
                'Dislipidemia',
                'Obesitas derajat I',
            ]),
            'anamnesis' => $faker->paragraph(3),
            'physical_exam' => 'Keadaan umum cukup, kesadaran compos mentis, tanda vital dalam pemantauan.',
            'treatment_plan' => $faker->randomElement([
                'Terapi medikamentosa, observasi tanda vital, evaluasi laboratorium.',
                'Pemberian cairan, antiemetik, edukasi diet, evaluasi keluhan.',
                'Antibiotik sesuai indikasi, fisioterapi napas, monitoring saturasi.',
                'Kontrol tekanan darah, diet rendah garam, evaluasi harian.',
            ]),
            'notes' => $faker->optional(0.6)->sentence(),
            'status' => $faker->randomElement(['open', 'open', 'closed']),
        ];
    }
}
