<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\EnrollmentForm;
use App\Models\Enrollment;
use App\Models\Block;
use App\Models\Subject;
use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EnrollmentPortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    public function test_a_student_can_upload_their_academic_record_only_after_starting_an_enrollment_form()
    {
        $student = User::create([
            'name' => 'Juan dela Cruz',
            'email' => 'juan@school.edu',
            'password' => bcrypt('password123'),
            'role' => 'student',
            'student_number' => '2026-99999',
        ]);

        // 1. Attempt upload without an enrollment form started
        $this->actingAs($student);
        $file = UploadedFile::fake()->create('my_record.pdf', 100);

        $response = $this->post(route('student.records.upload'), [
            'record_file' => $file,
        ]);

        $response->assertSessionHasErrors(['form']);

        // 2. Start/save an enrollment form draft
        $form = EnrollmentForm::create([
            'user_id' => $student->id,
            'first_name' => 'Juan',
            'last_name' => 'dela Cruz',
            'birthdate' => '2007-01-01',
            'sex' => 'male',
            'applicant_type' => 'new',
            'program' => 'bscs',
            'year_level' => 1,
            'semester' => '1',
            'address' => 'Manila',
            'contact_number' => '09170000000',
            'emergency_contact' => 'Maria-09171111111',
            'last_school' => 'Manila High',
            'status' => 'draft',
            'subjects_status' => 'pending',
        ]);

        // Refresh model relationship and re-authenticate to clear the cached user object inside the Auth manager
        $student->refresh();
        $this->actingAs($student);

        // 3. Attempt upload now that the form exists
        $response2 = $this->post(route('student.records.upload'), [
            'record_file' => $file,
        ]);

        $response2->assertRedirect();
        $response2->assertSessionHasNoErrors();

        // 4. Verify file is stored securely with encrypted/hashed name
        $form->refresh();
        $this->assertNotNull($form->record_file);
        $this->assertStringNotContainsString('juan', $form->record_file); // Filename should be a secure random hash
        Storage::disk('local')->assertExists($form->record_file);
    }

    public function test_unauthorized_users_cannot_download_student_records()
    {
        $studentA = User::create([
            'name' => 'Althea Santos',
            'email' => 'althea@school.edu',
            'password' => bcrypt('password123'),
            'role' => 'student',
            'student_number' => '2026-00010',
        ]);

        $studentB = User::create([
            'name' => 'Joshua Cruz',
            'email' => 'joshua@school.edu',
            'password' => bcrypt('password123'),
            'role' => 'student',
            'student_number' => '2026-00020',
        ]);

        $formA = EnrollmentForm::create([
            'user_id' => $studentA->id,
            'first_name' => 'Althea',
            'last_name' => 'Santos',
            'birthdate' => '2007-01-01',
            'sex' => 'female',
            'applicant_type' => 'new',
            'program' => 'bscs',
            'year_level' => 1,
            'semester' => '1',
            'address' => 'Manila',
            'contact_number' => '09170000000',
            'emergency_contact' => 'Maria-09171111111',
            'last_school' => 'Manila High',
            'status' => 'approved',
            'subjects_status' => 'pending',
        ]);

        $file = UploadedFile::fake()->create('transcript.pdf', 200);
        $path = Storage::disk('local')->putFile('records', $file);
        $formA->update(['record_file' => $path]);

        // Student B tries to download Student A's records
        $this->actingAs($studentB);
        $response = $this->get(route('records.download', $studentA));
        $response->assertStatus(403);
    }

    public function test_record_downloads_attach_a_custom_identifying_name()
    {
        $student = User::create([
            'name' => 'Althea Santos',
            'email' => 'althea@school.edu',
            'password' => bcrypt('password123'),
            'role' => 'student',
            'student_number' => '2026-00010',
        ]);

        $form = EnrollmentForm::create([
            'user_id' => $student->id,
            'first_name' => 'Althea',
            'last_name' => 'Santos',
            'birthdate' => '2007-01-01',
            'sex' => 'female',
            'applicant_type' => 'new',
            'program' => 'bscs',
            'year_level' => 1,
            'semester' => '1',
            'address' => 'Manila',
            'contact_number' => '09170000000',
            'emergency_contact' => 'Maria-09171111111',
            'last_school' => 'Manila High',
            'status' => 'approved',
            'subjects_status' => 'pending',
        ]);

        $file = UploadedFile::fake()->create('transcript.pdf', 200);
        $path = Storage::disk('local')->putFile('records', $file);
        $form->update(['record_file' => $path]);

        // Student A downloads their own file
        $this->actingAs($student);
        $response = $this->get(route('records.download', $student));
        $response->assertStatus(200);

        // Verify the response headers contain the custom filename with portal name and student details
        $portalName = config('app.name', 'EnrollSys');
        $response->assertHeader('Content-Disposition', 'attachment; filename=' . $portalName . '_Althea_Santos_2026-00010_Academic_Record.pdf');
    }

    public function test_block_assignments_must_match_the_student_year_level()
    {
        $admin = User::create([
            'name' => 'Registrar Admin',
            'email' => 'registrar@school.edu',
            'role' => 'admin',
            'password' => bcrypt('password123'),
        ]);

        $student = User::create([
            'name' => 'Joshua Cruz',
            'email' => 'joshua@school.edu',
            'role' => 'student',
            'password' => bcrypt('password123'),
            'student_number' => '2026-00020',
        ]);

        EnrollmentForm::create([
            'user_id' => $student->id,
            'first_name' => 'Joshua',
            'last_name' => 'Cruz',
            'birthdate' => '2006-01-01',
            'sex' => 'male',
            'applicant_type' => 'old',
            'program' => 'bsit',
            'year_level' => 2, // 2nd Year
            'semester' => '1',
            'address' => 'Quezon City',
            'contact_number' => '09180000000',
            'emergency_contact' => 'Maria-09171111111',
            'last_school' => 'CCIS College',
            'status' => 'approved',
            'subjects_status' => 'pending',
        ]);

        $enrollment = Enrollment::create([
            'student_name' => $student->name,
            'email' => $student->email,
            'course' => 'bsit',
        ]);

        // Create Year 1 block and Year 2 block
        $blockY1 = Block::create(['name' => '1-1', 'capacity' => 40]);
        $blockY2 = Block::create(['name' => '2-2', 'capacity' => 40]);

        $this->actingAs($admin);

        // 1. Attempt assigning block 1-1 (Year 1) to Joshua (Year 2) -> Should fail custom validation
        $response = $this->patch(route('admin.block-assignment.update', $student), [
            'block_id' => $blockY1->id,
        ]);
        $response->assertSessionHasErrors(['block_id']);

        // 2. Attempt assigning block 2-2 (Year 2) to Joshua (Year 2) -> Should succeed
        $response2 = $this->patch(route('admin.block-assignment.update', $student), [
            'block_id' => $blockY2->id,
        ]);
        $response2->assertRedirect();
        $response2->assertSessionHasNoErrors();

        $enrollment->refresh();
        $this->assertEquals($blockY2->id, $enrollment->block_id);
    }

    public function test_approved_enrollment_forms_are_locked_and_cannot_be_modified_by_students()
    {
        $student = User::create([
            'name' => 'Althea Santos',
            'email' => 'althea@school.edu',
            'role' => 'student',
            'password' => bcrypt('password123'),
            'student_number' => '2026-00010',
        ]);

        $form = EnrollmentForm::create([
            'user_id' => $student->id,
            'first_name' => 'Althea',
            'last_name' => 'Santos',
            'birthdate' => '2007-01-01',
            'sex' => 'female',
            'applicant_type' => 'new',
            'program' => 'bscs',
            'year_level' => 1,
            'semester' => '1',
            'address' => 'Manila',
            'contact_number' => '09170000000',
            'emergency_contact' => 'Maria-09171111111',
            'last_school' => 'Manila High',
            'status' => 'approved', // Approved & Locked
            'subjects_status' => 'pending',
        ]);

        $this->actingAs($student);

        // Attempting to post/update the form
        $response = $this->post(route('student.forms.store'), [
            'first_name' => 'Althea Modified',
            'last_name' => 'Santos',
            'birthdate' => '2007-01-01',
            'sex' => 'female',
            'applicant_type' => 'new',
            'program' => 'bscs',
            'year_level' => 1,
            'semester' => '1',
            'address' => 'Manila Updated Address',
            'contact_number' => '09170000000',
            'emergency_contact' => 'Maria-09171111111',
            'last_school' => 'Manila High',
            'email' => 'althea@school.edu',
        ]);

        // Form update returns redirect to forms page with errors
        $response->assertRedirect(route('student.forms.show'));
        $response->assertSessionHasErrors(['form']);
        
        $form->refresh();
        $this->assertEquals('Manila', $form->address); // Address remained locked/unchanged
    }

    public function test_approved_subject_enrollments_are_locked_and_cannot_be_modified_by_students()
    {
        $student = User::create([
            'name' => 'Althea Santos',
            'email' => 'althea@school.edu',
            'role' => 'student',
            'password' => bcrypt('password123'),
            'student_number' => '2026-00010',
        ]);

        $form = EnrollmentForm::create([
            'user_id' => $student->id,
            'first_name' => 'Althea',
            'last_name' => 'Santos',
            'birthdate' => '2007-01-01',
            'sex' => 'female',
            'applicant_type' => 'new',
            'program' => 'bscs',
            'year_level' => 1,
            'semester' => '1',
            'address' => 'Manila',
            'contact_number' => '09170000000',
            'emergency_contact' => 'Maria-09171111111',
            'last_school' => 'Manila High',
            'status' => 'approved',
            'subjects_status' => 'approved', // Subjects Locked
        ]);

        $sub = Subject::create([
            'code' => 'COMP 101',
            'name' => 'Intro to Comp',
            'units' => 3,
        ]);

        $this->actingAs($student);

        // Attempting to change/sync enrolled subjects
        $response = $this->post(route('student.subjects.store'), [
            'subjects' => [$sub->id],
        ]);

        // Subject update returns redirect with errors
        $response->assertRedirect(route('student.subjects.show'));
        $response->assertSessionHasErrors(['subjects']);
    }

    public function test_admin_can_add_office_notes_to_a_student()
    {
        $admin = User::create([
            'name' => 'Registrar Admin',
            'email' => 'registrar@school.edu',
            'role' => 'admin',
            'password' => bcrypt('password123'),
        ]);

        $student = User::create([
            'name' => 'Joshua Cruz',
            'email' => 'joshua@school.edu',
            'role' => 'student',
            'password' => bcrypt('password123'),
            'student_number' => '2026-00020',
        ]);

        $this->actingAs($admin);

        $response = $this->post(route('admin.approval.note'), [
            'student_id' => $student->id,
            'note' => 'Please bring a physical copy of your birth certificate.',
        ]);

        $response->assertRedirect(route('admin.approval.index'));
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('notes', [
            'user_id' => $student->id,
            'author_id' => $admin->id,
            'body' => 'Please bring a physical copy of your birth certificate.',
        ]);
    }
}
