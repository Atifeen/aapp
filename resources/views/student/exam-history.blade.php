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
                        <a class="nav-link" href="{{ route('leaderboard') }}">
                            <i class="bi bi-trophy me-1"></i>Leaderboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('exam.history') }}">
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
            <h2><i class="bi bi-clock-history me-2"></i>Exam History</h2>
            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
            </a>
        </div>

        @if($attempts->count() > 0)
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Exam</th>
                                <th class="py-3">Type</th>
                                <th class="py-3">Score</th>
                                <th class="py-3">Correct/Total</th>
                                <th class="py-3">Date</th>
                                <th class="py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attempts as $attempt)
                            <tr>
                                <td class="px-4">
                                    <strong>{{ $attempt->exam->title }}</strong>
                                    @if($attempt->exam->subject)
                                        <br><small class="text-muted">{{ $attempt->exam->subject->name }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $attempt->exam->is_rated ? 'danger' : 'secondary' }}">
                                        {{ $attempt->exam->is_rated ? 'Rated' : 'Practice' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge fs-6 bg-{{ $attempt->score >= 80 ? 'success' : ($attempt->score >= 60 ? 'warning text-dark' : 'danger') }}">
                                        {{ $attempt->score }}%
                                    </span>
                                </td>
                                <td class="text-muted">{{ $attempt->correct_ans }}/{{ $attempt->total_ques }}</td>
                                <td class="text-muted">{{ $attempt->created_at->format('M d, Y H:i') }}</td>
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
            <div class="card-footer bg-white">
                {{ $attempts->links() }}
            </div>
        </div>
        @else
        <div class="alert alert-info shadow-sm">
            <i class="bi bi-info-circle me-2"></i>
            You haven't taken any recorded exams yet. Take a <strong>rated contest</strong> or <strong>classroom exam</strong> to see your history here.
            <hr class="my-3">
            <p class="mb-0 small"><strong>Note:</strong> Board exams and university practice exams are not recorded in your history.</p>
        </div>
        @endif
    </div>
@endsection
