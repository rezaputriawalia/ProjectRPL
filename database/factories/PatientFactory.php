<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    public function definition(): array
    {
        $faker = fake('id_ID');

        return [

            'medical_record_number' =>
                'RM-' .
                now()->format('ym') .
                '-' .
                $faker->unique()->numerify('######'),

            'name' => $faker->name(),

            'gender' => $faker->randomElement([
                'L',
                'P'
            ]),

            'birth_date' => $faker
                ->dateTimeBetween('-80 years', '-1 year')
                ->format('Y-m-d'),

            'address' => $faker->address(),

            'phone' => $faker->phoneNumber(),

        ];
    }
}