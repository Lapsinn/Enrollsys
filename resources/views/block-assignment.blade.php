@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="page-title">Block Assignment</h2>
    <p class="text-muted mb-4">1st Semester, 2026-2027</p>

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
    {{-- Search & Filters Form --}}
    <form method="GET" action="{{ route('admin.block-assignment.index') }}" class="row mb-3 g-2 align-items-center">
        <div class="col-md-4">
            <input type="text" name="q" class="form-control" placeholder="Search student name or ID" value="{{ request('q') }}">
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
        <div class="col-auto">
            <button type="submit" class="btn btn-maroon">Filter</button>
            @if(request('q') || request('program') || request('block_id'))
                <a href="{{ route('admin.block-assignment.index') }}" class="btn btn-link text-muted">Clear</a>
            @endif
        </div>
    </form>

    {{-- Bulk Action Panel --}}
    <div class="card p-3 mb-4 bg-light border-0 shadow-sm">
        <div class="row align-items-center g-2">
            <div class="col-md-auto">
                <span class="fw-bold text-dark me-2">Bulk Action:</span>
            </div>
            <div class="col-md-4 col-lg-3">
                <select class="form-select" id="bulkBlockSelect">
                    <option value="" selected disabled>Select Block for Selected Students</option>
                    <option value="">Unassign Block</option>
                    @foreach($blocks as $b)
                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-maroon w-100" onclick="submitBulkAssign()">Bulk Assign</button>
            </div>
        </div>
    </div>

    {{-- Bulk assign support fields --}}
    <form id="realBulkForm" method="POST" action="{{ route('admin.block-assignment.bulk') }}" class="d-none">
        @csrf
        <div id="bulkStudentIdsContainer"></div>
        <input type="hidden" name="block_id" id="bulkBlockIdField">
    </form>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="py-3 px-4" style="width: 40px;">
                            <input type="checkbox" class="form-check-input" id="selectAllCheckbox" onclick="toggleSelectAll(this)">
                        </th>
                        <th class="py-3">Student</th>
                        <th class="py-3">Student No.</th>
                        <th class="py-3">Current Block</th>
                        <th class="py-3">Assign To</th>
                        <th class="py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td class="px-4">
                                <input type="checkbox" class="form-check-input student-select-checkbox" value="{{ $student->id }}">
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $student->name }}</span>
                                <div class="text-muted small">{{ strtoupper($student->enrollmentForm?->program ?? 'N/A') }}</div>
                            </td>
                            <td>{{ $student->student_number ?? '—' }}</td>
                            <td>
                                @if($student->enrollment?->block)
                                    <span class="badge badge-maroon bg-maroon">{{ $student->enrollment->block->name }}</span>
                                @else
                                    <span class="badge bg-light text-dark border">Unassigned</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.block-assignment.update', $student) }}" class="d-inline" id="updateForm-{{ $student->id }}">
                                    @csrf
                                    @method('PATCH')
                                    @php
                                        $studentYear = $student->enrollmentForm?->year_level;
                                        $filteredBlocks = $studentYear 
                                            ? $blocks->filter(fn($b) => str_starts_with($b->name, "{$studentYear}-")) 
                                            : $blocks;
                                    @endphp
                                    <select name="block_id" class="form-select form-select-sm" style="max-width: 200px;">
                                        <option value="">Unassigned</option>
                                        @foreach($filteredBlocks as $b)
                                            <option value="{{ $b->id }}" {{ $student->enrollment?->block_id == $b->id ? 'selected' : '' }}>
                                                {{ $b->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td>
                                <button type="submit" form="updateForm-{{ $student->id }}" class="btn btn-sm btn-maroon">Save</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No students found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <span class="text-muted small">Showing {{ $students->firstItem() ?? 0 }} to {{ $students->lastItem() ?? 0 }} of {{ $students->total() }} students</span>
            <div>
                {{ $students->links() }}
            </div>
        </div>
    </div>

    <script>
        function toggleSelectAll(master) {
            const checkboxes = document.querySelectorAll('.student-select-checkbox');
            checkboxes.forEach(cb => cb.checked = master.checked);
        }

        function submitBulkAssign() {
            const selectedCheckboxes = document.querySelectorAll('.student-select-checkbox:checked');
            const bulkBlockId = document.getElementById('bulkBlockSelect').value;

            if (selectedCheckboxes.length === 0) {
                alert('Please select at least one student.');
                return;
            }

            const container = document.getElementById('bulkStudentIdsContainer');
            container.innerHTML = '';
            
            selectedCheckboxes.forEach(cb => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'student_ids[]';
                hiddenInput.value = cb.value;
                container.appendChild(hiddenInput);
            });

            document.getElementById('bulkBlockIdField').value = bulkBlockId;
            document.getElementById('realBulkForm').submit();
        }
    </script>
    @else
    {{-- Student View --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-circle bg-light border d-flex justify-content-center align-items-center flex-shrink-0" style="width:56px;height:56px;">
                    <i class="bi bi-person fs-4 text-muted"></i>
                </div>
                <div>
                    <h5 class="mb-0 text-maroon">{{ auth()->user()->name }}</h5>
                    <span class="text-muted small">
                        {{ strtoupper(auth()->user()->enrollmentForm?->program ?? 'N/A') }}
                        @if(auth()->user()->enrollmentForm?->year_level)
                            , {{ auth()->user()->enrollmentForm->year_level }}{{ match((int)auth()->user()->enrollmentForm->year_level) { 1 => 'st', 2 => 'nd', 3 => 'rd', default => 'th' } }} Year
                        @endif
                    </span>
                </div>
            </div>

            <p class="text-muted small mb-2">Your Assigned Block</p>
            <h3 class="mb-0">
                @if(auth()->user()->enrollment?->block)
                    <span class="badge badge-maroon bg-maroon fs-6 px-3 py-2">{{ auth()->user()->enrollment->block->name }}</span>
                @else
                    <span class="badge bg-light text-dark border fs-6 px-3 py-2">Unassigned</span>
                @endif
            </h3>
            <p class="text-muted small mt-3 mb-0">
                <i class="bi bi-info-circle"></i> Block assignments are managed by the registrar. Contact your program coordinator if you believe this needs to change.
            </p>
        </div>
    </div>
    @endif
</div>
@endsection