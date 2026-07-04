<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VisitSchedule>
 */
class VisitScheduleFactory extends Factory
{
    public function definition(): array
    {
        $faker = fake('id_ID');
        $start = $faker->dateTimeBetween('08:00', '16:00')->format('H:i:s');

        return [
            'patient_id' => Patient::query()->inRandomOrder()->value('id') ?? Patient::factory(),
            'registration_id' => Registration::query()->inRandomOrder()->value('id') ?? Registration::factory(),
            'doctor_id' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'created_by' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'title' => $faker->randomElement(['Visite pagi', 'Evaluasi terapi', 'Kontrol pasca tindakan', 'Follow up kondisi umum']),
            'visit_date' => $faker->dateTimeBetween('-7 days', '+14 days')->format('Y-m-d'),
            'start_time' => $start,
            'end_time' => date('H:i:s', strtotime($start . ' +30 minutes')),
            'status' => $faker->randomElement(['scheduled', 'scheduled', 'completed', 'cancelled', 'missed']),
            'notes' => $faker->optional(0.65)->sentence(),
        ];
    }
}
