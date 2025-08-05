<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo user lãnh đạo
        User::create([
            'name' => 'Lãnh đạo',
            'email' => 'leader@example.com',
            'password' => Hash::make('password'),
            'role' => 'leader',
            'email_verified_at' => now(),
        ]);

        // Tạo các đội trưởng
        $captains = [
            ['name' => 'Đội trưởng A', 'email' => 'captain1@example.com'],
            ['name' => 'Đội trưởng B', 'email' => 'captain2@example.com'],
            ['name' => 'Đội trưởng C', 'email' => 'captain3@example.com'],
        ];

        foreach ($captains as $captain) {
            User::create([
                'name' => $captain['name'],
                'email' => $captain['email'],
                'password' => Hash::make('password'),
                'role' => 'captain',
                'email_verified_at' => now(),
            ]);
        }
    }
}
