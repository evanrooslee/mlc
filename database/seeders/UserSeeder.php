<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create superadmin user
        User::create([
            'name' => 'Super Admin',
            'role' => 'admin',
            'email' => 'superadmin@example.com',
            'phone_number' => fake()->phoneNumber(),
            'parents_phone_number' => fake()->phoneNumber(),
            'school' => 'Admin School',
            'grade' => 12,
            'password' => Hash::make('password'),
        ]);

        // Create additional dummy users
        for ($i = 1; $i <= 20; $i++) {
            User::factory()->create([
                'role' => 'student',
                'name' => fake()->name() . ' (DUMMY)',
            ]);
        }
    }
}
