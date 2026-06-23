<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnrollmentFormController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->input('q');

        $students = User::query()
            ->where('role', 'student')
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
            'query' => $query,
        ]);
    }

    public function show(User $student): View
    {
        // Replace with your actual EnrollmentForm model relationship,
        // e.g. $student->enrollmentForm
        return view('forms-detail', [
            'student' => $student,
        ]);
    }

    public function update(Request $request, User $student): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'birthdate' => ['required', 'date'],
            'sex' => ['required', 'in:male,female'],
            'applicant_type' => ['required', 'in:new,old,transferee'],
            'program' => ['required', 'string'],
            'year_level' => ['required', 'integer', 'between:1,4'],
            'semester' => ['required', 'in:1,2'],
            'address' => ['required', 'string'],
            'email' => ['required', 'email'],
            'contact_number' => ['required', 'string'],
            'emergency_contact' => ['required', 'string'],
            'last_school' => ['required', 'string'],
        ]);

        // Persist to your EnrollmentForm model here, e.g.:
        // $student->enrollmentForm()->update($validated);

        return redirect()
            ->route('admin.forms.show', $student)
            ->with('status', 'Enrollment form updated successfully.');
    }
}