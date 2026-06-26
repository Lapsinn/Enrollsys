<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Block;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlockAssignmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->input('q');
        $blockId = $request->input('block_id');
        $program = $request->input('program');

        $students = User::query()
            ->where('role', 'student')
            ->with(['enrollmentForm', 'enrollment.block'])
            ->when($query, function ($builder) use ($query) {
                $builder->where(function ($inner) use ($query) {
                    $inner->where('name', 'like', "%{$query}%")
                        ->orWhere('student_number', 'like', "%{$query}%");
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
            ->when($program, function ($builder) use ($program) {
                $builder->whereHas('enrollmentForm', function ($inner) use ($program) {
                    $inner->where('program', $program);
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $blocks = Block::all();

        return view('block-assignment', [
            'students' => $students,
            'blocks' => $blocks,
            'query' => $query,
            'blockId' => $blockId,
            'program' => $program,
        ]);
    }

    public function update(Request $request, User $student): RedirectResponse
    {
        $validated = $request->validate([
            'block_id' => ['nullable', 'exists:blocks,id'],
        ]);

        if ($validated['block_id'] && $student->enrollmentForm?->year_level) {
            $block = Block::find($validated['block_id']);
            $year = $student->enrollmentForm->year_level;
            if (!str_starts_with($block->name, "{$year}-")) {
                return redirect()
                    ->back()
                    ->withErrors(['block_id' => "Selected block '{$block->name}' does not match student's Year Level ({$year})."]);
            }
        }

        $enrollment = Enrollment::updateOrCreate(
            ['email' => $student->email],
            [
                'student_name' => $student->name,
                'course' => $student->enrollmentForm?->program ?? 'bscs',
                'block_id' => $validated['block_id'] ?: null,
            ]
        );

        $blockName = $validated['block_id'] ? Block::find($validated['block_id'])->name : 'Unassigned';

        return redirect()
            ->route('admin.block-assignment.index')
            ->with('status', "{$student->name} assigned to {$blockName}.");
    }

    public function bulkAssign(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_ids' => ['required', 'array'],
            'student_ids.*' => ['exists:users,id'],
            'block_id' => ['nullable', 'exists:blocks,id'],
        ]);

        $students = User::whereIn('id', $validated['student_ids'])->get();

        if ($validated['block_id']) {
            $block = Block::find($validated['block_id']);
            foreach ($students as $student) {
                if ($student->enrollmentForm?->year_level) {
                    $year = $student->enrollmentForm->year_level;
                    if (!str_starts_with($block->name, "{$year}-")) {
                        return redirect()
                            ->back()
                            ->withErrors(['block_id' => "Cannot assign student '{$student->name}' (Year {$year}) to block '{$block->name}'."]);
                    }
                }
            }
        }

        foreach ($students as $student) {
            Enrollment::updateOrCreate(
                ['email' => $student->email],
                [
                    'student_name' => $student->name,
                    'course' => $student->enrollmentForm?->program ?? 'bscs',
                    'block_id' => $validated['block_id'] ?: null,
                ]
            );
        }

        return redirect()
            ->route('admin.block-assignment.index')
            ->with('status', 'Bulk assignment completed.');
    }
}