<!-- Brand row -->
<div class="brand-row py-3 border-bottom">
    <div class="container-fluid px-4 d-flex align-items-center flex-wrap gap-3">
        <div class="crest">ES</div>
        <div class="flex-grow-1">
            <a class="brand-title" href="/">EnrollSys</a>
            <p class="brand-tagline mb-0">Online Enrollment Portal</p>
        </div>
        <div class="input-group" style="max-width:340px;">
            <input type="text" class="form-control" placeholder="Type keyword here...">
            <button class="btn btn-maroon" type="button"><i class="bi bi-search"></i></button>
        </div>
    </div>
</div>

<!-- Main nav row -->
<nav class="nav-row">
    <div class="container-fluid px-4 d-flex justify-content-between align-items-center flex-wrap">
        @auth
            @if(auth()->user()->role === 'admin')
            <div class="navbar-nav flex-row flex-wrap">
                <a class="nav-link" href="{{ route('admin.forms.index') }}">Enrollment Form</a>
                <a class="nav-link" href="{{ route('admin.records.index') }}">Student Records</a>
                <a class="nav-link" href="{{ route('admin.approval.index') }}">Approval Queue</a>
                <a class="nav-link" href="{{ route('admin.block-assignment.index') }}">Block Assignment</a>
                <a class="nav-link" href="{{ route('admin.subjects.index') }}">Subject Enrollment</a>
            </div>
            @else
            <div class="navbar-nav flex-row flex-wrap">
                <a class="nav-link" href="{{ route('student.forms.show') }}">Enrollment Form</a>
                <a class="nav-link" href="{{ route('student.records.show') }}">Student Records</a>
                <a class="nav-link" href="{{ route('student.status.show') }}">Enrollment Status</a>
                <a class="nav-link" href="{{ route('student.block.show') }}">Block Assignment</a>
                <a class="nav-link" href="{{ route('student.subjects.show') }}">Subject Enrollment</a>
            </div>
            @endif

            <div class="d-flex align-items-center gap-2 py-2">
                <span class="text-muted small">{{ auth()->user()->name }}</span>
                <div class="rounded-circle bg-maroon d-flex justify-content-center align-items-center" style="width: 34px; height: 34px;">
                    <i class="bi bi-person-fill text-white small"></i>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mb-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-maroon ms-2">Logout</button>
                </form>
            </div>
        @else
            <div class="d-flex align-items-center gap-2 py-2">
                <a href="{{ route('login') }}" class="btn btn-sm btn-maroon">Sign In</a>
            </div>
        @endauth
    </div>
</nav>