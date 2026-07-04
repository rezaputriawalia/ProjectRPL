<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole  = Role::where('name', 'admin')->firstOrFail();
        $doctorRole = Role::where('name', 'doctor')->firstOrFail();
        $nurseRole  = Role::where('name', 'nurse')->firstOrFail();

        User::updateOrCreate(
            ['email' => 'admin@sigap.test'],
            [
                'role_id' => $adminRole->id,
                'name' => 'Administrator',
                'phone' => '081111111111',
                'photo' => null,
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['email' => 'doctor@sigap.test'],
            [
                'role_id' => $doctorRole->id,
                'name' => 'Dr. Ahmad',
                'phone' => '082222222222',
                'photo' => null,
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['email' => 'nurse@sigap.test'],
            [
                'role_id' => $nurseRole->id,
                'name' => 'Perawat Siti',
                'phone' => '083333333333',
                'photo' => null,
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        );
    }
}