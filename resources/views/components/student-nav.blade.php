<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #1e293b;">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ route('student.dashboard') }}">
            <i class="bi bi-mortarboard-fill me-2"></i>AAPP Student
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}" href="{{ route('student.dashboard') }}">
                        <i class="bi bi-house-door me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('leaderboard') ? 'active' : '' }}" href="{{ route('leaderboard') }}">
                        <i class="bi bi-trophy me-1"></i>Leaderboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('exam.history') ? 'active' : '' }}" href="{{ route('exam.history') }}">
                        <i class="bi bi-clock-history me-1"></i>History
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" style="background-color: #334155;">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger" style="background-color: transparent; color: #ef4444 !important;">
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

<style>
    .nav-link {
        color: #e2e8f0 !important;
        transition: color 0.2s;
    }
    
    .nav-link:hover {
        color: #22c55e !important;
    }
    
    .nav-link.active {
        color: #22c55e !important;
        font-weight: 500;
    }
    
    .dropdown-menu {
        border: 1px solid #475569;
    }
    
    .dropdown-item:hover {
        background-color: #475569 !important;
    }
</style>
