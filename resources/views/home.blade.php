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
                            <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name ?? 'User' }}
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

        <!-- User Stats Section -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="welcome-card">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h2 class="mb-1">Welcome, {{ auth()->user()->name }}!</h2>
                            <p class="mb-0">Keep practicing to improve your rating</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h3 class="mb-1">Rating: {{ auth()->user()->rating }}</h3>
                            <p class="mb-0">Rank: #{{ auth()->user()->rank }} | Max Rating: {{ auth()->user()->max_rating }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title">Total Solved</h5>
                        <p class="card-text display-6">{{ auth()->user()->total_solved }}</p>
                        <i class="bi bi-check-circle stat-icon text-success"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title">Rating Change</h5>
                        @php
                            $ratingChanges = auth()->user()->rating_history ?? [];
                            $lastChange = end($ratingChanges) ? end($ratingChanges)['change'] : 0;
                        @endphp
                        <p class="card-text display-6 {{ $lastChange >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $lastChange >= 0 ? '+' : '' }}{{ $lastChange }}
                        </p>
                        <i class="bi bi-graph-up stat-icon {{ $lastChange >= 0 ? 'text-success' : 'text-danger' }}"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title">Contests Participated</h5>
                        <p class="card-text display-6">{{ auth()->user()->attempts()->whereHas('exam', function($q) { $q->where('is_rated', true); })->count() }}</p>
                        <i class="bi bi-trophy stat-icon text-warning"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title">Success Rate</h5>
                        @php
                            $attempts = auth()->user()->attempts;
                            $successRate = $attempts->count() > 0 
                                ? round(($attempts->sum('correct_ans') / $attempts->sum('total_ques')) * 100, 1)
                                : 0;
                        @endphp
                        <p class="card-text display-6">{{ $successRate }}%</p>
                        <i class="bi bi-bar-chart stat-icon text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Rated Contests -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card dashboard-card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Upcoming Rated Contests</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Contest Name</th>
                                        <th>Start Time</th>
                                        <th>Duration</th>
                                        <th>Difficulty</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $upcomingContests = \App\Models\Exam::where('is_rated', true)
                                            ->where('start_time', '>', now())
                                            ->orderBy('start_time')
                                            ->take(5)
                                            ->get();
                                    @endphp
                                    @forelse($upcomingContests as $contest)
                                        <tr>
                                            <td>{{ $contest->name }}</td>
                                            <td>{{ $contest->start_time->format('Y-m-d H:i') }}</td>
                                            <td>{{ $contest->duration }} mins</td>
                                            <td>
                                                @switch($contest->difficulty_level)
                                                    @case(1)
                                                        <span class="badge bg-success">Easy</span>
                                                        @break
                                                    @case(2)
                                                        <span class="badge bg-warning">Medium</span>
                                                        @break
                                                    @case(3)
                                                        <span class="badge bg-danger">Hard</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-dark">Expert</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-outline-primary">Register</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No upcoming contests</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Practice Exams -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card dashboard-card">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">Board Exam Practice</h4>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @php
                                $boardExams = \App\Models\Exam::where('exam_type', 'board')
                                    ->latest()
                                    ->take(5)
                                    ->get();
                            @endphp
                            @forelse($boardExams as $exam)
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $exam->name }}</h5>
                                        <small>{{ $exam->institution_name }} {{ $exam->year }}</small>
                                    </div>
                                    <p class="mb-1">Questions: {{ $exam->questions->count() }}</p>
                                </a>
                            @empty
                                <div class="text-center py-3">No board exams available</div>
                            @endforelse
                        </div>
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-outline-success">View All Board Exams</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card dashboard-card">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0">University Exam Practice</h4>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @php
                                $universityExams = \App\Models\Exam::where('exam_type', 'university')
                                    ->latest()
                                    ->take(5)
                                    ->get();
                            @endphp
                            @forelse($universityExams as $exam)
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $exam->name }}</h5>
                                        <small>{{ $exam->institution_name }} {{ $exam->year }}</small>
                                    </div>
                                    <p class="mb-1">Questions: {{ $exam->questions->count() }}</p>
                                </a>
                            @empty
                                <div class="text-center py-3">No university exams available</div>
                            @endforelse
                        </div>
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-outline-info">View All University Exams</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Actions -->
        @if(auth()->user()->role === 'admin')

        <!-- Welcome Card -->
        <div class="welcome-card">
            <h1 class="mb-2">
                <i class="bi bi-emoji-smile me-2"></i>
                Welcome back, {{ auth()->user()->name ?? 'User' }}!
            </h1>
            <p class="mb-0 fs-5">
                <i class="bi bi-shield-check me-2"></i>
                Logged in as: <strong>{{ ucfirst(auth()->user()->role ?? 'guest') }}</strong> | 
                <i class="bi bi-book ms-2 me-2"></i>
                Class: <strong>{{ auth()->user()->class ?? 'N/A' }}</strong>
                @if(auth()->user() && auth()->user()->board)
                    | <i class="bi bi-building ms-2 me-2"></i>
                    Board: <strong>{{ auth()->user()->board }}</strong>
                @endif
            </p>
        </div>

        <!-- Dashboard Stats -->
        <div class="row">
            <!-- Total Exams -->
            <div class="col-md-3 mb-4">
                <div class="card dashboard-card">
                    <div class="card-body position-relative">
                        <i class="bi bi-file-earmark-text stat-icon"></i>
                        <h6 class="text-muted text-uppercase mb-2">Total Exams</h6>
                        <h2 class="mb-0 fw-bold text-primary">0</h2>
                        <small class="text-muted">Available to take</small>
                    </div>
                </div>
            </div>

            <!-- Completed -->
            <div class="col-md-3 mb-4">
                <div class="card dashboard-card">
                    <div class="card-body position-relative">
                        <i class="bi bi-check-circle stat-icon"></i>
                        <h6 class="text-muted text-uppercase mb-2">Completed</h6>
                        <h2 class="mb-0 fw-bold text-success">0</h2>
                        <small class="text-muted">Exams finished</small>
                    </div>
                </div>
            </div>

            <!-- Average Score -->
            <div class="col-md-3 mb-4">
                <div class="card dashboard-card">
                    <div class="card-body position-relative">
                        <i class="bi bi-graph-up stat-icon"></i>
                        <h6 class="text-muted text-uppercase mb-2">Average Score</h6>
                        <h2 class="mb-0 fw-bold text-warning">0%</h2>
                        <small class="text-muted">Overall performance</small>
                    </div>
                </div>
            </div>

            <!-- Total Questions -->
            <div class="col-md-3 mb-4">
                <div class="card dashboard-card">
                    <div class="card-body position-relative">
                        <i class="bi bi-question-circle stat-icon"></i>
                        <h6 class="text-muted text-uppercase mb-2">Questions Answered</h6>
                        <h2 class="mb-0 fw-bold text-info">0</h2>
                        <small class="text-muted">Total attempted</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-12">
                <h4 class="mb-3">
                    <i class="bi bi-lightning-charge me-2"></i>Quick Actions
                </h4>
            </div>
            
            @if(auth()->check() && auth()->user()->role === 'student')
                <div class="col-md-4 mb-3">
                    <div class="card dashboard-card">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-pencil-square text-primary" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 mb-2">Take an Exam</h5>
                            <p class="text-muted">Start a new practice exam</p>
                            <button class="btn btn-primary">Start Now</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card dashboard-card">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-clock-history text-success" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 mb-2">View History</h5>
                            <p class="text-muted">See your past attempts</p>
                            <button class="btn btn-success">View History</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card dashboard-card">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-graph-up-arrow text-warning" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 mb-2">Progress Report</h5>
                            <p class="text-muted">Track your improvement</p>
                            <button class="btn btn-warning">View Report</button>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-4 mb-3">
                    <div class="card dashboard-card">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-plus-circle text-primary" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 mb-2">Create Exam</h5>
                            <p class="text-muted">Add a new exam</p>
                            <a href="{{ route('exams.create') }}" class="btn btn-primary">Create</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card dashboard-card">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-collection text-success" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 mb-2">Manage Questions</h5>
                            <p class="text-muted">Add or edit questions</p>
                            <button class="btn btn-success" onclick="window.location='{{ route('questions.index') }}'">Manage</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card dashboard-card">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-people text-info" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 mb-2">View Students</h5>
                            <p class="text-muted">Manage student accounts</p>
                            <button class="btn btn-info">View All</button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // Initialize countdowns for contests
    function updateContestCountdowns() {
        document.querySelectorAll('[data-countdown]').forEach(el => {
            const target = new Date(el.getAttribute('data-countdown')).getTime();
            const now = new Date().getTime();
            const diff = target - now;

            if (diff > 0) {
                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                el.innerHTML = `${days}d ${hours}h ${minutes}m`;
            } else {
                el.innerHTML = 'Started';
            }
        });
    }

    // Update countdowns every minute
    setInterval(updateContestCountdowns, 60000);
    updateContestCountdowns();
</script>
@endpush