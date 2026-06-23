<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class RecordsController extends Controller
{
    public function show(): View
    {
        // $grades = auth()->user()->grades;

        return view('records');
    }
}