@extends('layouts.dashboard')

@section('content')
@include('components.student-nav')

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-trophy-fill me-2 text-warning"></i>Leaderboard</h2>
            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
            </a>
        </div>
    </div>
@endsection
