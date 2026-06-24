<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
    // Create Admins
    \App\Models\User::create([
        'name' => 'Admin User',
        'email' => 'admin1@school.edu',
        'password' => Hash::make('password123'),
        'role' => 'admin',
    ]);

    // Create 4 Students
    for ($i = 1; $i <= 4; $i++) {
        \App\Models\User::create([
            'name' => "Student $i",
            'email' => "student$i@school.edu",
            'password' => Hash::make('password123'),
            'role' => 'student',
        ]);
    }
    }
    
}
