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

        // Create Blocks
        $blocks = [
            ['name' => 'BSCS - Block A', 'capacity' => 40],
            ['name' => 'BSCS - Block B', 'capacity' => 40],
            ['name' => 'BSIT - Block A', 'capacity' => 40],
            ['name' => 'BSIT - Block B', 'capacity' => 40],
        ];
        foreach ($blocks as $block) {
            \App\Models\Block::create($block);
        }

        // Create Subjects
        $subjects = [
            ['code' => 'CMPE 202', 'name' => 'Operating Systems', 'units' => 3],
            ['code' => 'CMPE 203', 'name' => 'Numerical Methods', 'units' => 4],
            ['code' => 'IT 101', 'name' => 'Introduction to IT', 'units' => 3],
            ['code' => 'CS 101', 'name' => 'Introduction to Computer Science', 'units' => 3],
        ];
        foreach ($subjects as $subject) {
            \App\Models\Subject::create($subject);
        }
    }
}
