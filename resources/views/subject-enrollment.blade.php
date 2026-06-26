@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="page-title">Subject Enrollment</h2>

    {{-- Flash status message --}}
    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Validation errors --}}
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

    @if(auth()->user()->role === 'admin')
    {{-- ADMIN VIEW --}}
    <form method="GET" action="{{ route('admin.subjects.index') }}" class="row mb-4 g-2 align-items-center">
        <div class="col-md-3">
            <input type="text" name="q" class="form-control" placeholder="Search student name/ID" value="{{ request('q') }}">
        </div>
        <div class="col-md-2">
            <select name="program" class="form-select">
                <option value="">All Programs</option>
                <option value="bscs" {{ request('program') == 'bscs' ? 'selected' : '' }}>BSCS</option>
                <option value="bsit" {{ request('program') == 'bsit' ? 'selected' : '' }}>BSIT</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="block_id" class="form-select">
                <option value="">All Blocks</option>
                <option value="unassigned" {{ request('block_id') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                @foreach($blocks as $b)
                    <option value="{{ $b->id }}" {{ request('block_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                @endforeach
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
            @if(request('q') || request('program') || request('block_id') || request('ay'))
                <a href="{{ route('admin.subjects.index') }}" class="btn btn-link text-muted">Clear</a>
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
                            $form = $st->enrollmentForm;
                            $enrolledCount = $form?->subjects()->count() ?? 0;
                            $totalUnits = $form?->subjects()->sum('units') ?? 0;
                            $isSelected = request('student_id') ? request('student_id') == $st->id : $loop->first;
                        @endphp
                        <a href="{{ route('admin.subjects.index', array_merge(request()->query(), ['student_id' => $st->id])) }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $isSelected ? 'active bg-maroon border-maroon' : '' }}">
                            <div class="text-start">
                                <div class="fw-semibold {{ $isSelected ? 'text-white' : 'text-dark' }}">{{ $st->name }}</div>
                                <div class="small {{ $isSelected ? 'text-white-50' : 'text-muted' }}">
                                    {{ $st->student_number ?? 'No Student No.' }}
                                </div>
                            </div>
                            <span class="badge bg-light text-maroon fw-semibold">
                                {{ $totalUnits }} Units ({{ $enrolledCount }} Subjs)
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

        {{-- Right: Selected Student's Enrolled Subjects --}}
        <div class="col-md-7">
            @php
                $selectedId = request('student_id') ?: ($students->first()?->id ?? null);
                $selectedStudent = $selectedId ? \App\Models\User::with(['enrollmentForm.subjects', 'enrollment.block'])->find($selectedId) : null;
                $selectedForm = $selectedStudent?->enrollmentForm;
                $enrolledSubjects = $selectedForm?->subjects ?? collect();
            @endphp

            @if($selectedStudent)
                <div class="card shadow-sm p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="rounded-circle bg-light border d-flex justify-content-center align-items-center flex-shrink-0" style="width:56px;height:56px;">
                            <i class="bi bi-person fs-4 text-muted"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 text-maroon">{{ $selectedStudent->name }}</h5>
                            <span class="text-muted small">
                                {{ $selectedForm ? strtoupper($selectedForm->program) : 'N/A' }} 
                                @if($selectedForm?->year_level)
                                    , {{ $selectedForm->year_level }}{{ match((int)$selectedForm->year_level) { 1 => 'st', 2 => 'nd', 3 => 'rd', default => 'th' } }} Year
                                @endif
                                @if($selectedStudent->enrollment?->block)
                                    - {{ $selectedStudent->enrollment->block->name }}
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h6 class="fw-bold mb-0">
                            Enrolled Subjects 
                            <span class="badge bg-{{ $selectedForm?->subjects_status === 'approved' ? 'success' : 'warning text-dark' }} ms-2" style="font-size:0.8rem;">
                                {{ $selectedForm?->subjects_status === 'approved' ? 'Approved & Locked' : 'Pending Review' }}
                            </span>
                        </h6>
                        <div class="d-flex gap-2">
                            <span class="badge bg-maroon fs-6 px-3 py-2">
                                Total Units: {{ $enrolledSubjects->sum('units') }}
                            </span>
                            @if($selectedForm)
                                @if($selectedForm->subjects_status !== 'approved')
                                    <form method="POST" action="{{ route('admin.subjects.approve', $selectedStudent) }}" class="mb-0">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-circle"></i> Approve Subjects</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.subjects.unlock', $selectedStudent) }}" class="mb-0">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-warning"><i class="bi bi-unlock"></i> Unlock Subjects</button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Subject Description</th>
                                    <th class="text-center">Units</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($enrolledSubjects as $subj)
                                    <tr>
                                        <td><span class="fw-semibold text-maroon">{{ $subj->code }}</span></td>
                                        <td>{{ $subj->name }}</td>
                                        <td class="text-center">{{ $subj->units }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">No subjects enrolled yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="card shadow-sm p-4 text-center text-muted my-5">
                    <i class="bi bi-journal-check fs-1 text-maroon mb-2"></i>
                    <p>Select a student to view their enrolled subjects.</p>
                </div>
            @endif
        </div>
    </div>
    @else
    {{-- STUDENT VIEW --}}
    @php
        $form = auth()->user()->enrollmentForm;
        $totalUnitsLoaded = $subjects->whereIn('id', $enrolledSubjectIds)->sum('units');
    @endphp

    <div class="card shadow-sm p-3 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-light border d-flex justify-content-center align-items-center flex-shrink-0" style="width:56px;height:56px;">
                    <i class="bi bi-person fs-4 text-muted"></i>
                </div>
                <div>
                    <h5 class="mb-0 text-maroon">{{ auth()->user()->name }}</h5>
                    <span class="text-muted small">
                        {{ $form ? strtoupper($form->program) : 'N/A' }}
                        @if($form?->year_level)
                            , {{ $form->year_level }}{{ match((int)$form->year_level) { 1 => 'st', 2 => 'nd', 3 => 'rd', default => 'th' } }} Year
                        @endif
                        @if(auth()->user()->enrollment?->block)
                            - {{ auth()->user()->enrollment->block->name }}
                        @endif
                    </span>
                </div>
            </div>
            <div class="text-end">
                <span class="text-muted small">Units Loaded</span>
                <h5 class="mb-0 text-maroon">{{ $totalUnitsLoaded }}</h5>
            </div>
        </div>
    </div>

    @if(!$form)
        <div class="alert alert-warning">
            Please submit your <a href="{{ route('student.forms.show') }}" class="alert-link">Enrollment Form</a> first to select and enroll in subjects.
        </div>
    @else
        <form method="POST" action="{{ route('student.subjects.store') }}" x-data="{ 
            search: '', 
            matchesSearch(code, name) {
                if (!this.search) return true;
                const q = this.search.toLowerCase();
                return code.toLowerCase().includes(q) || name.toLowerCase().includes(q);
            }
        }">
            @csrf
            
            @if($readonly)
                <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <div>
                        Your subject enrollment has been approved by the admin and is locked. No further changes can be made.
                    </div>
                </div>
            @endif

            <fieldset @disabled($readonly)>
                <h5 class="mb-3 text-maroon">Select Subjects to Enroll</h5>

                {{-- Search Bar --}}
                <div class="card p-3 mb-3 bg-light border-0 shadow-sm">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" x-model="search" class="form-control border-start-0 ps-0" placeholder="Search by subject code or title..." required>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2 mb-4">
                    @foreach($subjects as $subj)
                        @php $isEnrolled = in_array($subj->id, $enrolledSubjectIds); @endphp
                        <div class="card shadow-sm p-3 border-start border-maroon border-3"
                             x-show="matchesSearch('{{ addslashes($subj->code) }}', '{{ addslashes($subj->name) }}')">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-3">
                                    <input type="checkbox" name="subjects[]" value="{{ $subj->id }}" class="form-check-input" {{ $isEnrolled ? 'checked' : '' }}>
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $subj->code }} - {{ $subj->name }}</div>
                                        <span class="text-muted small">{{ $subj->units }} units</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </fieldset>

            <h5 class="text-maroon mb-3">Enrolled Class Schedule Preview</h5>
            <div class="card shadow-sm mb-4">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4">Code</th>
                            <th>Subject Description</th>
                            <th>Units</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $hasSubjects = false; @endphp
                        @foreach($subjects as $subj)
                            @if(in_array($subj->id, $enrolledSubjectIds))
                                @php $hasSubjects = true; @endphp
                                <tr>
                                    <td class="px-4 fw-semibold text-maroon">{{ $subj->code }}</td>
                                    <td>{{ $subj->name }}</td>
                                    <td>{{ $subj->units }}</td>
                                </tr>
                            @endif
                        @endforeach
                        @if(!$hasSubjects)
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">No subjects currently enrolled. Select subjects above and click save.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="text-end mb-5">
                <a href="{{ route('welcome') }}" class="btn btn-secondary me-2">Cancel</a>
                @unless($readonly)
                    <button type="submit" class="btn btn-maroon">Enroll Subjects</button>
                @endunless
            </div>
        </form>
    @endif
    @endif
</div>
@endsection