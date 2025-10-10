@extends('layouts.dashboard')

@section('content')
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-mortarboard-fill me-2"></i>AAPP
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="bi bi-house-door me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                            <span class="badge bg-warning ms-2">{{ auth()->user()->rating }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-person me-2"></i>Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

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
                    <div class="d-flex align-items-center gap-4">
                        <p class="mb-0 fs-5">
                            <i class="bi bi-graph-up me-2"></i>
                            <span class="text-muted">Rating:</span> 
                            <strong>{{ auth()->user()->rating }}</strong>
                        </p>
                        <p class="mb-0 fs-5">
                            <i class="bi bi-award me-2"></i>
                            <span class="text-muted">Max Rating:</span> 
                            <strong>{{ auth()->user()->max_rating }}</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="dashboard-card">
                    <div class="card-body p-4">
                        <h5 class="card-title">Contests</h5>
                        <p class="display-6 mb-1">{{ $totalParticipations }}</p>
                        <small class="text-muted">Contests participated</small>
                        <i class="bi bi-trophy stat-icon text-warning"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card">
                    <div class="card-body p-4">
                        <h5 class="card-title">Success Rate</h5>
                        <p class="display-6 mb-1">{{ $successRate }}%</p>
                        <small class="text-muted">Average score</small>
                        <i class="bi bi-check2-circle stat-icon text-success"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card">
                    <div class="card-body p-4">
                        <h5 class="card-title">Next Exam</h5>
                        <p class="display-6 mb-1">{{ $nextExamTime ?? 'None' }}</p>
                        <small class="text-muted">Upcoming exam</small>
                        <i class="bi bi-calendar-event stat-icon text-primary"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card">
                    <div class="card-body p-4">
                        <h5 class="card-title">Rank</h5>
                        <p class="display-6 mb-1">#{{ $rank }}</p>
                        <small class="text-muted">Current standing</small>
                        <i class="bi bi-bar-chart stat-icon text-info"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Exams -->
        <div class="row g-4">
            <div class="col-12">
                <h4>
                    <i class="bi bi-file-earmark-text me-2"></i>Available Exams
                </h4>
            </div>

            @forelse ($availableExams as $exam)
                <div class="col-md-4">
                    <div class="dashboard-card h-100">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3">{{ $exam->title }}</h5>
                            <div class="mb-3">
                                <span class="badge {{ $exam->is_rated ? 'bg-warning' : 'bg-secondary' }} me-2">
                                    {{ $exam->is_rated ? 'Rated' : 'Practice' }}
                                </span>
                                <span class="badge bg-info">
                                    {{ $exam->duration }} minutes
                                </span>
                            </div>
                            <p class="text-muted mb-4">{{ Str::limit($exam->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-calendar2 me-1"></i>
                                    {{ $exam->start_time->format('M d, Y H:i') }}
                                </small>
                                <a href="{{ route('exams.show', $exam) }}" class="btn btn-primary btn-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        No exams available at the moment.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection