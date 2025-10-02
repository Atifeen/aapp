<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - AAPP</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .welcome-card {
            background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }
        .dashboard-card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .stat-icon {
            font-size: 3rem;
            opacity: 0.3;
            position: absolute;
            right: 20px;
            top: 20px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
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
    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

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
                            <button class="btn btn-primary">Create</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card dashboard-card">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-collection text-success" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 mb-2">Manage Questions</h5>
                            <p class="text-muted">Add or edit questions</p>
                            <button class="btn btn-success">Manage</button>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>