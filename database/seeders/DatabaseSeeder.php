<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\EnrollmentForm;
use App\Models\Enrollment;
use App\Models\Block;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Blocks
        $blocks = [];
        for ($year = 1; $year <= 4; $year++) {
            for ($section = 1; $section <= 5; $section++) {
                $blocks[] = ['name' => "{$year}-{$section}", 'capacity' => 40];
            }
            $blocks[] = ['name' => "{$year}-1N", 'capacity' => 40];
        }

        foreach ($blocks as $block) {
            Block::firstOrCreate(
                ['name' => $block['name']],
                ['capacity' => $block['capacity']]
            );
        }

        // 2. Create Subjects from courses.md
        $filePath = base_path('courses.md');
        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            $lines = explode("\n", $content);
            foreach ($lines as $line) {
                $line = trim($line);
                if (str_starts_with($line, '|') && str_ends_with($line, '|')) {
                    $parts = explode('|', $line);
                    if (count($parts) >= 4) {
                        $code = trim($parts[1]);
                        $title = trim($parts[2]);
                        $unitsStr = trim($parts[3]);

                        // Skip headers and total rows
                        if (
                            strtolower($code) === 'course code' ||
                            str_contains($code, '---') ||
                            strtolower($code) === 'total' ||
                            strtolower($code) === 'grand total' ||
                            empty($code)
                        ) {
                            continue;
                        }

                        // Remove markdown formatting and parentheses
                        $code = trim(str_replace('**', '', $code));
                        $title = trim(str_replace('**', '', $title));
                        $unitsStr = str_replace(['(', ')', '**'], '', $unitsStr);
                        $units = (int)trim($unitsStr);

                        if ($code && $title) {
                            Subject::firstOrCreate(
                                ['code' => $code],
                                ['name' => $title, 'units' => $units]
                            );
                        }
                    }
                }
            }
        }

        // 3. Create Registrar Admin
        User::updateOrCreate(
            ['email' => 'registrar@school.edu'],
            [
                'name' => 'Maria Teresa Reyes',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // Fetch some blocks for students
        $block1_1 = Block::where('name', '1-1')->first();
        $block2_2 = Block::where('name', '2-2')->first();

        // Fetch some subjects for students
        $subCS1 = Subject::where('code', 'GEED 10053')->first();
        $subCS2 = Subject::where('code', 'COMP 20013')->first();
        $subCS3 = Subject::where('code', 'COMP 20023')->first();

        $subIT1 = Subject::where('code', 'COMP 007')->first();
        $subIT2 = Subject::where('code', 'COMP 008')->first();
        $subIT3 = Subject::where('code', 'INTE 201')->first();

        // --- Student 1: Fully Completed & Approved (Althea Santos) ---
        $althea = User::updateOrCreate(
            ['email' => 'althea@school.edu'],
            [
                'name' => 'Althea Santos',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_number' => '2026-00010',
            ]
        );

        $altheaForm = EnrollmentForm::updateOrCreate(
            ['user_id' => $althea->id],
            [
                'first_name' => 'Althea',
                'last_name' => 'Santos',
                'birthdate' => '2007-05-15',
                'sex' => 'female',
                'applicant_type' => 'new',
                'program' => 'bscs',
                'year_level' => 1,
                'semester' => '1',
                'address' => '123 Mabini St, Manila',
                'contact_number' => '09171234567',
                'emergency_contact' => 'Juan Santos - 09177654321',
                'last_school' => 'Manila High School',
                'status' => 'approved',
                'subjects_status' => 'approved',
            ]
        );

        $altheaPath = 'records/' . sha1('althea_record') . '.pdf';
        Storage::put($altheaPath, 'Academic Transcript for Althea Santos - Approved Year 1.');
        $altheaForm->update(['record_file' => $altheaPath]);

        Enrollment::updateOrCreate(
            ['email' => $althea->email],
            [
                'student_name' => $althea->name,
                'course' => 'bscs',
                'block_id' => $block1_1 ? $block1_1->id : null,
            ]
        );

        if ($subCS1 && $subCS2 && $subCS3) {
            $altheaForm->subjects()->sync([$subCS1->id, $subCS2->id, $subCS3->id]);
        }

        // --- Student 2: Subjects Pending Approval (Joshua Dela Cruz) ---
        $joshua = User::updateOrCreate(
            ['email' => 'joshua@school.edu'],
            [
                'name' => 'Joshua Dela Cruz',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_number' => '2026-00020',
            ]
        );

        $joshuaForm = EnrollmentForm::updateOrCreate(
            ['user_id' => $joshua->id],
            [
                'first_name' => 'Joshua',
                'last_name' => 'Dela Cruz',
                'birthdate' => '2006-08-20',
                'sex' => 'male',
                'applicant_type' => 'old',
                'program' => 'bsit',
                'year_level' => 2,
                'semester' => '1',
                'address' => '456 Rizal Ave, Quezon City',
                'contact_number' => '09181234567',
                'emergency_contact' => 'Maria Dela Cruz - 09187654321',
                'last_school' => 'CCIS College',
                'status' => 'approved',
                'subjects_status' => 'pending',
            ]
        );

        $joshuaPath = 'records/' . sha1('joshua_record') . '.pdf';
        Storage::put($joshuaPath, 'Academic Transcript for Joshua Dela Cruz - Year 2.');
        $joshuaForm->update(['record_file' => $joshuaPath]);

        Enrollment::updateOrCreate(
            ['email' => $joshua->email],
            [
                'student_name' => $joshua->name,
                'course' => 'bsit',
                'block_id' => $block2_2 ? $block2_2->id : null,
            ]
        );

        if ($subIT1 && $subIT2 && $subIT3) {
            $joshuaForm->subjects()->sync([$subIT1->id, $subIT2->id, $subIT3->id]);
        }

        // --- Student 3: Form Approved, No Subjects Enrolled (Carlo Aquino) ---
        $carlo = User::updateOrCreate(
            ['email' => 'carlo@school.edu'],
            [
                'name' => 'Carlo Aquino',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_number' => '2026-00030',
            ]
        );

        $carloForm = EnrollmentForm::updateOrCreate(
            ['user_id' => $carlo->id],
            [
                'first_name' => 'Carlo',
                'last_name' => 'Aquino',
                'birthdate' => '2005-11-30',
                'sex' => 'male',
                'applicant_type' => 'old',
                'program' => 'bscs',
                'year_level' => 3,
                'semester' => '1',
                'address' => '789 Bonifacio St, Makati',
                'contact_number' => '09191234567',
                'emergency_contact' => 'Teresa Aquino - 09197654321',
                'last_school' => 'CCIS College',
                'status' => 'approved',
                'subjects_status' => 'pending',
            ]
        );

        $carloPath = 'records/' . sha1('carlo_record') . '.pdf';
        Storage::put($carloPath, 'Academic Transcript for Carlo Aquino - Year 3.');
        $carloForm->update(['record_file' => $carloPath]);

        Enrollment::updateOrCreate(
            ['email' => $carlo->email],
            [
                'student_name' => $carlo->name,
                'course' => 'bscs',
                'block_id' => null,
            ]
        );

        // --- Student 4: Form Submitted & Pending Approval (Dianne Rivera) ---
        $dianne = User::updateOrCreate(
            ['email' => 'dianne@school.edu'],
            [
                'name' => 'Dianne Rivera',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_number' => '2026-00040',
            ]
        );

        $dianneForm = EnrollmentForm::updateOrCreate(
            ['user_id' => $dianne->id],
            [
                'first_name' => 'Dianne',
                'last_name' => 'Rivera',
                'birthdate' => '2007-02-14',
                'sex' => 'female',
                'applicant_type' => 'new',
                'program' => 'bsit',
                'year_level' => 1,
                'semester' => '1',
                'address' => '101 Shaw Blvd, Pasig',
                'contact_number' => '09201234567',
                'emergency_contact' => 'Helen Rivera - 09207654321',
                'last_school' => 'Manila High School',
                'status' => 'pending',
                'subjects_status' => 'pending',
            ]
        );

        // --- Student 5: Draft Form / Initial Stage (Ethan Roxas) ---
        $ethan = User::updateOrCreate(
            ['email' => 'ethan@school.edu'],
            [
                'name' => 'Ethan Roxas',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_number' => '2026-00050',
            ]
        );

        $ethanForm = EnrollmentForm::updateOrCreate(
            ['user_id' => $ethan->id],
            [
                'first_name' => 'Ethan',
                'last_name' => 'Roxas',
                'birthdate' => '2004-09-08',
                'sex' => 'male',
                'applicant_type' => 'old',
                'program' => 'bscs',
                'year_level' => 4,
                'semester' => '1',
                'address' => '202 McKinley Hill, Taguig',
                'contact_number' => '09211234567',
                'emergency_contact' => 'Manuel Roxas - 09217654321',
                'last_school' => 'CCIS College',
                'status' => 'draft',
                'subjects_status' => 'pending',
            ]
        );

        // --- Student 6: Blank User (Aldwin Paul C. Lopez) ---
        User::updateOrCreate(
            ['email' => 'aldwin@school.edu'],
            [
                'name' => 'Aldwin Paul C. Lopez',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_number' => '2023-01754',
            ]
        );
    }
}
