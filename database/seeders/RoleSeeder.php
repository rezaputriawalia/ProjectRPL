<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'doctor',
                'display_name' => 'Dokter',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'nurse',
                'display_name' => 'Perawat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}