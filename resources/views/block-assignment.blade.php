@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="page-title">Block Assignment</h2>
    <p class="text-muted mb-4">1st Semester, 2026-2027</p>

    @if(auth()->user()->role === 'admin')
    <div class="row mb-4 g-2">
        <div class="col-md-5">
            <input type="text" class="form-control" placeholder="Search student name">
        </div>
        <div class="col-md-3">
            <select class="form-select">
                <option selected disabled>Program</option>
                <option>BS Information Technology</option>
                <option>BS Computer Science</option>
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select">
                <option selected disabled>Year Level</option>
                <option>1st Year</option>
                <option>2nd Year</option>
                <option>3rd Year</option>
                <option>4th Year</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-maroon w-100">Bulk Assign</button>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="py-3 px-4">Student</th>
                        <th class="py-3">Student No.</th>
                        <th class="py-3">Current Block</th>
                        <th class="py-3">Assign To</th>
                        <th class="py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-4">Juan Dela Cruz</td>
                        <td>2026-00123</td>
                        <td><span class="badge badge-maroon">BSCS - Block A</span></td>
                        <td>
                            <select class="form-select form-select-sm">
                                <option>Block A</option>
                                <option>Block B</option>
                                <option>Block C</option>
                            </select>
                        </td>
                        <td><button class="btn btn-sm btn-maroon">Save</button></td>
                    </tr>
                    <tr>
                        <td class="px-4">Maria Santos</td>
                        <td>2026-00124</td>
                        <td><span class="badge badge-maroon">BSCS - Block B</span></td>
                        <td>
                            <select class="form-select form-select-sm">
                                <option>Block A</option>
                                <option selected>Block B</option>
                                <option>Block C</option>
                            </select>
                        </td>
                        <td><button class="btn btn-sm btn-maroon">Save</button></td>
                    </tr>
                    <tr>
                        <td class="px-4">Mark Reyes</td>
                        <td>2026-00125</td>
                        <td><span class="badge bg-light text-dark border">Unassigned</span></td>
                        <td>
                            <select class="form-select form-select-sm">
                                <option selected disabled>Select block</option>
                                <option>Block A</option>
                                <option>Block B</option>
                                <option>Block C</option>
                            </select>
                        </td>
                        <td><button class="btn btn-sm btn-maroon">Save</button></td>
                    </tr>
                    <tr>
                        <td class="px-4">Angela Cruz</td>
                        <td>2026-00126</td>
                        <td><span class="badge badge-maroon">BSCS - Block A</span></td>
                        <td>
                            <select class="form-select form-select-sm">
                                <option selected>Block A</option>
                                <option>Block B</option>
                                <option>Block C</option>
                            </select>
                        </td>
                        <td><button class="btn btn-sm btn-maroon">Save</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <span class="text-muted small">Showing 4 of 45 students</span>
            <div class="btn-group">
                <button class="btn btn-sm btn-outline-maroon">&laquo;</button>
                <button class="btn btn-sm btn-outline-maroon">&raquo;</button>
            </div>
        </div>
    </div>
    @else
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-circle bg-light border d-flex justify-content-center align-items-center flex-shrink-0" style="width:56px;height:56px;">
                    <i class="bi bi-person fs-4 text-muted"></i>
                </div>
                <div>
                    <h5 class="mb-0 text-maroon">{{ auth()->user()->name }}</h5>
                    <span class="text-muted small">BS Computer Science, 3rd Year</span>
                </div>
            </div>

            <p class="text-muted small mb-2">Your Assigned Block</p>
            <h3 class="mb-0"><span class="badge badge-maroon fs-6 px-3 py-2">BSCS - Block B</span></h3>
            <p class="text-muted small mt-3 mb-0">
                <i class="bi bi-info-circle"></i> Block assignments are managed by the registrar. Contact your program coordinator if you believe this needs to change.
            </p>
        </div>
    </div>
    @endif
</div>
@endsection