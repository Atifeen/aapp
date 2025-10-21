@extends('layouts.dashboard')

@section('content')
@include('components.student-nav')

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
