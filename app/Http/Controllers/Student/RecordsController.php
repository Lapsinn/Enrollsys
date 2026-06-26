<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class RecordsController extends Controller
{
    public function show(): View
    {
        return view('records', [
            'student' => auth()->user(),
        ]);
    }

    public function upload(Request $request): RedirectResponse
    {
        $request->validate([
            'record_file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:10240'], // Max 10MB
        ]);

        /** @var User $user */
        $user = auth()->user();

        $form = $user->enrollmentForm;
        if (!$form) {
            return back()->withErrors(['form' => 'Please fill out and save your enrollment form draft first before uploading academic records.']);
        }

        // Delete old file if exists
        if ($form->record_file && Storage::exists($form->record_file)) {
            Storage::delete($form->record_file);
        }

        // Store new file
        $path = $request->file('record_file')->store('records');

        $form->update([
            'record_file' => $path,
        ]);

        return back()->with('status', 'Student record file uploaded successfully.');
    }

    public function download(User $user): StreamedResponse|RedirectResponse
    {
        // Check authorization: must be the student themselves or an admin
        if (auth()->id() !== $user->id && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $form = $user->enrollmentForm;
        if (!$form || !$form->record_file || !Storage::exists($form->record_file)) {
            return back()->withErrors(['file' => 'File not found on server.']);
        }

        $extension = pathinfo($form->record_file, PATHINFO_EXTENSION);
        $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $user->name);
        $cleanNumber = preg_replace('/[^A-Za-z0-9_\-]/', '_', $user->student_number ?? 'Record');
        
        $portalName = config('app.name', 'EnrollSys');
        $filename = $extension 
            ? "{$portalName}_{$cleanName}_{$cleanNumber}_Academic_Record.{$extension}"
            : "{$portalName}_{$cleanName}_{$cleanNumber}_Academic_Record";

        return Storage::download($form->record_file, $filename);
    }
}