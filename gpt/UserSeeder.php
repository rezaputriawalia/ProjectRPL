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
        $adminRole = Role::query()->where('name', 'admin')->firstOrFail();
        $dokterRole = Role::query()->where('name', 'dokter')->firstOrFail();
        $perawatRole = Role::query()->where('name', 'perawat')->firstOrFail();

        User::query()->updateOrCreate(
            ['email' => 'admin@sigap.test'],
            [
                'role_id' => $adminRole->id,
                'name' => 'Admin SIGAP',
                'password' => Hash::make('password'),
                'phone' => '081234560001',
                'gender' => 'P',
                'profession_number' => 'ADM-00001',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        User::factory()->count(20)->dokter($dokterRole->id)->create();
        User::factory()->count(50)->perawat($perawatRole->id)->create();
    }
}
