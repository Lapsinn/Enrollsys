@extends('layouts.app')

@section('content')
<div class="hero-band rounded-4 p-5 mb-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="display-3 fw-bold">Enroll now!</h1>
            <p class="lead mb-4" style="color:#f0d9da;">Apply, upload documents, and track your enrollment status — all in one secure portal.</p>
            <a href="/login" class="btn btn-light btn-lg px-4 fw-semibold text-maroon">Enroll now</a>
        </div>
        <div class="col-md-6">
            <div class="bg-white bg-opacity-10 border border-light border-opacity-25 rounded d-flex justify-content-center align-items-center" style="height: 300px;">
                <span style="color:#f0d9da;">Main Illustration/Image</span>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card p-4 h-100 border">
                <div class="d-flex justify-content-center align-items-center bg-maroon text-white rounded-3 mb-3" style="width: 44px; height: 44px;">
                    <i class="bi bi-laptop fs-5"></i>
                </div>
                <h5 class="text-maroon">Apply Online</h5>
                <p class="small text-muted mb-0">Fill out the enrollment form from any device.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 h-100 border">
                <div class="d-flex justify-content-center align-items-center bg-maroon text-white rounded-3 mb-3" style="width: 44px; height: 44px;">
                    <i class="bi bi-cloud-upload fs-5"></i>
                </div>
                <h5 class="text-maroon">Upload Documents</h5>
                <p class="small text-muted mb-0">Submit records and forms securely.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 h-100 border">
                <div class="d-flex justify-content-center align-items-center bg-maroon text-white rounded-3 mb-3" style="width: 44px; height: 44px;">
                    <i class="bi bi-bar-chart-steps fs-5"></i>
                </div>
                <h5 class="text-maroon">Track Status</h5>
                <p class="small text-muted mb-0">See real-time updates on your application.</p>
            </div>
        </div>
    </div>
</div>
@endsection