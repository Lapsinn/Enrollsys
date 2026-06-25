@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.forms.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
            <h2 class="page-title mb-0">Edit Enrollment Form</h2>
        </div>
        <div>
            @if($form)
                <span class="badge bg-{{ match($form->status) {
                    'approved' => 'success',
                    'rejected' => 'danger',
                    'pending'  => 'warning text-dark',
                    default    => 'secondary',
                } }} fs-6 px-3 py-2">
                    {{ ucfirst($form->status) }}
                </span>
            @else
                <span class="badge bg-secondary fs-6 px-3 py-2">No Form Submitted</span>
            @endif
        </div>
    </div>

    {{-- Flash status message --}}
    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h4 class="text-maroon mb-4">Student Info & Enrollment Details</h4>
            
            <form method="POST" action="{{ route('admin.forms.update', $student) }}">
                @csrf
                @method('PATCH')

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

                {{-- Name row --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Last Name</label>
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                               value="{{ old('last_name', $form?->last_name) }}" required>
                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">First Name</label>
                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                               value="{{ old('first_name', $form?->first_name) }}" required>
                        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control"
                               value="{{ old('middle_name', $form?->middle_name) }}">
                    </div>
                </div>

                {{-- Birthdate / Sex / Applicant Type --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Birthdate</label>
                        <input type="date" name="birthdate" class="form-control @error('birthdate') is-invalid @enderror"
                               value="{{ old('birthdate', $form?->birthdate) }}" required>
                        @error('birthdate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Sex</label>
                        <select class="form-select @error('sex') is-invalid @enderror" name="sex" required>
                            <option value="male"   {{ old('sex', $form?->sex) == 'male'   ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('sex', $form?->sex) == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('sex')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Applicant Type</label>
                        <select class="form-select @error('applicant_type') is-invalid @enderror" name="applicant_type" required>
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
                        <label class="form-label fw-semibold">Program</label>
                        <select class="form-select @error('program') is-invalid @enderror" name="program" required>
                            <option value="bsit" {{ old('program', $form?->program) == 'bsit' ? 'selected' : '' }}>BS Information Technology</option>
                            <option value="bscs" {{ old('program', $form?->program) == 'bscs' ? 'selected' : '' }}>BS Computer Science</option>
                        </select>
                        @error('program')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Year Level</label>
                        <select class="form-select @error('year_level') is-invalid @enderror" name="year_level" required>
                            @foreach([1,2,3,4] as $yr)
                                <option value="{{ $yr }}" {{ old('year_level', $form?->year_level) == $yr ? 'selected' : '' }}>
                                    {{ $yr }}{{ match($yr) { 1 => 'st', 2 => 'nd', 3 => 'rd', default => 'th' } }} Year
                                </option>
                            @endforeach
                        </select>
                        @error('year_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Semester</label>
                        <select class="form-select @error('semester') is-invalid @enderror" name="semester" required>
                            <option value="1" {{ old('semester', $form?->semester) == '1' ? 'selected' : '' }}>1st Semester</option>
                            <option value="2" {{ old('semester', $form?->semester) == '2' ? 'selected' : '' }}>2nd Semester</option>
                        </select>
                        @error('semester')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Address --}}
                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Home Address</label>
                        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                               value="{{ old('address', $form?->address) }}" required>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Email / Contact --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $student->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control @error('contact_number') is-invalid @enderror"
                               value="{{ old('contact_number', $form?->contact_number) }}" required>
                        @error('contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Emergency Contact / Last School --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Emergency Contact</label>
                        <input type="text" name="emergency_contact" class="form-control @error('emergency_contact') is-invalid @enderror"
                               value="{{ old('emergency_contact', $form?->emergency_contact) }}" required>
                        @error('emergency_contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Last School Attended</label>
                        <input type="text" name="last_school" class="form-control @error('last_school') is-invalid @enderror"
                               value="{{ old('last_school', $form?->last_school) }}" required>
                        @error('last_school')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.forms.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    <button type="submit" class="btn btn-maroon px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
