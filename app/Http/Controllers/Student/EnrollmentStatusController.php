<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class EnrollmentStatusController extends Controller
{
    public function show(): View
    {
        // $status = auth()->user()->application;
        // $notifications = auth()->user()->notifications;

        return view('approval');
    }
}