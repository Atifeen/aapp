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
                        <a href="{{ route('students.index') }}" class="btn btn-info">View All</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exam Categories -->
        <div class="row mt-5">
            <!-- Board Exams -->
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-mortarboard me-2"></i>Board Exams</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @php
                                $boardExams = \App\Models\Exam::where('exam_type', 'board')
                                    ->with(['subject', 'questions'])
                                    ->latest()
                                    ->take(5)
                                    ->get();
                            @endphp
                            @forelse($boardExams as $exam)
                                <a href="{{ route('exams.show', $exam) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1 fw-bold">{{ $exam->title }}</h6>
                                        @if($exam->start_time)
                                            <small class="text-primary">{{ $exam->start_time->format('M d, Y') }}</small>
                                        @else
                                            <small class="text-muted">No schedule</small>
                                        @endif
                                    </div>
                                    @if($exam->subject)
                                        <p class="mb-1 text-muted small">
                                            <i class="bi bi-book me-1"></i>{{ $exam->subject->name }}
                                        </p>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>{{ $exam->duration }} min | 
                                            <i class="bi bi-question-circle me-1"></i>{{ $exam->questions->count() }} questions
                                        </small>
                                        @if($exam->is_rated)
                                            <span class="badge bg-warning text-dark">Rated</span>
                                        @endif
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-3">
                                    <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                    <p class="text-muted mt-2 mb-0">No board exams</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('exams.index') }}?exam_type=board" class="btn btn-outline-success btn-sm">View All Board Exams</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- University Exams -->
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-building me-2"></i>University Exams</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @php
                                $universityExams = \App\Models\Exam::where('exam_type', 'university')
                                    ->with(['subject', 'questions'])
                                    ->latest()
                                    ->take(5)
                                    ->get();
                            @endphp
                            @forelse($universityExams as $exam)
                                <a href="{{ route('exams.show', $exam) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1 fw-bold">{{ $exam->title }}</h6>
                                        @if($exam->start_time)
                                            <small class="text-primary">{{ $exam->start_time->format('M d, Y') }}</small>
                                        @else
                                            <small class="text-muted">No schedule</small>
                                        @endif
                                    </div>
                                    @if($exam->institution_name || $exam->year)
                                        <p class="mb-1 text-muted small">
                                            <i class="bi bi-bank me-1"></i>{{ $exam->institution_name }} 
                                            @if($exam->year) - {{ $exam->year }} @endif
                                        </p>
                                    @endif
                                    @if($exam->subject)
                                        <p class="mb-1 text-muted small">
                                            <i class="bi bi-book me-1"></i>{{ $exam->subject->name }}
                                        </p>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>{{ $exam->duration }} min | 
                                            <i class="bi bi-question-circle me-1"></i>{{ $exam->questions->count() }} questions
                                        </small>
                                        @if($exam->is_rated)
                                            <span class="badge bg-warning text-dark">Rated</span>
                                        @endif
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-3">
                                    <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                    <p class="text-muted mt-2 mb-0">No university exams</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('exams.index') }}?exam_type=university" class="btn btn-outline-info btn-sm">View All University Exams</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Custom Exams -->
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Custom Exams</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @php
                                $customExams = \App\Models\Exam::where('exam_type', 'custom')
                                    ->with(['subject', 'chapter', 'questions'])
                                    ->latest()
                                    ->take(5)
                                    ->get();
                            @endphp
                            @forelse($customExams as $exam)
                                <a href="{{ route('exams.show', $exam) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1 fw-bold">{{ $exam->title }}</h6>
                                        @if($exam->start_time)
                                            <small class="text-primary">{{ $exam->start_time->format('M d, Y') }}</small>
                                        @else
                                            <small class="text-muted">No schedule</small>
                                        @endif
                                    </div>
                                    @if($exam->subject)
                                        <p class="mb-1 text-muted small">
                                            <i class="bi bi-book me-1"></i>{{ $exam->subject->name }}
                                            @if($exam->chapter) - {{ $exam->chapter->name }} @endif
                                        </p>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>{{ $exam->duration }} min | 
                                            <i class="bi bi-question-circle me-1"></i>{{ $exam->questions->count() }} questions
                                        </small>
                                        @if($exam->is_rated)
                                            <span class="badge bg-warning text-dark">Rated</span>
                                        @endif
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-3">
                                    <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                    <p class="text-muted mt-2 mb-0">No custom exams</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('exams.index') }}?exam_type=custom" class="btn btn-outline-warning btn-sm">View All Custom Exams</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection