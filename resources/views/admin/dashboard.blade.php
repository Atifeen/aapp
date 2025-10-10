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
                    <p class="mb-0 fs-5">
                        <i class="bi bi-shield-check me-2"></i>
                        <span class="text-muted">Role:</span> 
                        <strong>Administrator</strong>
                    </p>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="dashboard-card">
                    <div class="card-body p-4">
                        <h5 class="card-title">Total Exams</h5>
                        <p class="display-6 mb-1">{{ $totalExams }}</p>
                        <small class="text-muted">Available exams</small>
                        <i class="bi bi-file-earmark-text stat-icon text-primary"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card">
                    <div class="card-body p-4">
                        <h5 class="card-title">Rated Exams</h5>
                        <p class="display-6 mb-1">{{ $totalRatedExams }}</p>
                        <small class="text-muted">Rating contests</small>
                        <i class="bi bi-star stat-icon text-warning"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card">
                    <div class="card-body p-4">
                        <h5 class="card-title">Upcoming</h5>
                        <p class="display-6 mb-1">{{ $upcomingExams }}</p>
                        <small class="text-muted">Scheduled exams</small>
                        <i class="bi bi-calendar-event stat-icon text-success"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card">
                    <div class="card-body p-4">
                        <h5 class="card-title">Active Now</h5>
                        <p class="display-6 mb-1">{{ $activeExams }}</p>
                        <small class="text-muted">Currently running</small>
                        <i class="bi bi-play-circle stat-icon text-info"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row g-4">
            <div class="col-12">
                <h4>
                    <i class="bi bi-lightning-charge me-2"></i>Quick Actions
                </h4>
            </div>
            
            <div class="col-md-4">
                <div class="dashboard-card">
                    <div class="card-body p-4 text-center">
                        <i class="bi bi-plus-circle text-primary mb-3" style="font-size: 2.5rem"></i>
                        <h5>Create Exam</h5>
                        <p class="text-muted mb-4">Add a new practice exam or contest</p>
                        <a href="{{ route('exams.create') }}" class="btn btn-primary">Create Now</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="dashboard-card">
                    <div class="card-body p-4 text-center">
                        <i class="bi bi-collection text-success mb-3" style="font-size: 2.5rem"></i>
                        <h5>Manage Questions</h5>
                        <p class="text-muted mb-4">Add or edit question bank</p>
                        <a href="{{ route('questions.index') }}" class="btn btn-success">Manage Now</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="dashboard-card">
                    <div class="card-body p-4 text-center">
                        <i class="bi bi-people text-info mb-3" style="font-size: 2.5rem"></i>
                        <h5>View Students</h5>
                        <p class="text-muted mb-4">Manage student profiles</p>
                        <button class="btn btn-info">View All</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Exams -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>
                        <i class="bi bi-clock-history me-2"></i>Recent Exams
                    </h4>
                    <a href="{{ route('exams.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-list me-1"></i>View All
                    </a>
                </div>
                <div class="dashboard-card">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Subject</th>
                                    <th>Start Time</th>
                                    <th>Duration</th>
                                    <th>Type</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentExams as $exam)
                                    <tr>
                                        <td>{{ $exam->title }}</td>
                                        <td>{{ $exam->subject->name }}</td>
                                        <td>
                                            @if($exam->start_time)
                                                {{ $exam->start_time->format('M d, Y H:i') }}
                                            @else
                                                <span class="text-muted">Not scheduled</span>
                                            @endif
                                        </td>
                                        <td>{{ $exam->duration }} min</td>
                                        <td>
                                            @if($exam->is_rated)
                                                <span class="badge bg-warning">Rated</span>
                                            @else
                                                <span class="badge bg-info">Practice</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('exams.show', $exam) }}" class="btn btn-sm btn-primary" title="View Exam">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('exams.questions.select', $exam) }}" class="btn btn-sm btn-info" title="Manage Questions">
                                                    <i class="bi bi-list-check"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                            <p class="text-muted mb-0">No exams created yet</p>
                                            <a href="{{ route('exams.create') }}" class="btn btn-primary mt-3">
                                                Create Your First Exam
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection