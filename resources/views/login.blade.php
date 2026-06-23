@extends('layouts.guest')

@section('content')
<div class="card shadow-lg border-0 p-4 p-md-5">
    <div class="text-center mb-4">
        <div class="crest mb-3">ES</div>
        <h3 class="text-maroon mb-1">Sign in to EnrollSys</h3>
        <p class="text-muted small mb-0">Online Enrollment Portal</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger py-2 small">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Tabs -->
    <ul class="nav nav-pills nav-fill mb-4 bg-light rounded p-1" id="loginTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="student-tab" data-bs-toggle="tab" data-bs-target="#student-pane" type="button" role="tab">
                <i class="bi bi-person-fill me-1"></i> Student
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin-pane" type="button" role="tab">
                <i class="bi bi-shield-lock-fill me-1"></i> Admin
            </button>
        </li>
    </ul>

    <div class="tab-content" id="loginTabContent">

        <!-- Student login -->
        <div class="tab-pane fade show active" id="student-pane" role="tabpanel">
            <form method="POST" action="/login">
                @csrf
                <input type="hidden" name="role" value="student">

                <div class="mb-3">
                    <label class="form-label">Student Email or Student No.</label>
                    <input type="text" name="email" class="form-control" placeholder="juan.delacruz@email.com" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="rememberStudent">
                        <label class="form-check-label small" for="rememberStudent">Remember me</label>
                    </div>
                    <a href="/forgot-password" class="small text-maroon">Forgot password?</a>
                </div>
                <button type="submit" class="btn btn-maroon w-100">Sign In</button>
            </form>
        </div>

        <!-- Admin login -->
        <div class="tab-pane fade" id="admin-pane" role="tabpanel">
            <form method="POST" action="/login">
                @csrf
                <input type="hidden" name="role" value="admin">

                <div class="mb-3">
                    <label class="form-label">Admin Email</label>
                    <input type="text" name="email" class="form-control" placeholder="admin@enrollsys.edu" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="rememberAdmin">
                        <label class="form-check-label small" for="rememberAdmin">Remember me</label>
                    </div>
                    <a href="/forgot-password" class="small text-maroon">Forgot password?</a>
                </div>
                <button type="submit" class="btn btn-maroon w-100">Sign In as Admin</button>
            </form>
            <p class="text-center text-muted small mt-4 mb-0">
                Admin accounts are provisioned by IT. Contact support if you need access.
            </p>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection