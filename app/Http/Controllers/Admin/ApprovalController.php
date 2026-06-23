<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApprovalController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->input('q');
        $program = $request->input('program');
        $status = $request->input('status');

        $applications = User::query()
            ->where('role', 'student')
            ->when($query, function ($builder) use ($query) {
                $builder->where(function ($inner) use ($query) {
                    $inner->where('name', 'like', "%{$query}%")
                        ->orWhere('student_number', 'like', "%{$query}%");
                });
            })
            // ->when($program, fn ($b) => $b->where('program', $program))
            // ->when($status, fn ($b) => $b->where('application_status', $status))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('approval', [
            'applications' => $applications,
            'query' => $query,
        ]);
    }

    public function approve(User $student): RedirectResponse
    {
        // $student->application->update(['status' => 'approved']);

        return redirect()
            ->route('admin.approval.index')
            ->with('status', "{$student->name}'s application was approved.");
    }

    public function reject(User $student): RedirectResponse
    {
        // $student->application->update(['status' => 'rejected']);

        return redirect()
            ->route('admin.approval.index')
            ->with('status', "{$student->name}'s application was rejected.");
    }

    public function storeNote(Request $request, User $student): RedirectResponse
    {
        $validated = $request->validate([
            'note' => ['required', 'string', 'max:2000'],
        ]);

        // $student->notes()->create([
        //     'author_id' => auth()->id(),
        //     'body' => $validated['note'],
        // ]);

        return redirect()
            ->route('admin.approval.index')
            ->with('status', "Note added for {$student->name}.");
    }
}