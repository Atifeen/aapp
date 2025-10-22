@extends('layouts.dashboard')

@section('content')
    <!-- Navigation -->
    @include('components.admin-nav')

    <!-- Main Content -->
    <div class="container py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Welcome Card -->
        <div class="welcome-card mb-4">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <h2 class="mb-3">
                        <i class="bi bi-emoji-smile me-2"></i>Welcome back, {{ auth()->user()->name }}!
                    </h2>
                    <p class="mb-0 fs-5">
                        <i class="bi bi-shield-check me-2"></i>
                        <span class="text-muted">Role:</span> 
                        <strong>Administrator</strong>
                    </p>
                </div>
            </div>
        </div>
 
        <div class="row g-4">
            <div class="col-md-4">
                <div class="dashboard-card">
                    <div class="card-body p-4 text-center">
                        <i class="bi bi-plus-circle mb-3" style="font-size: 2.5rem; color: #16a34a;"></i>
                        <h5>Create Exam</h5>
                        <p class="text-muted mb-4">Add a new practice exam or contest</p>
                        <a href="{{ route('exams.create') }}" class="btn btn-success">Create Now</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="dashboard-card">
                    <div class="card-body p-4 text-center">
                        <i class="bi bi-collection mb-3" style="font-size: 2.5rem; color: #16a34a;"></i>
                        <h5>Manage Questions</h5>
                        <p class="text-muted mb-4">Add or edit question bank</p>
                        <a href="{{ route('questions.index') }}" class="btn btn-success">Manage Now</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="dashboard-card">
                    <div class="card-body p-4 text-center">
                        <i class="bi bi-people mb-3" style="font-size: 2.5rem; color: #16a34a;"></i>
                        <h5>View Students</h5>
                        <p class="text-muted mb-4">Manage student profiles</p>
                        <a href="{{ route('students.index') }}" class="btn btn-success">View All</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- View All Exams Section -->
        <div class="row mt-5">
       
            <!-- Board Exams -->
            <div class="col-md-4">
                <div class="dashboard-card">
              
                    <div class="card-body text-center p-5">
                        <i class="bi bi-folder2-open mb-3" style="font-size: 3rem; color: #16a34a;"></i>
                        <p class="text-muted mb-4">View and manage all board examination papers</p>
                        <a href="{{ route('exams.index') }}?exam_type=board" class="btn btn-success">View All Board Exams</a>
                    </div>
                </div>
            </div>

            <!-- University Exams -->
            <div class="col-md-4">
                <div class="dashboard-card">
                    <!-- <div class="card-header text-white py-3 px-4" style="background-color: #334155; border: none;">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-building me-2"></i>University Exams</h5>
                    </div> -->
                    <div class="card-body text-center p-5">
                        <i class="bi bi-folder2-open mb-3" style="font-size: 3rem; color: #16a34a;"></i>
                        <p class="text-muted mb-4">View and manage all university admission tests</p>
                        <a href="{{ route('exams.index') }}?exam_type=university" class="btn btn-success">View All University Exams</a>
                    </div>
                </div>
            </div>

            <!-- Custom Exams -->
            <div class="col-md-4">
                <div class="dashboard-card">
                    <!-- <div class="card-header text-white py-3 px-4" style="background-color: #334155; border: none;">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-gear me-2"></i>Custom Exams</h5>
                    </div> -->
                    <div class="card-body text-center p-5">
                        <i class="bi bi-folder2-open mb-3" style="font-size: 3rem; color: #16a34a;"></i>
                        <p class="text-muted mb-4">View and manage all custom questions exam....</p>
                        <a href="{{ route('exams.index') }}?exam_type=custom" class="btn btn-success">View All Custom Exams</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<style>
    .btn-success {
        background-color: #15803d !important;
        border-color: #15803d !important;
        box-shadow: none !important;
    }
    .btn-success:hover {
        background-color: #166534 !important;
        border-color: #166534 !important;
        box-shadow: none !important;
    }
    .btn-success:focus, .btn-success:active {
        background-color: #166534 !important;
        border-color: #166534 !important;
        box-shadow: none !important;
    }
</style>
@endsection