<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class BlockAssignmentController extends Controller
{
    public function show(): View
    {
        // $block = auth()->user()->blockAssignment;

        return view('block-assignment');
    }
}