@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="page-title">Student Records</h2>

    @if(auth()->user()->role === 'admin')
    {{-- ADMIN VIEW --}}
    <form method="GET" action="{{ route('admin.records.index') }}" class="row mb-4 g-2">
        <div class="col-md-5">
            <input type="text" name="q" class="form-control" placeholder="Search student name/ID" value="{{ request('q') }}">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-outline-secondary">Search</button>
            @if(request('q'))
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
                $selectedStudent = $selectedId ? \App\Models\User::with(['enrollmentForm.subjects', 'enrollment.block'])->find($selectedId) : null;
                $selectedForm = $selectedStudent?->enrollmentForm;
                $subjects = $selectedForm?->subjects ?? collect();
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

                <div class="row mb-4 text-center">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm bg-light py-3">
                            <h6 class="text-muted mb-1">Units loaded</h6>
                            <h3 class="mb-0 text-maroon">{{ $subjects->sum('units') }}</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm bg-light py-3">
                            <h6 class="text-muted mb-1">GPA / GWA</h6>
                            <h3 class="mb-0 text-maroon">1.50</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm bg-light py-3">
                            <h6 class="text-muted mb-1">Status</h6>
                            <h3 class="mb-0 text-success">Regular</h3>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3 px-4">Subject</th>
                                    <th class="py-3">Code</th>
                                    <th class="py-3">Units</th>
                                    <th class="py-3">Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subjects as $subj)
                                    <tr>
                                        <td class="px-4 fw-semibold text-dark">{{ $subj->name }}</td>
                                        <td><span class="text-maroon fw-semibold">{{ $subj->code }}</span></td>
                                        <td>{{ $subj->units }}</td>
                                        <td><span class="text-success fw-semibold">1.50</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No enrolled subjects found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
        $form = auth()->user()->enrollmentForm;
        $subjects = $form?->subjects ?? collect();
    @endphp

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body d-flex align-items-center gap-3">
            <div class="rounded-circle bg-light border d-flex justify-content-center align-items-center flex-shrink-0" style="width:48px;height:48px;">
                <i class="bi bi-person fs-5 text-muted"></i>
            </div>
            <div>
                <h5 class="mb-0 text-maroon">{{ auth()->user()->name }}</h5>
                <span class="text-muted small">Student No. {{ auth()->user()->student_number ?? '—' }}</span>
            </div>
        </div>
    </div>

    <div class="row mb-4 text-center">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-light py-3">
                <h6 class="text-muted mb-1">Units this semester</h6>
                <h3 class="mb-0 text-maroon">{{ $subjects->sum('units') }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-light py-3">
                <h6 class="text-muted mb-1">GWA</h6>
                <h3 class="mb-0 text-maroon">1.50</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-light py-3">
                <h6 class="text-muted mb-1">Status</h6>
                <h3 class="mb-0 text-success">Regular</h3>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="py-3 px-4">Subject</th>
                        <th class="py-3">Code</th>
                        <th class="py-3">Units</th>
                        <th class="py-3">Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $subj)
                        <tr>
                            <td class="px-4 fw-semibold text-dark">{{ $subj->name }}</td>
                            <td><span class="text-maroon fw-semibold">{{ $subj->code }}</span></td>
                            <td>{{ $subj->units }}</td>
                            <td><span class="text-success fw-semibold">1.50</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">You have not enrolled in any subjects yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection