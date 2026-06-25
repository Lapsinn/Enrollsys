<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectEnrollmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->input('q');
        $program = $request->input('program');
        $blockId = $request->input('block_id');
        $yearLevel = $request->input('ay');

        $students = User::query()
            ->where('role', 'student')
            ->when($query, function ($builder) use ($query) {
                $builder->where(function ($inner) use ($query) {
                    $inner->where('name', 'like', "%{$query}%")
                        ->orWhere('student_number', 'like', "%{$query}%");
                });
            })
            ->when($program, function ($builder) use ($program) {
                $builder->whereHas('enrollmentForm', function ($inner) use ($program) {
                    $inner->where('program', $program);
                });
            })
            ->when($blockId, function ($builder) use ($blockId) {
                if ($blockId === 'unassigned') {
                    $builder->where(function ($inner) {
                        $inner->whereDoesntHave('enrollment')
                            ->orWhereHas('enrollment', function ($q) {
                                $q->whereNull('block_id');
                            });
                    });
                } else {
                    $builder->whereHas('enrollment', function ($inner) use ($blockId) {
                        $inner->where('block_id', $blockId);
                    });
                }
            })
            ->when($yearLevel, function ($builder) use ($yearLevel) {
                $builder->whereHas('enrollmentForm', function ($inner) use ($yearLevel) {
                    $inner->where('year_level', $yearLevel);
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $blocks = \App\Models\Block::all();

        return view('subject-enrollment', [
            'students' => $students,
            'blocks' => $blocks,
            'query' => $query,
            'program' => $program,
            'blockId' => $blockId,
            'yearLevel' => $yearLevel,
        ]);
    }
}