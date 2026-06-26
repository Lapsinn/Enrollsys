<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\EnrollmentForm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnrollmentFormController extends Controller
{
    /**
     * Show the enrollment form.
     * If the student already has a saved form (draft or submitted), pre-fill it.
     */
    public function show(): View
    {
        // Load the authenticated student's existing form (or null if none yet)
        $form = auth()->user()->enrollmentForm;

        return view('forms', [
            'form' => $form,
        ]);
    }

    /**
     * Submit (or re-submit) the enrollment form.
     */
    public function store(Request $request): RedirectResponse
    {
        $form = auth()->user()->enrollmentForm;
        if ($form && $form->status === 'approved') {
            return redirect()
                ->route('student.forms.show')
                ->withErrors(['form' => 'Your enrollment form has already been approved and cannot be updated.']);
        }

        $validated = $request->validate([
            'last_name'         => ['required', 'string', 'max:255'],
            'first_name'        => ['required', 'string', 'max:255'],
            'middle_name'       => ['nullable', 'string', 'max:255'],
            'birthdate'         => ['required', 'date'],
            'sex'               => ['required', 'in:male,female'],
            'applicant_type'    => ['required', 'in:new,old,transferee'],
            'program'           => ['required', 'in:bsit,bscs'],
            'year_level'        => ['required', 'in:1,2,3,4'],
            'semester'          => ['required', 'in:1,2'],
            'address'           => ['required', 'string'],
            'email'             => ['required', 'email'],
            'contact_number'    => ['required', 'string'],
            'emergency_contact' => ['required', 'string'],
            'last_school'       => ['required', 'string'],
        ]);

        // Remove 'email' from $validated before saving to enrollment_forms
        // because email lives on the users table, not enrollment_forms.
        $email = $validated['email'];
        unset($validated['email']);

        // Always set status to 'pending' on a fresh submission
        $validated['status'] = 'pending';

        // Create or update the enrollment form tied to this user
        auth()->user()->enrollmentForm()->updateOrCreate(
            ['user_id' => auth()->id()],
            $validated
        );

        // Optionally sync the email back to the user record
        auth()->user()->update(['email' => $email]);

        return redirect()
            ->route('student.forms.show')
            ->with('status', 'Enrollment submitted successfully.');
    }

    /**
     * Save a draft without changing the status to pending.
     */
    public function saveDraft(Request $request): RedirectResponse
    {
        $form = auth()->user()->enrollmentForm;
        if ($form && $form->status === 'approved') {
            return redirect()
                ->route('student.forms.show')
                ->withErrors(['form' => 'Your enrollment form has already been approved and cannot be updated.']);
        }

        $data = $request->except(['_token']);
        $data['status'] = 'draft';

        auth()->user()->enrollmentForm()->updateOrCreate(
            ['user_id' => auth()->id()],
            $data
        );

        return redirect()
            ->route('student.forms.show')
            ->with('status', 'Draft saved.');
    }

    /**
     * Show the student's current enrollment status.
     */
    public function status(): View
    {
        $form = auth()->user()->enrollmentForm;

        return view('enrollment-status', [
            'form' => $form,
        ]);
    }
}
