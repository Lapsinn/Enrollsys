@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="page-title">Student Records</h2>

    @if(auth()->user()->role === 'admin')
    <div class="row mb-4 g-2">
        <div class="col-md-5">
            <input type="text" class="form-control" placeholder="Search student name/ID">
        </div>
        <div class="col-md-3">
            <select class="form-select">
                <option selected disabled>Semester</option>
                <option value="1">1st Semester</option>
                <option value="2">2nd Semester</option>
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select">
                <option selected disabled>A.Y.</option>
                <option value="2024">2024-2025</option>
                <option value="2026">2026-2027</option>
            </select>
        </div>
    </div>
    @else
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body d-flex align-items-center gap-3">
            <div class="rounded-circle bg-light border d-flex justify-content-center align-items-center flex-shrink-0" style="width:48px;height:48px;">
                <i class="bi bi-person fs-5 text-muted"></i>
            </div>
            <div>
                <h5 class="mb-0 text-maroon">{{ auth()->user()->name }}</h5>
                <span class="text-muted small">Student No. {{ auth()->user()->student_number ?? '2026-00123' }}</span>
            </div>
        </div>
    </div>
    @endif

    <div class="row mb-4 text-center">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-light py-3">
                <h6 class="text-muted mb-1">Units this semester</h6>
                <h3 class="mb-0 text-maroon">18</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-light py-3">
                <h6 class="text-muted mb-1">GWA</h6>
                <h3 class="mb-0 text-maroon">1.25</h3>
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
                        <th class="py-3">Instructor</th>
                        <th class="py-3">Units</th>
                        <th class="py-3">Grade</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-4">CMPE 202 - Operating Systems</td>
                        <td>Joshua Garcia</td>
                        <td>3.0</td>
                        <td>1.5</td>
                    </tr>
                    <tr>
                        <td class="px-4">CMPE 203 - Numerical Methods</td>
                        <td>Alvin Aguado</td>
                        <td>4.0</td>
                        <td>1.0</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection