<?php

// ============================================================
// TEMPORARY PREVIEW ROUTES — DELETE THIS FILE BEFORE GOING LIVE
// ============================================================
// These routes let you VIEW the admin/student pages without a
// real account or database, by faking a logged-in user just for
// the page render. Nothing is saved, no real login happens.
//
// Visit:
//   /preview/admin/forms
//   /preview/admin/records
//   /preview/admin/approval
//   /preview/admin/block
//   /preview/admin/subjects
//
//   /preview/student/forms
//   /preview/student/records
//   /preview/student/status
//   /preview/student/block
//   /preview/student/subjects
// ============================================================

use Illuminate\Support\Facades\Route;

// A fake user object — NOT a real database record.
// We build it on the fly just so auth()->user()->role works
// inside the blade files.
if (!function_exists('previewUser')) {
    function previewUser(string $role): \App\Models\User
    {
        $user = new \App\Models\User();
        $user->id = 0;
        $user->name = $role === 'admin' ? 'Preview Admin' : 'Preview Student';
        $user->email = 'preview@example.com';
        $user->role = $role;
        $user->student_number = $role === 'student' ? '2026-00000' : null;

        return $user;
    }
}

Route::prefix('preview')->group(function () {

    // ---------- Admin previews ----------
    Route::get('/admin/forms', function () {
        auth()->login(previewUser('admin'));
        return view('forms');
    });

    Route::get('/admin/records', function () {
        auth()->login(previewUser('admin'));
        return view('records');
    });

    Route::get('/admin/approval', function () {
        auth()->login(previewUser('admin'));
        return view('approval');
    });

    Route::get('/admin/block', function () {
        auth()->login(previewUser('admin'));
        return view('block-assignment');
    });

    Route::get('/admin/subjects', function () {
        auth()->login(previewUser('admin'));
        return view('subject-enrollment');
    });

    // ---------- Student previews ----------
    Route::get('/student/forms', function () {
        auth()->login(previewUser('student'));
        return view('forms');
    });

    Route::get('/student/records', function () {
        auth()->login(previewUser('student'));
        return view('records');
    });

    Route::get('/student/status', function () {
        auth()->login(previewUser('student'));
        return view('approval');
    });

    Route::get('/student/block', function () {
        auth()->login(previewUser('student'));
        return view('block-assignment');
    });

    Route::get('/student/subjects', function () {
        auth()->login(previewUser('student'));
        return view('subject-enrollment');
    });
});