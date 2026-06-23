@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="page-title">Subject Enrollment</h2>

    @if(auth()->user()->role === 'admin')
    <div class="row mb-4 g-2">
        <div class="col-md-5">
            <input type="text" class="form-control" placeholder="Search by student name or ID">
        </div>
    </div>
    @endif

    <div class="card shadow-sm p-3 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-light border d-flex justify-content-center align-items-center flex-shrink-0" style="width:56px;height:56px;">
                    <i class="bi bi-person fs-4 text-muted"></i>
                </div>
                <div>
                    <h5 class="mb-0 text-maroon">Juan Dela Cruz</h5>
                    <span class="text-muted small">BS Information Technology, 2nd Year - Block A</span>
                </div>
            </div>
            <div class="text-end">
                <span class="text-muted small">Units Loaded</span>
                <h5 class="mb-0 text-maroon">9 / 21</h5>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column gap-2 mb-4">
        <div class="card shadow-sm p-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <input type="checkbox" class="form-check-input" checked>
                    <div>
                        <div class="fw-semibold">CMPE 202 - Operating Systems</div>
                        <span class="text-muted small">3 units</span>
                    </div>
                </div>
                <select class="form-select form-select-sm" style="width:220px;">
                    <option>Section A · MWF 9-10AM</option>
                    <option>Section B · TTh 1-2:30PM</option>
                </select>
            </div>
        </div>
        <div class="card shadow-sm p-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <input type="checkbox" class="form-check-input" checked>
                    <div>
                        <div class="fw-semibold">CMPE 203 - Numerical Methods</div>
                        <span class="text-muted small">3 units</span>
                    </div>
                </div>
                <select class="form-select form-select-sm" style="width:220px;">
                    <option>Section A · MWF 1-2PM</option>
                    <option>Section B · TTh 9-10:30AM</option>
                </select>
            </div>
        </div>
        <div class="card shadow-sm p-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <input type="checkbox" class="form-check-input">
                    <div class="text-muted">More subjects here</div>
                </div>
                <select class="form-select form-select-sm" style="width:220px;" disabled>
                    <option>Class schedule</option>
                </select>
            </div>
        </div>
    </div>

    <h5 class="text-maroon mb-3">Schedule Preview</h5>
    <div class="card shadow-sm mb-4">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="px-4">Subject</th>
                    <th>Time</th>
                    <th>Room</th>
                    <th>Instructor</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-4">CMPE 202</td>
                    <td>MWF 9:00 - 10:00 AM</td>
                    <td>Room 301</td>
                    <td>Joshua Garcia</td>
                </tr>
                <tr>
                    <td class="px-4">CMPE 203</td>
                    <td>MWF 1:00 - 2:00 PM</td>
                    <td>Room 204</td>
                    <td>Alvin Aguado</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="text-end">
        <button class="btn btn-secondary">Cancel</button>
        <button class="btn btn-maroon">Enroll Subjects</button>
    </div>
</div>
@endsection