<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnrollmentFormController extends Controller
{
    public function show(): View
    {
        return view('forms');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'birthdate' => ['required', 'date'],
            'sex' => ['required', 'in:male,female'],
            'applicant_type' => ['required', 'in:new,old,transferee'],
            'program' => ['required', 'in:bsit,bscs'],
            'year_level' => ['required', 'in:1,2,3,4'],
            'semester' => ['required', 'in:1,2'],
            'address' => ['required', 'string'],
            'email' => ['required', 'email'],
            'contact_number' => ['required', 'string'],
            'emergency_contact' => ['required', 'string'],
            'last_school' => ['required', 'string'],
            'documents' => ['nullable', 'array'],
            'documents.*' => ['file', 'mimes:pdf,jpg,png', 'max:5120'],
        ]);

        // auth()->user()->enrollmentForm()->updateOrCreate([], $validated);
        // Handle file uploads separately, e.g. Storage::putFile('documents', $file)

        return redirect()
            ->route('student.forms.show')
            ->with('status', 'Enrollment submitted successfully.');
    }

    public function saveDraft(Request $request): RedirectResponse
    {
        // auth()->user()->enrollmentForm()->updateOrCreate([], $request->all() + ['is_draft' => true]);

        return redirect()
            ->route('student.forms.show')
            ->with('status', 'Draft saved.');
    }
}