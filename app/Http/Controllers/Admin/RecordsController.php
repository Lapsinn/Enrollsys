<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecordsController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->input('q');
        $semester = $request->input('semester');
        $academicYear = $request->input('ay');

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

        return view('records', [
            'students' => $students,
            'query' => $query,
            'semester' => $semester,
            'academicYear' => $academicYear,
        ]);
    }
}