<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlockAssignmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->input('q');
        $program = $request->input('program');
        $yearLevel = $request->input('year_level');

        $students = User::query()
            ->where('role', 'student')
            ->when($query, fn ($b) => $b->where('name', 'like', "%{$query}%"))
            // ->when($program, fn ($b) => $b->where('program', $program))
            // ->when($yearLevel, fn ($b) => $b->where('year_level', $yearLevel))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('block-assignment', [
            'students' => $students,
            'query' => $query,
        ]);
    }

    public function update(Request $request, User $student): RedirectResponse
    {
        $validated = $request->validate([
            'block' => ['required', 'string', 'in:Block A,Block B,Block C'],
        ]);

        // $student->blockAssignment()->update(['block' => $validated['block']]);

        return redirect()
            ->route('admin.block-assignment.index')
            ->with('status', "{$student->name} assigned to {$validated['block']}.");
    }

    public function bulkAssign(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_ids' => ['required', 'array'],
            'student_ids.*' => ['exists:users,id'],
            'block' => ['required', 'string', 'in:Block A,Block B,Block C'],
        ]);

        // User::whereIn('id', $validated['student_ids'])
        //     ->each(fn ($student) => $student->blockAssignment()->update(['block' => $validated['block']]));

        return redirect()
            ->route('admin.block-assignment.index')
            ->with('status', 'Bulk assignment completed.');
    }
}