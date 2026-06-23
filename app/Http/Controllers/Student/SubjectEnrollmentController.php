<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectEnrollmentController extends Controller
{
    public function show(): View
    {
        // $availableSubjects = Subject::available()->get();
        // $enrolledSubjects = auth()->user()->subjects;

        return view('subject-enrollment');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subjects' => ['required', 'array'],
            'subjects.*.subject_id' => ['required', 'exists:subjects,id'],
            'subjects.*.section_id' => ['required', 'exists:sections,id'],
        ]);

        // auth()->user()->subjects()->sync(...);

        return redirect()
            ->route('student.subjects.show')
            ->with('status', 'Subjects enrolled successfully.');
    }
}