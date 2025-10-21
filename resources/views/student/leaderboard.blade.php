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
                        <a class="nav-link" href="{{ route('student.dashboard') }}">
                            <i class="bi bi-house-door me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('leaderboard') }}">
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-trophy-fill me-2 text-warning"></i>Leaderboard</h2>
            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Rank</th>
                                <th class="py-3">Name</th>
                                <th class="py-3">Rating</th>
                                <th class="py-3">Max Rating</th>
                                <th class="py-3">Contests Solved</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                            <tr class="{{ $student->id === auth()->id() ? 'table-primary' : '' }}">
                                <td class="px-4">
                                    <strong class="fs-5">#{{ $students->firstItem() + $index }}</strong>
                                    @if($students->firstItem() + $index == 1)
                                        <i class="bi bi-trophy-fill text-warning ms-2"></i>
                                    @elseif($students->firstItem() + $index == 2)
                                        <i class="bi bi-trophy-fill text-secondary ms-2"></i>
                                    @elseif($students->firstItem() + $index == 3)
                                        <i class="bi bi-trophy-fill ms-2" style="color: #CD7F32;"></i>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $student->name }}</strong>
                                    @if($student->id === auth()->id())
                                        <span class="badge bg-primary ms-2">You</span>
                                    @endif
                                </td>
                                <td><strong class="text-primary fs-5">{{ $student->rating }}</strong></td>
                                <td class="text-muted">{{ $student->max_rating }}</td>
                                <td class="text-muted">{{ $student->total_solved }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white">
                {{ $students->links('custom-pagination') }}
            </div>
        </div>
    </div>
@endsection
