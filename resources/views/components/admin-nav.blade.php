<nav class="navbar navbar-expand-lg navbar-dark mb-4" style="background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(10px); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}" style="color: #3b82f6 !important; font-size: 1.5rem;">
            <i class="bi bi-mortarboard-fill me-2"></i>AAPP Admin
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" style="border-color: rgba(255, 255, 255, 0.1);">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard') }}"
                       style="color: {{ request()->routeIs('admin.dashboard') ? '#22c55e' : '#f8fafc' }};">
                        <i class="bi bi-house-door me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('exams.*') ? 'active' : '' }}" 
                       href="{{ route('exams.index') }}"
                       style="color: {{ request()->routeIs('exams.*') ? '#22c55e' : '#f8fafc' }};">
                        <i class="bi bi-file-earmark-text me-1"></i>Exams
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('questions.*') ? 'active' : '' }}" 
                       href="{{ route('questions.index') }}"
                       style="color: {{ request()->routeIs('questions.*') ? '#22c55e' : '#f8fafc' }};">
                        <i class="bi bi-collection me-1"></i>Questions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" 
                       href="{{ route('students.index') }}"
                       style="color: {{ request()->routeIs('students.*') ? '#22c55e' : '#f8fafc' }};">
                        <i class="bi bi-people me-1"></i>Students
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="adminUserDropdown" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false"
                       style="color: #f8fafc;">
                        <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" style="background-color: rgba(30, 41, 59, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1);">
                        <li>
                            <a class="dropdown-item" href="#" style="color: #f8fafc;">
                                <i class="bi bi-person me-2"></i>Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider" style="border-color: rgba(255, 255, 255, 0.1);"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item" style="color: #ef4444;">
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
    .navbar-dark .navbar-nav .nav-link:hover {
        color: #22c55e !important;
    }
    
    .dropdown-menu .dropdown-item:hover {
        background-color: rgba(51, 65, 85, 0.8) !important;
        color: #f8fafc !important;
    }
    
    .navbar-toggler:focus {
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
    }
</style>
