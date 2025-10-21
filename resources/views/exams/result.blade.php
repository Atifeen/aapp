@extends('layouts.app')

@section('title', 'Exam Results - ' . $exam->title)

<style>
    :root {
        --accent-green: #10b981;
        --accent-red: #f43f5e;
    }

    body {
        background: #0f172a !important;
        color: #e2e8f0 !important;
    }
    
    .card {
        background-color: #1e293b !important;
        border: 1px solid #475569 !important;
        border-radius: 12px !important;
        color: #e2e8f0 !important;
    }
    
    .card-header {
        background-color: #334155 !important;
        color: #e2e8f0 !important;
        border-bottom: 1px solid #475569;
        border-radius: 12px 12px 0 0 !important;
    }
    
    .card-body {
        background-color: #1e293b !important;
        color: #e2e8f0 !important;
    }
    
    .score-card {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        border: 2px solid #475569;
        border-radius: 12px;
        padding: 1.5rem;
        transition: transform 0.3s ease;
    }
    
    .score-card:hover {
        transform: translateY(-5px);
        border-color: #64748b;
    }
    
    .score-number {
        font-size: 3rem;
        font-weight: 700;
        line-height: 1;
    }
    
    .text-success-custom {
        color: var(--accent-green) !important;
    }
    
    .text-danger-custom {
        color: var(--accent-red) !important;
    }
    
    .text-warning-custom {
        color: #fbbf24 !important;
    }
    
    .text-info-custom {
        color: #3b82f6 !important;
    }
    
    .badge {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
    }
    
    .bg-success-custom {
        background-color: var(--accent-green) !important;
    }
    
    .bg-danger-custom {
        background-color: var(--accent-red) !important;
    }

    .bg-info-custom {
        background-color: #3b82f6 !important;
    }
    
    .btn-primary {
        background-color: var(--accent-green) !important;
        border-color: var(--accent-green) !important;
        border-radius: 8px;
        padding: 0.65rem 1.5rem;
    }
    
    .btn-primary:hover {
        background-color: #059669 !important;
    }
    
    .btn-outline-secondary {
        background-color: #334155 !important;
        border-color: #475569 !important;
        color: #e2e8f0 !important;
        border-radius: 8px;
        padding: 0.65rem 1.5rem;
    }
    
    .btn-outline-secondary:hover {
        background-color: #475569 !important;
    }
    
    .question-card {
        background: #1e293b;
        border-radius: 12px;
        border: 1px solid #475569;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .question-header {
        padding: 1rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #334155;
        border-bottom: 1px solid #475569;
    }
    
    .option-box {
        background-color: #334155;
        border: 2px solid #475569;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }
    
    .option-box.correct {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(16, 185, 129, 0.05) 100%);
        border-color: var(--accent-green);
        border-width: 2px;
    }
    
    .option-box.incorrect {
        background: linear-gradient(135deg, rgba(244, 63, 94, 0.15) 0%, rgba(244, 63, 94, 0.05) 100%);
        border-color: var(--accent-red);
        border-width: 2px;
    }
    
    .result-summary {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        border: 1px solid #475569;
    }
    
    h1, h2, h3, h4, h5, h6, p, strong {
        color: #e2e8f0 !important;
    }
    
    .text-muted {
        color: #94a3b8 !important;
    }
</style>

@section('content')
<script>
MathJax = {
  tex: {
    inlineMath: [['\\(', '\\)'], ['$', '$']],
    displayMath: [['\\[', '\\]'], ['$$', '$$']]
  }
};
</script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

<div class="container-fluid py-4 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Results Summary -->
            <div class="result-summary mb-4">
           
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="score-card text-center">
                            <div class="score-number {{ $score >= 80 ? 'text-success-custom' : ($score >= 60 ? 'text-warning-custom' : 'text-danger-custom') }}">
                                {{ $score }}%
                            </div>
                            <p class="text-muted mb-0 mt-2">Your Score</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="score-card text-center">
                            <div class="score-number text-success-custom">{{ $correctAnswers }}</div>
                            <p class="text-muted mb-0 mt-2">Correct</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="score-card text-center">
                            <div class="score-number text-danger-custom">{{ $totalQuestions - $correctAnswers }}</div>
                            <p class="text-muted mb-0 mt-2">Wrong</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="score-card text-center">
                            <div class="score-number text-info-custom">{{ $totalQuestions }}</div>
                            <p class="text-muted mb-0 mt-2">Total</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Results -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Detailed Results</h5>
                </div>
                <div class="card-body">
                    @foreach($results as $index => $result)
                        <div class="question-card">
                            <div class="question-header">
                                <strong>Question {{ $index + 1 }}</strong>
                                @if($result['is_correct'])
                                    <span class="badge bg-success-custom">
                                        <i class="bi bi-check-circle me-1"></i>Correct
                                    </span>
                                @elseif($result['user_answer'])
                                    <span class="badge bg-danger-custom" style = "background-color: var(--accent-red) !important;">
                                        <i class="bi bi-x-circle me-1"></i>Wrong
                                    </span>
                                @else
                                    <span class="badge bg-info-custom" style = "background-color: #3b82f6 !important;">
                                        <i class="bi bi-dash-circle me-1"></i>Skipped
                                    </span>
                                @endif
                            </div>
                            <div class="p-4">
                                <div class="mb-3">
                                    <p>{!! str_replace(['\\(', '\\)', '\\[', '\\]'], ['\(', '\)', '\[', '\]'], html_entity_decode($result['question']->question_text)) !!}</p>
                                </div>

                                @if($result['question']->image || $result['question']->image_url)
                                    <div class="mb-3">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#image-{{ $result['question']->id }}">
                                            <i class="bi bi-image me-1"></i>View Image
                                        </button>
                                        <div class="collapse mt-2" id="image-{{ $result['question']->id }}">
                                            <img src="{{ $result['question']->image ?? $result['question']->image_url }}" 
                                                 alt="Question Image" 
                                                 class="img-fluid rounded"
                                                 style="max-height: 400px;">
                                        </div>
                                    </div>
                                @endif

                                <div class="row g-3">
                                    @foreach(['A', 'B', 'C', 'D'] as $option)
                                        @php
                                            $optionField = 'option_' . strtolower($option);
                                            $isCorrect = strtoupper($result['question']->correct_option) === $option || $result['question']->correct_answer === $option;
                                            $isUserAnswer = $result['user_answer'] === $option;
                                        @endphp
                                        @if($result['question']->$optionField)
                                            <div class="col-md-6">
                                                <div class="option-box {{ $isCorrect ? 'correct' : ($isUserAnswer ? 'incorrect' : '') }}">
                                                    <div class="d-flex align-items-start">
                                                        <div class="flex-shrink-0 me-2">
                                                            <strong>{{ $option }})</strong>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            {!! str_replace(['\\(', '\\)', '\\[', '\\]'], ['\(', '\)', '\[', '\]'], html_entity_decode($result['question']->$optionField)) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.addEventListener('load', function() {
        if (typeof MathJax !== 'undefined') {
            setTimeout(function() {
                MathJax.typesetPromise();
            }, 500);
        }
    });
</script>
@endpush
@endsection