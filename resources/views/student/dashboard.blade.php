@extends('layouts.dashboard')

@section('content')
@include('components.student-nav')

<div class="container py-4">
    <!-- Welcome Card -->
    <div class="card shadow-sm mb-4" style="background-color: #1e293b; border: none;">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="text-white mb-0">
                        <i class="bi bi-emoji-smile me-2"></i>Welcome back, {{ auth()->user()->name }}!
                    </h2>
                </div>
                <div class="col-md-4 text-end">
                    <span class="badge bg-secondary px-3 py-2">
                        <i class="bi bi-shield-check me-2"></i>Student
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Quick Action Cards Row 1 -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card h-100 action-card" style="background-color: #1e293b; border: none;">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-book" style="font-size: 4rem; color: #16a34a;"></i>
                    </div>
                    <h4 class="text-white mb-4">Board Exams</h4>
                    <a href="{{ route('exams.index', ['exam_type' => 'board']) }}" class="btn btn-success px-4 py-2">
                        <i class="bi bi-arrow-right-circle me-2"></i>View Board Exams
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100 action-card" style="background-color: #1e293b; border: none;">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-building" style="font-size: 4rem; color: #16a34a;"></i>
                    </div>
                    <h4 class="text-white mb-4">University Exams</h4>
                    <a href="{{ route('exams.index', ['exam_type' => 'university']) }}" class="btn btn-success px-4 py-2">
                        <i class="bi bi-arrow-right-circle me-2"></i>View University Exams
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100 action-card" style="background-color: #1e293b; border: none;">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-gear" style="font-size: 4rem; color: #16a34a;"></i>
                    </div>
                    <h4 class="text-white mb-4">Custom Exams</h4>
                    <a href="{{ route('exams.index', ['exam_type' => 'custom']) }}" class="btn btn-success px-4 py-2">
                        <i class="bi bi-arrow-right-circle me-2"></i>View Custom Exams
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Row 2 -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card h-100 action-card" style="background-color: #1e293b; border: none;">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-trophy" style="font-size: 4rem; color: #16a34a;"></i>
                    </div>
                    <h4 class="text-white mb-4">Leaderboard</h4>
                    <a href="{{ route('leaderboard') }}" class="btn btn-success px-4 py-2">
                        <i class="bi bi-arrow-right-circle me-2"></i>View Rankings
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100 action-card" style="background-color: #1e293b; border: none;">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-clock-history" style="font-size: 4rem; color: #16a34a;"></i>
                    </div>
                    <h4 class="text-white mb-4">Exam History</h4>
                    <a href="{{ route('exam.history') }}" class="btn btn-success px-4 py-2">
                        <i class="bi bi-arrow-right-circle me-2"></i>View History
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100 action-card" style="background-color: #1e293b; border: none;">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-list-check" style="font-size: 4rem; color: #16a34a;"></i>
                    </div>
                    <h4 class="text-white mb-4">All Exams</h4>
                    <a href="{{ route('exams.index') }}" class="btn btn-success px-4 py-2">
                        <i class="bi bi-arrow-right-circle me-2"></i>Browse All
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #0f172a;
    }
    
    .action-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }
    
    .action-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.5);
    }
    
    .btn {
        transition: all 0.2s ease;
        font-weight: 600;
    }
    
    .btn:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }
    
    .btn-success {
        background-color: #16a34a;
        border-color: #16a34a;
    }
    
    .btn-success:hover {
        background-color: #15803d;
        border-color: #15803d;
    }
</style>
@endsection
