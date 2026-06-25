<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EnrollmentForm;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnrollmentFormController extends Controller
{
    /**
     * List all students with a search filter.
     * Eager-loads enrollmentForm so we can show status in the table.
     */
    public function index(Request $request): View
    {
        $query = $request->input('q');

        $students = User::query()
            ->where('role', 'student')
            ->with('enrollmentForm')          // eager-load to avoid N+1
            ->when($query, function ($builder) use ($query) {
                $builder->where(function ($inner) use ($query) {
                    $inner->where('name', 'like', "%{$query}%")
                          ->orWhere('student_number', 'like', "%{$query}%");
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('forms', [
            'students' => $students,
            'query'    => $query,
        ]);
    }

    /**
     * Show one student's enrollment form detail.
     */
    public function show(User $student): View
    {
        // Load the related enrollment form; will be null if not yet submitted
        $student->load('enrollmentForm');

        return view('forms-detail', [
            'student' => $student,
            'form'    => $student->enrollmentForm,
        ]);
    }

    /**
     * Admin edits / corrects a student's enrollment form.
     */
    public function update(Request $request, User $student): RedirectResponse
    {
        $validated = $request->validate([
            'first_name'        => ['required', 'string', 'max:255'],
            'last_name'         => ['required', 'string', 'max:255'],
            'middle_name'       => ['nullable', 'string', 'max:255'],
            'birthdate'         => ['required', 'date'],
            'sex'               => ['required', 'in:male,female'],
            'applicant_type'    => ['required', 'in:new,old,transferee'],
            'program'           => ['required', 'string'],
            'year_level'        => ['required', 'integer', 'between:1,4'],
            'semester'          => ['required', 'in:1,2'],
            'address'           => ['required', 'string'],
            'email'             => ['required', 'email'],
            'contact_number'    => ['required', 'string'],
            'emergency_contact' => ['required', 'string'],
            'last_school'       => ['required', 'string'],
        ]);

        // Email is on the users table, not enrollment_forms
        $email = $validated['email'];
        unset($validated['email']);

        // Update the enrollment form record
        $student->enrollmentForm()->update($validated);

        // Keep user email in sync if changed
        $student->update(['email' => $email]);

        return redirect()
            ->route('admin.forms.show', $student)
            ->with('status', 'Enrollment form updated successfully.');
    }
}
