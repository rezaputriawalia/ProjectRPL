<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\User;
use Database\Seeders\WardData;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Registration>
 */
class RegistrationFactory extends Factory
{
    public function definition(): array
    {
        $faker = fake('id_ID');
        $status = $faker->randomElement(['active', 'active', 'active', 'discharged']);
        $registrationDate = $faker->dateTimeBetween('-60 days', 'now');

        return [
            'registration_number' => 'REG-' . now()->format('ymd') . '-' . $faker->unique()->numerify('#####'),
            'patient_id' => Patient::query()->inRandomOrder()->value('id') ?? Patient::factory(),
            'registered_by' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'registration_date' => $registrationDate,
            'care_type' => $faker->randomElement(['rawat_jalan', 'rawat_inap', 'igd']),
            'ward' => $faker->randomElement(WardData::names()),
            'room' => $faker->bothify('R-##'),
            'bed' => (string) $faker->numberBetween(1, 6),
            'main_complaint' => $faker->randomElement([
                'Demam dan nyeri kepala sejak dua hari',
                'Sesak napas memberat saat aktivitas',
                'Nyeri perut kanan bawah',
                'Kontrol tekanan darah tidak stabil',
                'Mual, muntah, dan lemas',
                'Luka pasca operasi membutuhkan observasi',
            ]),
            'status' => $status,
            'discharged_at' => $status === 'discharged' ? $faker->dateTimeBetween($registrationDate, 'now') : null,
        ];
    }
}
