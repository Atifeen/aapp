@extends('layouts.dashboard')

@section('content')
@include('components.student-nav')

<style>
    body {
        background-color: #0f172a !important;
        color: #e2e8f0 !important;
    }

    .card {
        background-color: #1e293b !important;
        border: 2px solid #475569 !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3) !important;
    }

    .table {
        color: #e2e8f0 !important;
        width: 100%;
        background-color: transparent !important;
    }

    .table thead {
        background: linear-gradient(135deg, #334155 0%, #1e293b 100%) !important;
        color: #f1f5f9 !important;
        font-weight: 600;
    }

    .table thead th {
        border-bottom: 2px solid #475569 !important;
        background-color: transparent !important;
    }

    .table tbody {
        background-color: #1e293b !important;
    }

    .table tbody tr {
        border-bottom: 1px solid #334155 !important;
        background-color: #1e293b !important;
    }

    .table tbody tr td {
        background-color: transparent !important;
        color: #f1f5f9 !important;
    }

    .table tbody tr:hover {
        background: linear-gradient(135deg, rgba(51, 65, 85, 0.3) 0%, rgba(30, 41, 59, 0.5) 100%) !important;
        transition: all 0.3s ease;
    }

    .table tbody tr:hover td {
        background-color: transparent !important;
        color: #ffffff !important;
    }

    .table tbody tr td strong {
        color: #ffffff !important;
    }

    .badge {
        font-size: 0.9rem;
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
    }

    .badge.bg-danger {
        background: linear-gradient(135deg, #f43f5e 0%, #dc2626 100%) !important;
        color: white !important;
    }

    .badge.bg-secondary {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%) !important;
        color: white !important;
    }

    .badge.bg-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        color: white !important;
    }

    .badge.bg-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
        color: #0f172a !important;
    }

    .btn-outline-primary {
        color: #10b981 !important;
        border-color: #10b981 !important;
        background-color: transparent !important;
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        color: white !important;
        border-color: #10b981 !important;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
    }

    .btn-outline-secondary {
        color: #94a3b8 !important;
        border-color: #475569 !important;
        background-color: #334155 !important;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background-color: #475569 !important;
        color: #f1f5f9 !important;
        border-color: #64748b !important;
    }

    .alert-info {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(59, 130, 246, 0.05) 100%) !important;
        border: 2px solid #3b82f6 !important;
        border-radius: 10px;
        color: #e2e8f0 !important;
    }

    .card-footer {
        background-color: #1e293b !important;
        border-top: 2px solid #475569 !important;
    }

    h2 {
        color: #f1f5f9 !important;
        font-weight: 700;
    }

    .text-muted {
        color: #cbd5e1 !important;
    }

    .page-link {
        background-color: #334155 !important;
        color: #e2e8f0 !important;
        border: 1px solid #475569 !important;
    }

    .page-link:hover {
        background-color: #475569 !important;
        color: #f1f5f9 !important;
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        border-color: #10b981 !important;
        color: white !important;
    }

    small {
        color: #cbd5e1 !important;
    }
</style>

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
                    <thead>
                        <tr>
                            <th class="px-4 py-3" style="color: #f1f5f9 !important;">Exam</th>
                            <th class="py-3" style="color: #f1f5f9 !important;">Type</th>
                            <th class="py-3" style="color: #f1f5f9 !important;">Score</th>
                            <th class="py-3" style="color: #f1f5f9 !important;">Correct/Total</th>
                            <th class="py-3" style="color: #f1f5f9 !important;">Date</th>
                            <th class="py-3" style="color: #f1f5f9 !important;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attempts as $attempt)
                        <tr>
                            <td class="px-4" style="color: #f1f5f9 !important;">
                                <strong style="color: #ffffff !important;">{{ $attempt->exam->title }}</strong>
                                @if($attempt->exam->subject)
                                    <br><small style="color: #cbd5e1 !important;">{{ $attempt->exam->subject->name }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $attempt->exam->is_rated ? 'danger' : 'secondary' }}">
                                    {{ $attempt->exam->is_rated ? 'Rated' : 'Practice' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge fs-6 bg-{{ $attempt->score >= 80 ? 'success' : ($attempt->score >= 60 ? 'warning' : 'danger') }}">
                                    {{ $attempt->score }}%
                                </span>
                            </td>
                            <td style="color: #cbd5e1 !important;">{{ $attempt->correct_ans }}/{{ $attempt->total_ques }}</td>
                            <td style="color: #cbd5e1 !important;">{{ $attempt->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <a href="{{ route('attempts.result', $attempt) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i>View Results
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $attempts->links('custom-pagination') }}
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
