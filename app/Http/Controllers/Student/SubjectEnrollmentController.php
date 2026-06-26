<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectEnrollmentController extends Controller
{
    public function show(): View
    {
        $subjects = Subject::all();
        $enrolledSubjectIds = auth()->user()->enrollmentForm?->subjects()->pluck('subjects.id')->toArray() ?? [];
        $readonly = auth()->user()->enrollmentForm?->subjects_status === 'approved';

        return view('subject-enrollment', [
            'subjects' => $subjects,
            'enrolledSubjectIds' => $enrolledSubjectIds,
            'readonly' => $readonly,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $form = auth()->user()->enrollmentForm;
        if (!$form) {
            return redirect()
                ->route('student.forms.show')
                ->with('status', 'Please submit your enrollment form first.');
        }

        if ($form->subjects_status === 'approved') {
            return redirect()
                ->route('student.subjects.show')
                ->withErrors(['subjects' => 'Your subject enrollment has already been approved and cannot be updated.']);
        }

        $validated = $request->validate([
            'subjects' => ['nullable', 'array'],
            'subjects.*' => ['exists:subjects,id'],
        ]);

        $form->subjects()->sync($request->input('subjects', []));

        return redirect()
            ->route('student.subjects.show')
            ->with('status', 'Subjects enrolled successfully.');
    }
}