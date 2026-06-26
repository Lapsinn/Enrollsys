@extends('layouts.app')

@section('content')

{{-- ============================================================
     ADMIN VIEW — list of students + detail/edit panel
     Controller passes: $students (paginated), $query (string)
     ============================================================ --}}
@if(auth()->user()->role === 'admin')

<div class="container mt-5">
    <h2 class="page-title">Enrollment Forms</h2>

    {{-- Flash status message --}}
    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Search --}}
    <form method="GET" action="{{ route('admin.forms.index') }}" class="row mb-4">
        <div class="col-md-5">
            <input type="text"
                   name="q"
                   class="form-control"
                   placeholder="Search by student name or ID"
                   value="{{ $query ?? '' }}">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-outline-secondary">Search</button>
            @if($query)
                <a href="{{ route('admin.forms.index') }}" class="btn btn-link text-muted">Clear</a>
            @endif
        </div>
    </form>

    {{-- Student table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="py-3 px-4">Student</th>
                        <th class="py-3">Student No.</th>
                        <th class="py-3">Program</th>
                        <th class="py-3">Year Level</th>
                        <th class="py-3">Status</th>
                        <th class="py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        @php $form = $student->enrollmentForm; @endphp
                        <tr>
                            <td class="px-4">
                                <a href="{{ route('admin.forms.show', $student) }}"
                                   class="text-maroon fw-semibold text-decoration-none">
                                    {{ $student->name }}
                                </a>
                            </td>
                            <td>{{ $student->student_number ?? '—' }}</td>
                            <td>{{ $form?->program ?? '—' }}</td>
                            <td>
                                @if($form?->year_level)
                                    {{ $form->year_level }}{{ match((int)$form->year_level) { 1 => 'st', 2 => 'nd', 3 => 'rd', default => 'th' } }} Year
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if($form)
                                    <span class="badge bg-{{ match($form->status) {
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        'pending'  => 'warning text-dark',
                                        default    => 'secondary',
                                    } }}">
                                        {{ ucfirst($form->status) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">No Form</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.forms.show', $student) }}"
                                   class="btn btn-sm btn-outline-maroon">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No matching students found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $students->links() }}
    </div>
</div>

{{-- ============================================================
     STUDENT VIEW — fill / re-view own enrollment form
     Controller passes: $form (EnrollmentForm|null)
     ============================================================ --}}
@else

<div class="container mt-5">
    <h2 class="page-title">Student Enrollment Form</h2>

    {{-- Flash messages --}}
    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Submitted / approved notice --}}
    @if($form && in_array($form->status, ['pending', 'approved']))
        <div class="alert alert-info">
            Your enrollment form has been submitted and is currently
            <strong>{{ ucfirst($form->status) }}</strong>.
            @if($form->status === 'approved')
                No further edits are allowed.
            @else
                You may update it below until it is reviewed.
            @endif
        </div>
    @endif

    @php $readonly = $form && $form->status === 'approved'; @endphp

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('student.forms.store') }}">
                @csrf

                {{-- Validation errors --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <fieldset @disabled($readonly)>

                    {{-- Name row --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                                   placeholder="Enter last name"
                                   value="{{ old('last_name', $form?->last_name) }}">
                            @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                                   placeholder="Enter first name"
                                   value="{{ old('first_name', $form?->first_name) }}">
                            @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="middle_name" class="form-control"
                                   placeholder="Enter middle name"
                                   value="{{ old('middle_name', $form?->middle_name) }}">
                        </div>
                    </div>

                    {{-- Birthdate / Sex / Applicant Type --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Birthdate</label>
                            <input type="date" name="birthdate" class="form-control @error('birthdate') is-invalid @enderror"
                                   value="{{ old('birthdate', $form?->birthdate) }}">
                            @error('birthdate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sex</label>
                            <select class="form-select @error('sex') is-invalid @enderror" name="sex">
                                <option selected disabled>Select...</option>
                                <option value="male"   {{ old('sex', $form?->sex) == 'male'   ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('sex', $form?->sex) == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('sex')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Applicant Type</label>
                            <select class="form-select @error('applicant_type') is-invalid @enderror" name="applicant_type">
                                <option selected disabled>Select...</option>
                                <option value="new"        {{ old('applicant_type', $form?->applicant_type) == 'new'        ? 'selected' : '' }}>New</option>
                                <option value="old"        {{ old('applicant_type', $form?->applicant_type) == 'old'        ? 'selected' : '' }}>Old</option>
                                <option value="transferee" {{ old('applicant_type', $form?->applicant_type) == 'transferee' ? 'selected' : '' }}>Transferee</option>
                            </select>
                            @error('applicant_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Program / Year Level / Semester --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Program</label>
                            <select class="form-select @error('program') is-invalid @enderror" name="program">
                                <option selected disabled>Select...</option>
                                <option value="bsit" {{ old('program', $form?->program) == 'bsit' ? 'selected' : '' }}>BS Information Technology</option>
                                <option value="bscs" {{ old('program', $form?->program) == 'bscs' ? 'selected' : '' }}>BS Computer Science</option>
                            </select>
                            @error('program')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Year Level</label>
                            <select class="form-select @error('year_level') is-invalid @enderror" name="year_level">
                                <option selected disabled>Select...</option>
                                @foreach([1,2,3,4] as $yr)
                                    <option value="{{ $yr }}" {{ old('year_level', $form?->year_level) == $yr ? 'selected' : '' }}>
                                        {{ $yr }}{{ match($yr) { 1 => 'st', 2 => 'nd', 3 => 'rd', default => 'th' } }} Year
                                    </option>
                                @endforeach
                            </select>
                            @error('year_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Semester</label>
                            <select class="form-select @error('semester') is-invalid @enderror" name="semester">
                                <option selected disabled>Select...</option>
                                <option value="1" {{ old('semester', $form?->semester) == '1' ? 'selected' : '' }}>1st Semester</option>
                                <option value="2" {{ old('semester', $form?->semester) == '2' ? 'selected' : '' }}>2nd Semester</option>
                            </select>
                            @error('semester')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Address --}}
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Home Address</label>
                            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                                   placeholder="Enter full address"
                                   value="{{ old('address', $form?->address) }}">
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Email / Contact --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   placeholder="Enter email address"
                                   value="{{ old('email', $form ? auth()->user()->email : '') }}">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_number" class="form-control @error('contact_number') is-invalid @enderror"
                                   placeholder="Enter contact number"
                                   value="{{ old('contact_number', $form?->contact_number) }}">
                            @error('contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Emergency Contact / Last School --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Emergency Contact</label>
                            <input type="text" name="emergency_contact" class="form-control @error('emergency_contact') is-invalid @enderror"
                                   placeholder="Name and Number"
                                   value="{{ old('emergency_contact', $form?->emergency_contact) }}">
                            @error('emergency_contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last School Attended</label>
                            <input type="text" name="last_school" class="form-control @error('last_school') is-invalid @enderror"
                                   placeholder="Enter school name"
                                   value="{{ old('last_school', $form?->last_school) }}">
                            @error('last_school')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>


                </fieldset>

                {{-- Action buttons — hidden if form is approved --}}
                @unless($readonly)
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit"
                            formaction="{{ route('student.forms.draft') }}"
                            class="btn btn-outline-secondary px-4">
                        Save Draft
                    </button>
                    <button type="submit" class="btn btn-maroon px-4">
                        Submit Enrollment
                    </button>
                </div>
                @endunless

            </form>
        </div>
    </div>
</div>

@endif
@endsection
