@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="page-title">Student Records</h2>

    @if(auth()->user()->role === 'admin')
    {{-- ADMIN VIEW --}}
    <form method="GET" action="{{ route('admin.records.index') }}" class="row mb-4 g-2 align-items-center">
        <div class="col-md-4">
            <input type="text" name="q" class="form-control" placeholder="Search student name/ID" value="{{ request('q') }}">
        </div>
        <div class="col-md-2">
            <select name="semester" class="form-select">
                <option value="">All Semesters</option>
                <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>1st Semester</option>
                <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>2nd Semester</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="ay" class="form-select">
                <option value="">All Years</option>
                <option value="1" {{ request('ay') == '1' ? 'selected' : '' }}>1st Year</option>
                <option value="2" {{ request('ay') == '2' ? 'selected' : '' }}>2nd Year</option>
                <option value="3" {{ request('ay') == '3' ? 'selected' : '' }}>3rd Year</option>
                <option value="4" {{ request('ay') == '4' ? 'selected' : '' }}>4th Year</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-maroon">Filter</button>
            @if(request('q') || request('semester') || request('ay'))
                <a href="{{ route('admin.records.index') }}" class="btn btn-link text-muted">Clear</a>
            @endif
        </div>
    </form>

    <div class="row">
        {{-- Left: Students List --}}
        <div class="col-md-5">
            <div class="card shadow-sm p-3">
                <div class="list-group">
                    @forelse($students as $st)
                        @php 
                            $isSelected = request('student_id') ? request('student_id') == $st->id : $loop->first;
                            $form = $st->enrollmentForm;
                        @endphp
                        <a href="{{ route('admin.records.index', array_merge(request()->query(), ['student_id' => $st->id])) }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $isSelected ? 'active bg-maroon border-maroon' : '' }}">
                            <div class="text-start">
                                <div class="fw-semibold {{ $isSelected ? 'text-white' : 'text-dark' }}">{{ $st->name }}</div>
                                <div class="small {{ $isSelected ? 'text-white-50' : 'text-muted' }}">
                                    {{ $st->student_number ?? 'No Student No.' }}
                                </div>
                            </div>
                            <span class="badge bg-light text-maroon fw-semibold">
                                {{ strtoupper($form?->program ?? 'N/A') }}
                            </span>
                        </a>
                    @empty
                        <div class="text-center text-muted py-4">No students found.</div>
                    @endforelse
                </div>
                <div class="mt-3">
                    {{ $students->links() }}
                </div>
            </div>
        </div>

        {{-- Right: Student Record Detail --}}
        <div class="col-md-7">
            @php
                $selectedId = request('student_id') ?: ($students->first()?->id ?? null);
                $selectedStudent = $selectedId ? \App\Models\User::with(['enrollmentForm'])->find($selectedId) : null;
                $selectedForm = $selectedStudent?->enrollmentForm;
            @endphp

            @if($selectedStudent)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-light border d-flex justify-content-center align-items-center flex-shrink-0" style="width:48px;height:48px;">
                            <i class="bi bi-person fs-5 text-muted"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 text-maroon">{{ $selectedStudent->name }}</h5>
                            <span class="text-muted small">Student No. {{ $selectedStudent->student_number ?? '—' }}</span>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 p-4">
                    <h5 class="text-maroon mb-3">Academic Record Document</h5>
                    
                    @if($selectedForm && $selectedForm->record_file)
                        <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                            <i class="bi bi-check-circle-fill fs-5"></i>
                            <div>
                                A record file is available for this student.
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('records.download', $selectedStudent) }}" class="btn btn-maroon px-4">
                                <i class="bi bi-download"></i> View / Download Record File
                            </a>
                        </div>
                    @else
                        <div class="alert alert-warning d-flex align-items-center gap-2 mb-0">
                            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                            <div>
                                No record file has been uploaded for this student yet.
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="card shadow-sm p-4 text-center text-muted my-5">
                    <i class="bi bi-folder2-open fs-1 text-maroon mb-2"></i>
                    <p>Select a student to view their records.</p>
                </div>
            @endif
        </div>
    </div>
    @else
    {{-- STUDENT VIEW --}}
    @php
        $student = auth()->user();
    @endphp

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body d-flex align-items-center gap-3">
            <div class="rounded-circle bg-light border d-flex justify-content-center align-items-center flex-shrink-0" style="width:48px;height:48px;">
                <i class="bi bi-person fs-5 text-muted"></i>
            </div>
            <div>
                <h5 class="mb-0 text-maroon">{{ $student->name }}</h5>
                <span class="text-muted small">Student No. {{ $student->student_number ?? '—' }}</span>
            </div>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm p-4">
        <h4 class="text-maroon mb-4">Upload / Update Academic Record</h4>
        
        @if(!$student->enrollmentForm)
            <div class="alert alert-warning mb-0">
                <i class="bi bi-exclamation-triangle-fill"></i> Please start your <a href="{{ route('student.form.show') }}" class="alert-link">Enrollment Form</a> draft first before uploading academic records.
            </div>
        @else
            @if($student->enrollmentForm->record_file)
                <div class="alert alert-success d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                        <div>
                            <strong>Academic Record Document is available.</strong>
                        </div>
                    </div>
                    <a href="{{ route('records.download', $student) }}" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-download"></i> Download Current File
                    </a>
                </div>
            @else
                <div class="alert alert-warning mb-4">
                    <i class="bi bi-exclamation-triangle-fill"></i> No record file has been uploaded yet. Please upload your document below.
                </div>
            @endif

            <form method="POST" action="{{ route('student.records.upload') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="recordFile" class="form-label fw-semibold">Select Document File</label>
                    <input type="file" name="record_file" id="recordFile" class="form-control" required>
                    <div class="form-text text-muted">
                        Accepted formats: PDF, Word (doc/docx), or images (jpg/png). Max size: 10MB.
                    </div>
                </div>
                <button type="submit" class="btn btn-maroon px-4">
                    <i class="bi bi-upload"></i> {{ $student->enrollmentForm->record_file ? 'Replace File' : 'Upload File' }}
                </button>
            </form>
        @endif
    </div>
    @endif
</div>
@endsection