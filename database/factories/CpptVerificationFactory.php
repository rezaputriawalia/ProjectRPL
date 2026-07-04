<?php

namespace Database\Factories;

use App\Models\CpptEntry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CpptVerification>
 */
class CpptVerificationFactory extends Factory
{
    public function definition(): array
    {
        $faker = fake('id_ID');

        return [
            'cppt_entry_id' => CpptEntry::query()->inRandomOrder()->value('id') ?? CpptEntry::factory(),
            'verified_by' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'verification_status' => $faker->randomElement(['verified', 'rejected']),
            'verification_note' => $faker->optional(0.7)->sentence(),
            'verified_at' => $faker->dateTimeBetween('-20 days', 'now'),
        ];
    }
}
