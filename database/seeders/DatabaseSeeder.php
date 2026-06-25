<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin1@school.edu',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Create 4 Students
        for ($i = 1; $i <= 4; $i++) {
            User::create([
                'name' => "Student $i",
                'email' => "student$i@school.edu",
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_number' => '2026-' . str_pad($i, 5, '0', STR_PAD_LEFT),
            ]);
        }
    }
}
