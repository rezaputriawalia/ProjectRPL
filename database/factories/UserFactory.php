<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password = null;

    public function definition(): array
    {
        $faker = fake('id_ID');

        return [
            'role_id' => Role::query()->inRandomOrder()->value('id'),
            'name' => $faker->name(),
            'email' => $faker->unique()->safeEmail(),
            'phone' => $faker->phoneNumber(),
            'photo' => null,
            'password' => static::$password ??= Hash::make('password'),
            'status' => 'active',
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(int $roleId): static
    {
        return $this->state(fn () => [
            'role_id' => $roleId,
        ]);
    }

    public function doctor(int $roleId): static
    {
        return $this->state(fn () => [
            'role_id' => $roleId,
        ]);
    }

    public function nurse(int $roleId): static
    {
        return $this->state(fn () => [
            'role_id' => $roleId,
        ]);
    }
}