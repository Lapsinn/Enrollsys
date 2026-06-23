@extends('layouts.app')

@section('content')
@if(auth()->user()->role === 'admin')
<div class="container mt-5" x-data="{ noteModalOpen: false, noteText: '', noteTarget: '' }">
    <h2 class="page-title">Approval Queue</h2>

    <div class="row mb-4 g-2">
        <div class="col-md-3">
            <input type="text" class="form-control" placeholder="Search by student name or ID">
        </div>
        <div class="col-md-3">
            <select class="form-select">
                <option selected disabled>Program</option>
                <option>BS Information Technology</option>
                <option>BS Computer Science</option>
                <option>BS Computer Engineering</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select">
                <option selected disabled>Status</option>
                <option>Pending</option>
                <option>Approved</option>
                <option>Rejected</option>
            </select>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4"><div class="card p-3 shadow-sm text-center"><h6 class="text-muted mb-1">Pending</h6><h3 class="mb-0 text-maroon">12</h3></div></div>
        <div class="col-md-4"><div class="card p-3 shadow-sm text-center"><h6 class="text-muted mb-1">Approved Today</h6><h3 class="mb-0 text-maroon">5</h3></div></div>
        <div class="col-md-4"><div class="card p-3 shadow-sm text-center"><h6 class="text-muted mb-1">Rejected</h6><h3 class="mb-0 text-maroon">2</h3></div></div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm p-3">
                <div class="list-group">
                    <button class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-light border d-flex justify-content-center align-items-center flex-shrink-0" style="width:36px;height:36px;">
                                <i class="bi bi-person text-muted"></i>
                            </div>
                            <div class="text-start">
                                <div>Juan Dela Cruz</div>
                                <div class="text-muted small">Submitted, June 12 2026</div>
                            </div>
                        </div>
                        <span class="badge bg-warning text-dark">Pending</span>
                    </button>
                    <button class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-light border d-flex justify-content-center align-items-center flex-shrink-0" style="width:36px;height:36px;">
                                <i class="bi bi-person text-muted"></i>
                            </div>
                            <div class="text-start">
                                <div>Mark Reyes</div>
                                <div class="text-muted small">Submitted, June 11 2026</div>
                            </div>
                        </div>
                        <span class="badge bg-warning text-dark">Pending</span>
                    </button>
                    <button class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-light border d-flex justify-content-center align-items-center flex-shrink-0" style="width:36px;height:36px;">
                                <i class="bi bi-person text-muted"></i>
                            </div>
                            <div class="text-start">
                                <div>Maria Santos</div>
                                <div class="text-muted small">Submitted, June 10 2026</div>
                            </div>
                        </div>
                        <span class="badge bg-success">Approved</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm p-4">
                <h4 class="text-maroon">Application Detail</h4>
                <hr>
                <p class="mb-2"><strong>Program:</strong> BSCS</p>
                <p class="mb-2"><strong>Year Level:</strong> 3rd Year</p>
                <p class="mb-2"><strong>Requested Block:</strong> Block A</p>
                <p class="mb-0"><strong>Documents:</strong> <a href="#" class="text-maroon">Transcript_of_Records.pdf</a></p>
                <div class="d-flex gap-2 mt-4">
                    <button class="btn btn-outline-danger">Reject</button>
                    <button class="btn btn-maroon">Approve</button>
                    <button class="btn btn-outline-maroon" @click="noteModalOpen = true; noteTarget = 'Juan Dela Cruz'">
                        <i class="bi bi-pencil-square"></i> Add Note
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Note Modal -->
    <div x-show="noteModalOpen" x-cloak class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background:rgba(0,0,0,.5); z-index:1050;" @click.self="noteModalOpen = false">
        <div class="card shadow-lg p-4" style="width:480px;">
            <h5 class="text-maroon mb-1">Add Note</h5>
            <p class="text-muted small mb-3">For <span x-text="noteTarget"></span></p>
            <textarea class="form-control mb-3" rows="4" placeholder="Write a note for this student..." x-model="noteText"></textarea>
            <div class="d-flex justify-content-end gap-2">
                <button class="btn btn-secondary" @click="noteModalOpen = false; noteText = ''">Cancel</button>
                <button class="btn btn-maroon" @click="noteModalOpen = false; noteText = ''">Save Note</button>
            </div>
        </div>
    </div>
</div>
@else
<div class="container mt-5">
    <h2 class="page-title">Enrollment Status</h2>

    <div class="card shadow-sm p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <p class="text-muted small mb-1">Current Application Status</p>
                <h3 class="mb-0">
                    <span class="badge bg-warning text-dark fs-6 px-3 py-2">Pending</span>
                </h3>
            </div>
            <div class="text-end">
                <p class="text-muted small mb-1">Submitted</p>
                <p class="mb-0 fw-semibold">June 12, 2026</p>
            </div>
        </div>
    </div>

    <div class="card shadow-sm p-4">
        <h5 class="text-maroon mb-3">Notifications</h5>
        <div class="list-group list-group-flush">
            <div class="list-group-item d-flex gap-3 align-items-start px-0">
                <i class="bi bi-exclamation-circle-fill text-warning fs-5"></i>
                <div>
                    <div class="fw-semibold">Missing document</div>
                    <div class="text-muted small">Please upload your Transcript of Records to complete your application.</div>
                </div>
            </div>
            <div class="list-group-item d-flex gap-3 align-items-start px-0">
                <i class="bi bi-info-circle-fill text-maroon fs-5"></i>
                <div>
                    <div class="fw-semibold">Application under review</div>
                    <div class="text-muted small">Your enrollment form is being reviewed by the registrar's office.</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection