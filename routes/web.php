<?php

use App\Http\Controllers\Admin\ApprovalController as AdminApprovalController;
use App\Http\Controllers\Admin\BlockAssignmentController as AdminBlockAssignmentController;
use App\Http\Controllers\Admin\EnrollmentFormController as AdminEnrollmentFormController;
use App\Http\Controllers\Admin\RecordsController as AdminRecordsController;
use App\Http\Controllers\Admin\SubjectEnrollmentController as AdminSubjectEnrollmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Student\BlockAssignmentController as StudentBlockAssignmentController;
use App\Http\Controllers\Student\EnrollmentFormController as StudentEnrollmentFormController;
use App\Http\Controllers\Student\EnrollmentStatusController as StudentEnrollmentStatusController;
use App\Http\Controllers\Student\RecordsController as StudentRecordsController;
use App\Http\Controllers\Student\SubjectEnrollmentController as StudentSubjectEnrollmentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public / Guest Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('welcome'))->name('welcome');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
| Uses the same blade files as admin (forms, records, approval ->
| "Enrollment Status", block-assignment, subject-enrollment), but each
| blade checks auth()->user()->role to render the student-facing half.
*/

Route::middleware(['auth', 'student'])->group(function () {
    Route::get('/forms', [StudentEnrollmentFormController::class, 'show'])->name('student.forms.show');
    Route::post('/forms', [StudentEnrollmentFormController::class, 'store'])->name('student.forms.store');
    Route::post('/forms/draft', [StudentEnrollmentFormController::class, 'saveDraft'])->name('student.forms.draft');

    Route::get('/records', [StudentRecordsController::class, 'show'])->name('student.records.show');

    // "Approval Queue" renamed to "Enrollment Status" for students
    Route::get('/status', [StudentEnrollmentStatusController::class, 'show'])->name('student.status.show');

    Route::get('/block', [StudentBlockAssignmentController::class, 'show'])->name('student.block.show');

    Route::get('/subjects', [StudentSubjectEnrollmentController::class, 'show'])->name('student.subjects.show');
    Route::post('/subjects', [StudentSubjectEnrollmentController::class, 'store'])->name('student.subjects.store');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Enrollment Form: search -> view -> edit
    Route::get('/forms', [AdminEnrollmentFormController::class, 'index'])->name('admin.forms.index');
    Route::get('/forms/{student}', [AdminEnrollmentFormController::class, 'show'])->name('admin.forms.show');
    Route::patch('/forms/{student}', [AdminEnrollmentFormController::class, 'update'])->name('admin.forms.update');

    // Student Records (read-only, no export)
    Route::get('/records', [AdminRecordsController::class, 'index'])->name('admin.records.index');

    // Approval Queue: search, approve, reject, notes
    Route::get('/approval', [AdminApprovalController::class, 'index'])->name('admin.approval.index');
    Route::post('/approval/{student}/approve', [AdminApprovalController::class, 'approve'])->name('admin.approval.approve');
    Route::post('/approval/{student}/reject', [AdminApprovalController::class, 'reject'])->name('admin.approval.reject');
    Route::post('/approval/{student}/note', [AdminApprovalController::class, 'storeNote'])->name('admin.approval.note');

    // Block Assignment: search, save, bulk assign
    Route::get('/block', [AdminBlockAssignmentController::class, 'index'])->name('admin.block-assignment.index');
    Route::patch('/block/{student}', [AdminBlockAssignmentController::class, 'update'])->name('admin.block-assignment.update');
    Route::post('/block/bulk', [AdminBlockAssignmentController::class, 'bulkAssign'])->name('admin.block-assignment.bulk');

    // Subject Enrollment overview: search
    Route::get('/subjects', [AdminSubjectEnrollmentController::class, 'index'])->name('admin.subjects.index');
});