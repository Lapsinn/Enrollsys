@extends('layouts.app')

@section('content')
@if(auth()->user()->role === 'admin')
<div class="container mt-5" x-data="{ noteModalOpen: false, noteText: '', noteStudentId: '', noteStudentName: '', closeModal() { this.noteText = ''; this.noteModalOpen = false; } }">
    <h2 class="page-title">Approval Queue</h2>

    {{-- Flash status message --}}
    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Search form --}}
    <form method="GET" action="{{ route('admin.approval.index') }}" class="row mb-4 g-2 align-items-center">
        <div class="col-md-4">
            <input type="text" 
                   name="q" 
                   class="form-control" 
                   placeholder="Search by student name or ID" 
                   value="{{ request('q') }}">
        </div>
        <div class="col-md-2">
            <select name="program" class="form-select">
                <option value="">All Programs</option>
                <option value="bscs" {{ request('program') == 'bscs' ? 'selected' : '' }}>BSCS</option>
                <option value="bsit" {{ request('program') == 'bsit' ? 'selected' : '' }}>BSIT</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-maroon">Filter</button>
            @if(request('q') || request('program') || request('status'))
                <a href="{{ route('admin.approval.index') }}" class="btn btn-link text-muted">Clear</a>
            @endif
        </div>
    </form>

    <div class="row">
        {{-- Left: Students List --}}
        <div class="col-md-5">
            <div class="card shadow-sm p-3">
                <div class="list-group">
                    @forelse($applications as $student)
                        @php 
                            $form = $student->enrollmentForm; 
                            $isSelected = request('student_id') ? request('student_id') == $student->id : $loop->first;
                        @endphp
                        <a href="{{ route('admin.approval.index', array_merge(request()->query(), ['student_id' => $student->id])) }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $isSelected ? 'active bg-maroon border-maroon' : '' }}">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-light border d-flex justify-content-center align-items-center flex-shrink-0" style="width:36px;height:36px;">
                                    <i class="bi bi-person text-muted"></i>
                                </div>
                                <div class="text-start">
                                    <div class="{{ $isSelected ? 'text-white' : 'text-dark' }} fw-semibold">{{ $student->name }}</div>
                                    <div class="{{ $isSelected ? 'text-white-50' : 'text-muted' }} small">
                                        {{ $form ? 'Submitted ' . $form->updated_at->diffForHumans() : 'No form submitted' }}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-{{ match($form?->status) {
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    'pending'  => 'warning text-dark',
                                    default    => 'secondary',
                                } }}">
                                    {{ $form ? ucfirst($form->status) : 'N/A' }}
                                </span>
                                <span class="btn btn-sm btn-outline-{{ $isSelected ? 'light' : 'secondary' }} p-1 py-0"
                                      role="button"
                                      title="Add Note"
                                      @click.stop.prevent="noteStudentId = '{{ $student->id }}'; noteStudentName = '{{ $student->name }}'; noteModalOpen = true;">
                                    <i class="bi bi-chat-left-text"></i>
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="text-center text-muted py-4">No students found in approval queue.</div>
                    @endforelse
                </div>
                <div class="mt-3">
                    {{ $applications->links() }}
                </div>
            </div>
        </div>

        {{-- Right: Application Detail & Notes --}}
        <div class="col-md-7">
            @php
                $selectedId = request('student_id') ?: ($applications->first()?->id ?? null);
                $selectedStudent = $selectedId ? \App\Models\User::with(['enrollmentForm', 'notes.author'])->find($selectedId) : null;
                $selectedForm = $selectedStudent?->enrollmentForm;
            @endphp

            @if($selectedStudent)
                <div class="card shadow-sm p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <h4 class="text-maroon mb-1">Application Detail</h4>
                        <span class="badge bg-{{ match($selectedForm?->status) {
                            'approved' => 'success',
                            'rejected' => 'danger',
                            'pending'  => 'warning text-dark',
                            default    => 'secondary',
                        } }} fs-6 px-3 py-2">
                            {{ $selectedForm ? ucfirst($selectedForm->status) : 'No Form' }}
                        </span>
                    </div>
                    <h5 class="text-muted mb-3">{{ $selectedStudent->name }} ({{ $selectedStudent->student_number ?? 'No Student Number' }})</h5>
                    <hr>
                    
                    @if($selectedForm)
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <p class="mb-1 text-muted small">Program</p>
                                <p class="fw-semibold mb-3">{{ strtoupper($selectedForm->program) }}</p>
                                
                                <p class="mb-1 text-muted small">Year Level / Semester</p>
                                <p class="fw-semibold mb-3">
                                    {{ $selectedForm->year_level }}{{ match((int)$selectedForm->year_level) { 1 => 'st', 2 => 'nd', 3 => 'rd', default => 'th' } }} Year, 
                                    {{ $selectedForm->semester }}{{ match((int)$selectedForm->semester) { 1 => 'st', 2 => 'nd', default => 'th' } }} Semester
                                </p>
                            </div>
                            <div class="col-sm-6">
                                <p class="mb-1 text-muted small">Applicant Type</p>
                                <p class="fw-semibold mb-3">{{ ucfirst($selectedForm->applicant_type) }}</p>

                                <p class="mb-1 text-muted small">Contact Number</p>
                                <p class="fw-semibold mb-3">{{ $selectedForm->contact_number }}</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <p class="mb-1 text-muted small">Home Address</p>
                            <p class="fw-semibold mb-3">{{ $selectedForm->address }}</p>
                        </div>
                    @else
                        <p class="text-muted my-4">This student has not submitted an enrollment form yet.</p>
                    @endif

                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                        @if($selectedForm && $selectedForm->status === 'pending')
                            <form method="POST" action="{{ route('admin.approval.reject', $selectedStudent) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger">Reject Application</button>
                            </form>
                            <form method="POST" action="{{ route('admin.approval.approve', $selectedStudent) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-maroon">Approve Application</button>
                            </form>
                        @elseif($selectedForm && $selectedForm->status === 'approved')
                            <div class="text-success fw-semibold my-2">
                                <i class="bi bi-check-circle-fill"></i> Approved
                            </div>
                        @elseif($selectedForm && $selectedForm->status === 'rejected')
                            <div class="text-danger fw-semibold my-2">
                                <i class="bi bi-x-circle-fill"></i> Rejected
                            </div>
                        @endif

                        <button class="btn btn-outline-maroon ms-auto" 
                                @click="noteStudentId = '{{ $selectedStudent->id }}'; noteStudentName = '{{ $selectedStudent->name }}'; noteModalOpen = true;">
                            <i class="bi bi-pencil-square"></i> Add Note
                        </button>
                    </div>
                </div>

                {{-- Notes Section --}}
                <div class="card shadow-sm p-4">
                    <h5 class="text-maroon mb-3">Office Notes & Feedback</h5>
                    <div class="list-group list-group-flush">
                        @forelse($selectedStudent->notes as $note)
                            <div class="list-group-item px-0 py-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-semibold">{{ $note->author?->name ?? 'System' }}</span>
                                    <span class="text-muted small">{{ $note->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="text-muted">{{ $note->body }}</div>
                            </div>
                        @empty
                            <p class="text-muted mb-0 small">No notes have been added for this student yet.</p>
                        @endforelse
                    </div>
                </div>

            @else
                <div class="card shadow-sm p-4 text-center text-muted my-5">
                    <i class="bi bi-person-fill-exclamation fs-1 text-maroon mb-2"></i>
                    <p>Select a student from the list to view their application details.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Add Note Modal -->
    <div x-show="noteModalOpen" x-cloak class="position-fixed top-0 start-0 w-100 h-100 align-items-center justify-content-center" :class="noteModalOpen ? 'd-flex' : ''" style="display: none; background:rgba(0,0,0,.5); z-index:1050;" @click.self="closeModal()">
        <div class="card shadow-lg p-4" style="width:480px;">
            <h5 class="text-maroon mb-3">Add Note</h5>
            <form id="noteForm" method="POST" action="{{ route('admin.approval.note') }}">
                @csrf
                <input type="hidden" name="student_id" :value="noteStudentId">
                <div class="mb-3">
                    <label class="form-label text-muted small fw-semibold">For <span class="fw-bold text-dark fs-5" x-text="noteStudentName"></span></label>
                </div>
                <div class="mb-3">
                    <label for="noteText" class="form-label text-muted small fw-semibold">Note / Feedback</label>
                    <textarea id="noteText" x-model="noteText" class="form-control" name="note" rows="4" placeholder="Write a note or instructions for this student..." required></textarea>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary" @click="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-maroon">Save Note</button>
                </div>
            </form>
        </div>
    </div>


</div>
@else
{{-- Student View --}}
<div class="container mt-5">
    <h2 class="page-title">Enrollment Status</h2>

    @php
        $form = auth()->user()->enrollmentForm;
        $notes = auth()->user()->notes()->with('author')->latest()->get();
    @endphp

    <div class="card shadow-sm p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <p class="text-muted small mb-1">Current Application Status</p>
                <h3 class="mb-0">
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
                        <span class="badge bg-secondary fs-6 px-3 py-2">Not Submitted</span>
                    @endif
                </h3>
            </div>
            <div class="text-end">
                <p class="text-muted small mb-1">Last Updated</p>
                <p class="mb-0 fw-semibold">{{ $form ? $form->updated_at->format('M d, Y') : 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="card shadow-sm p-4">
        <h5 class="text-maroon mb-3">Registrar Updates & Feedback</h5>
        <div class="list-group list-group-flush">
            @forelse($notes as $note)
                <div class="list-group-item d-flex gap-3 align-items-start px-0 py-3">
                    <i class="bi bi-info-circle-fill text-maroon fs-5 mt-1"></i>
                    <div class="w-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="fw-semibold">{{ $note->author?->name ?? 'System' }}</div>
                            <span class="text-muted small">{{ $note->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="text-muted small mt-1">{{ $note->body }}</div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-4">No recent updates or notes from the registrar's office.</div>
            @endforelse
        </div>
    </div>
</div>
@endif
@endsection