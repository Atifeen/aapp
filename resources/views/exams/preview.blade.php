@extends('layouts.app')

@section('title', 'Preview - ' . $exam->title)

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
    
    .card-footer {
        background-color: #1e293b !important;
        border-top: 1px solid #475569;
    }
    
    .badge {
        background-color: #15803d !important;
        color: white !important;
    }
    
    .btn-primary, .btn-success, .btn-info {
        background-color: #15803d !important;
        border-color: #15803d !important;
        color: white !important;
    }
    
    .btn-primary:hover, .btn-success:hover, .btn-info:hover {
        background-color: #166534 !important;
    }
    
    .btn-outline-secondary {
        background-color: #334155 !important;
        border-color: #475569 !important;
        color: #e2e8f0 !important;
    }
    
    .btn-outline-secondary:hover {
        background-color: #475569 !important;
        color: white !important;
    }
    
    .alert-info {
        background-color: #334155 !important;
        border-color: #475569 !important;
        color: #e2e8f0 !important;
    }
    
    .alert-warning {
        background-color: #92400e !important;
        border-color: #b45309 !important;
        color: #fef3c7 !important;
    }
    
    .option-item {
        background-color: #1e293b !important;
        border: 1px solid #475569 !important;
        color: #e2e8f0 !important;
    }
    
    .option-item.correct {
        background-color: #166534 !important;
        border: 2px solid #15803d !important;
    }
    
    h1, h2, h3, h4, h5, h6, p, strong {
        color: #e2e8f0 !important;
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
            <div class="mb-3">
                <a href="{{ route('exams.show', $exam) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to Exam Details
                </a>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-eye me-2"></i>{{ $exam->title }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Exam Type:</strong> <span class="badge">{{ ucfirst($exam->exam_type) }}</span></p>
                            @if($exam->subject)
                                <p><strong>Subject:</strong> {{ $exam->subject->name }} (Class {{ $exam->subject->class }})</p>
                            @endif
                            @if($exam->chapter)
                                <p><strong>Chapter:</strong> {{ $exam->chapter->name }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p><strong>Duration:</strong> {{ $exam->duration }} minutes</p>
                            <p><strong>Total Questions:</strong> {{ $exam->questions->count() }}</p>
                             
                        </div>
                    </div>
                     
                </div>
            </div>

            @if($exam->questions->isEmpty())
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>No questions have been added to this exam yet.
                </div>
            @else
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-list-ol me-2"></i>Questions ({{ $exam->questions->count() }})</h5>
                    </div>
                    <div class="card-body">
                        @foreach($exam->questions as $index => $question)
                            <div class="card mb-4">
                                <div class="card-header">
                                    <strong>Question {{ $index + 1 }}</strong>
                                    <!-- @if($question->year)
                                        <span class="badge ms-2">Year: {{ $question->year }}</span>
                                    @endif
                                    @if($question->source_type)
                                        <span class="badge ms-2">{{ ucfirst($question->source_type) }}</span>
                                    @endif -->
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <p>{!! str_replace(['\\(', '\\)', '\\[', '\\]'], ['\(', '\)', '\[', '\]'], html_entity_decode($question->question_text)) !!}</p>
                                    </div>

                                    @if($question->image || $question->image_url)
                                        <div class="mb-3">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" 
                                                    data-bs-toggle="collapse" 
                                                    data-bs-target="#image-{{ $question->id }}">
                                                <i class="bi bi-image me-1"></i>Toggle Image
                                            </button>
                                            <div class="collapse mt-2" id="image-{{ $question->id }}">
                                                <img src="{{ $question->image ?? $question->image_url }}" 
                                                     alt="Question Image" 
                                                     class="img-fluid rounded border"
                                                     style="max-height: 400px;">
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row g-3">
                                        @foreach(['A', 'B', 'C', 'D'] as $option)
                                            @php
                                                $optionField = 'option_' . strtolower($option);
                                                $isCorrect = strtoupper($question->correct_option) === $option || $question->correct_answer === $option;
                                            @endphp
                                            @if($question->$optionField)
                                                <div class="col-md-6">
                                                    <div class="option-item p-3 rounded d-flex align-items-start {{ $isCorrect ? 'correct' : '' }}">
                                                        <div class="flex-shrink-0 me-2">
                                                            <strong>{{ $option }})</strong>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            {!! str_replace(['\\(', '\\)', '\\[', '\\]'], ['\(', '\)', '\[', '\]'], html_entity_decode($question->$optionField)) !!}
                                                            @if($isCorrect)
                                                                <span class="badge ms-2">✓ Correct</span>
                                                            @endif
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
                    <div class="card-footer text-center">
                        <a href="{{ route('exams.take', $exam) }}" class="btn btn-success btn-lg">
                            <i class="bi bi-pencil-square me-2"></i>Take This Exam
                        </a>
                    </div>
                </div>
            @endif
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