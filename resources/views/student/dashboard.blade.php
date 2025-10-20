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
                        <a class="nav-link active" href="{{ route('student.dashboard') }}">
                            <i class="bi bi-house-door me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('leaderboard') }}">
                            <i class="bi bi-trophy me-1"></i>Leaderboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('exam.history') }}">
                            <i class="bi bi-clock-history me-1"></i>History
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
                </div>
            </div>
        </div>

        <!-- Rating Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card text-center text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body py-4">
                        <h3 class="display-4 fw-bold mb-2">{{ $currentRating }}</h3>
                        <p class="mb-0 text-white-50">Current Rating</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="card-body py-4">
                        <h3 class="display-4 fw-bold mb-2">{{ $maxRating }}</h3>
                        <p class="mb-0 text-white-50">Max Rating</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center text-white" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="card-body py-4">
                        <h3 class="display-4 fw-bold mb-2">#{{ $rank }}</h3>
                        <p class="mb-0 text-white-50">Global Rank</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center text-white" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <div class="card-body py-4">
                        <h3 class="display-4 fw-bold mb-2">{{ $totalSolved }}</h3>
                        <p class="mb-0 text-white-50">Exams Solved</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Attempts -->
        @if($recentAttempts->count() > 0)
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Recent Attempts</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Exam</th>
                                <th>Score</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAttempts as $attempt)
                            <tr>
                                <td>
                                    <strong>{{ $attempt->exam->title }}</strong>
                                    @if($attempt->exam->is_rated)
                                        <span class="badge bg-warning text-dark ms-2">Rated</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge fs-6 bg-{{ $attempt->score >= 80 ? 'success' : ($attempt->score >= 60 ? 'warning' : 'danger') }}">
                                        {{ $attempt->score }}%
                                    </span>
                                </td>
                                <td>{{ $attempt->created_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('exams.show', $attempt->exam) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>Review
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Stats Grid -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="dashboard-card">
                    <div class="card-body p-4">
                        <h5 class="card-title">Total Attempts</h5>
                        <p class="display-6 mb-1">{{ $totalParticipations }}</p>
                        <small class="text-muted">Contests participated</small>
                        <i class="bi bi-trophy stat-icon text-warning"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card">
                    <div class="card-body p-4">
                        <h5 class="card-title">Success Rate</h5>
                        <p class="display-6 mb-1">{{ $successRate }}%</p>
                        <small class="text-muted">Average score</small>
                        <i class="bi bi-check2-circle stat-icon text-success"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card">
                    <div class="card-body p-4">
                        <h5 class="card-title">Available Exams</h5>
                        <p class="display-6 mb-1">{{ $availableExams->count() }}</p>
                        <small class="text-muted">Ready to take</small>
                        <i class="bi bi-file-earmark-text stat-icon text-primary"></i>
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
                                <span class="badge {{ $exam->is_rated ? 'bg-warning text-dark' : 'bg-secondary' }} me-2">
                                    {{ $exam->is_rated ? 'Rated Contest' : 'Practice' }}
                                </span>
                                <span class="badge bg-info me-2">
                                    {{ $exam->duration }} minutes
                                </span>
                                @if($exam->start_time)
                                    @php
                                        $now = now();
                                        $hasStarted = $exam->start_time <= $now;
                                        $endTime = $exam->start_time->copy()->addMinutes($exam->duration);
                                        $hasEnded = $now > $endTime;
                                    @endphp
                                    @if($hasEnded)
                                        <span class="badge bg-secondary">Finished</span>
                                    @elseif($hasStarted)
                                        <span class="badge bg-success">Active Now!</span>
                                    @else
                                        <span class="badge bg-primary">Upcoming</span>
                                    @endif
                                @endif
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-calendar2 me-1"></i>
                                    @if($exam->start_time)
                                        {{ $exam->start_time->format('M d, Y H:i') }}
                                    @else
                                        Always Available
                                    @endif
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