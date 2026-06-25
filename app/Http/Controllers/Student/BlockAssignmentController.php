<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class BlockAssignmentController extends Controller
{
    public function show(): View
    {
        $user = auth()->user();
        $user->load(['enrollmentForm', 'enrollment.block']);

        return view('block-assignment', [
            'student' => $user,
        ]);
    }
}