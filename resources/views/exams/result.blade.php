@extends('layouts.app')

@section('title', 'Exam Results - ' . $exam->title)

<style>
    body {
        background: #0f172a !important;
        color: #e2e8f0 !important;
    }
    
    .card {
        background-color: #1e293b !important;
        border: 1px solid #475569 !important;
        color: #e2e8f0 !important;
    }
    
    .card-header {
        background-color: #334155 !important;
        color: #e2e8f0 !important;
        border-bottom: 1px solid #475569;
    }
    
    .card-body {
        background-color: #1e293b !important;
        color: #e2e8f0 !important;
    }
    
    .bg-success {
        background-color: #15803d !important;
    }
    
    .bg-warning {
        background-color: #ca8a04 !important;
    }
    
    .bg-danger {
        background-color: #dc2626 !important;
    }
    
    .text-success {
        color: #22c55e !important;
    }
    
    .text-warning {
        color: #fbbf24 !important;
    }
    
    .text-danger {
        color: #ef4444 !important;
    }
    
    .text-info {
        color: #3b82f6 !important;
    }
    
    .border {
        border-color: #475569 !important;
    }
    
    .border-success {
        border-color: #15803d !important;
    }
    
    .border-danger {
        border-color: #dc2626 !important;
    }
    
    .badge {
        background-color: #15803d !important;
        color: white !important;
    }
    
    .badge.bg-success {
        background-color: #15803d !important;
    }
    
    .badge.bg-danger {
        background-color: #dc2626 !important;
    }
    
    .btn-primary {
        background-color: #15803d !important;
        border-color: #15803d !important;
        color: white !important;
        box-shadow: none !important;
    }
    
    .btn-primary:hover {
        background-color: #166534 !important;
        border-color: #166534 !important;
    }
    
    .btn-outline-secondary {
        background-color: #334155 !important;
        border-color: #475569 !important;
        color: #e2e8f0 !important;
    }
    
    .btn-outline-secondary:hover {
        background-color: #475569 !important;
        border-color: #475569 !important;
        color: white !important;
    }
    
    .alert-success {
        background-color: #15803d !important;
        border-color: #15803d !important;
        color: white !important;
    }
    
    .alert-warning {
        background-color: #ca8a04 !important;
        border-color: #ca8a04 !important;
        color: white !important;
    }
    
    .alert-danger {
        background-color: #dc2626 !important;
        border-color: #dc2626 !important;
        color: white !important;
    }
    
    .bg-opacity-10 {
        opacity: 0.2 !important;
    }
    
    h1, h2, h3, h4, h5, h6, p, strong {
        color: #e2e8f0 !important;
    }
    
    .text-muted {
        color: #94a3b8 !important;
    }
    
    .question-text {
        color: #e2e8f0 !important;
    }
    
    .option-item {
        background-color: #334155 !important;
        border: 1px solid #475569 !important;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 8px;
    }
</style>

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Results Summary Card -->
            <div class="card mb-4">
                <div class="card-header bg-{{ $score >= 80 ? 'success' : ($score >= 60 ? 'warning' : 'danger') }} text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-check-circle me-2"></i>Exam Completed: {{ $exam->title }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h2 class="display-4 mb-0 text-{{ $score >= 80 ? 'success' : ($score >= 60 ? 'warning' : 'danger') }}">
                                    {{ $score }}%
                                </h2>
                                <p class="text-muted mb-0">Your Score</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h2 class="display-4 mb-0 text-success">{{ $correctAnswers }}</h2>
                                <p class="text-muted mb-0">Correct Answers</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h2 class="display-4 mb-0 text-danger">{{ $totalQuestions - $correctAnswers }}</h2>
                                <p class="text-muted mb-0">Wrong Answers</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h2 class="display-4 mb-0 text-info">{{ $totalQuestions }}</h2>
                                <p class="text-muted mb-0">Total Questions</p>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-{{ $score >= 80 ? 'success' : ($score >= 60 ? 'warning' : 'danger') }} mt-4">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>
                            @if($score >= 80)
                                Excellent! You have a strong understanding of this topic.
                            @elseif($score >= 60)
                                Good effort! Consider reviewing the topics you missed.
                            @else
                                Keep practicing! Review the material and try again.
                            @endif
                        </strong>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('student.dashboard') }}" class="btn btn-primary me-2">
                            <i class="bi bi-house me-1"></i>Back to Dashboard
                        </a>
                        <a href="{{ route('exams.show', $exam) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-info-circle me-1"></i>View Exam Details
                        </a>
                    </div>
                </div>
            </div>

            <!-- Detailed Results -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-list-check me-2"></i>Detailed Results
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($results as $index => $result)
                        <div class="card mb-4 border-{{ $result['is_correct'] ? 'success' : 'danger' }}">
                            <div class="card-header bg-{{ $result['is_correct'] ? 'success' : 'danger' }} bg-opacity-10">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>Question {{ $index + 1 }}</strong>
                                    <span class="badge bg-{{ $result['is_correct'] ? 'success' : 'danger' }}">
                                        @if($result['is_correct'])
                                            <i class="bi bi-check-circle me-1"></i>Correct
                                        @else
                                            <i class="bi bi-x-circle me-1"></i>Wrong
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="question-text mb-3">
                                    <p class="mb-3">{!! $result['question']->question_text !!}</p>
                                </div>

                                @if($result['question']->image_url)
                                    <div class="mb-3">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#image-{{ $result['question']->id }}">
                                            <i class="bi bi-image me-1"></i>Toggle Image
                                        </button>
                                        <div class="collapse mt-2" id="image-{{ $result['question']->id }}">
                                            <img src="{{ $result['question']->image_url }}" 
                                                 alt="Question Image" 
                                                 class="img-fluid rounded border"
                                                 style="max-height: 400px;">
                                        </div>
                                    </div>
                                @endif

                                <div class="options-container">
                                    <div class="row">
                                        @foreach(['A', 'B', 'C', 'D'] as $option)
                                            @php
                                                $optionField = 'option_' . strtolower($option);
                                                $isCorrect = $result['question']->correct_answer === $option;
                                                $isUserAnswer = $result['user_answer'] === $option;
                                            @endphp
                                            @if($result['question']->$optionField)
                                                <div class="col-md-6 mb-2">
                                                    <div class="option-item p-3 rounded 
                                                        @if($isCorrect) 
                                                            bg-success bg-opacity-10 border border-success
                                                        @elseif($isUserAnswer && !$isCorrect)
                                                            bg-danger bg-opacity-10 border border-danger
                                                        @else 
                                                            bg-light border
                                                        @endif">
                                                        <strong>{{ $option }})</strong> {!! $result['question']->$optionField !!}
                                                        
                                                        @if($isCorrect)
                                                            <span class="badge bg-success ms-2">✓ Correct Answer</span>
                                                        @endif
                                                        
                                                        @if($isUserAnswer && !$isCorrect)
                                                            <span class="badge bg-danger ms-2">✗ Your Answer</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                                @if(!$result['is_correct'])
                                    <div class="alert alert-danger mt-3 mb-0">
                                        <i class="bi bi-x-circle me-2"></i>
                                        <strong>Your answer was incorrect.</strong> 
                                        @if($result['user_answer'])
                                            You selected option {{ $result['user_answer'] }}.
                                        @else
                                            You did not answer this question.
                                        @endif
                                        The correct answer is {{ $result['question']->correct_answer }}.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center my-4">
                <a href="{{ route('student.dashboard') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-house me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize MathJax for LaTeX rendering
    if (typeof MathJax !== 'undefined') {
        MathJax.typesetPromise();
    }
</script>
@endpush
@endsection
