@section('head')
    <!-- MathJax for LaTeX rendering -->
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
@endsection
@extends('layouts.app')

@section('title', $exam->title)

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Exam Details Card -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-journal-text me-2"></i>{{ $exam->title }}
                    </h5>
                    <div>
                        @if($exam->questions->isEmpty())
                            <a href="{{ route('exams.questions.select', $exam) }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-1"></i>Add Questions
                            </a>
                        @else
                            <a href="{{ route('exams.questions.select', $exam) }}" class="btn btn-outline-primary">
                                <i class="bi bi-pencil me-1"></i>Edit Questions
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">Subject</dt>
                                <dd class="col-sm-8">{{ $exam->subject->name }}</dd>

                                <dt class="col-sm-4">Type</dt>
                                <dd class="col-sm-8">{{ ucfirst($exam->exam_type) }}</dd>

                                @if($exam->exam_type == 'university')
                                    <dt class="col-sm-4">Institution</dt>
                                    <dd class="col-sm-8">{{ $exam->institution_name }}</dd>

                                    <dt class="col-sm-4">Year</dt>
                                    <dd class="col-sm-8">{{ $exam->year }}</dd>
                                @endif

                                @if($exam->exam_type == 'custom')
                                    <dt class="col-sm-4">Chapter</dt>
                                    <dd class="col-sm-8">{{ $exam->chapter->name ?? 'All Chapters' }}</dd>
                                @endif
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">Duration</dt>
                                <dd class="col-sm-8">{{ $exam->duration }} minutes</dd>

                                <dt class="col-sm-4">Questions</dt>
                                <dd class="col-sm-8">{{ $exam->questions->count() }}</dd>

                                @if($exam->is_rated)
                                    <dt class="col-sm-4">Rated</dt>
                                    <dd class="col-sm-8">
                                        <span class="badge bg-success">Yes</span>
                                        <span class="ms-2">Level {{ $exam->difficulty_level }}</span>
                                    </dd>

                                    <dt class="col-sm-4">Start Time</dt>
                                    <dd class="col-sm-8">{{ $exam->start_time ? $exam->start_time->format('M d, Y H:i') : 'Not set' }}</dd>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Questions List -->
                @if($exam->questions->isNotEmpty())
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-list-check me-2"></i>Questions
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach($exam->questions as $index => $question)
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <strong>Question {{ $index + 1 }}</strong>
                                    <span class="badge bg-secondary ms-2">{{ ucfirst($question->type) }}</span>
                                    <span class="badge bg-info ms-2">Marks: {{ $question->marks }}</span>
                                    <span class="badge bg-light text-dark ms-2">
                                        @if($question->board)
                                            {{ $question->board }} {{ $question->year }}
                                        @elseif($question->institution)
                                            {{ $question->institution }} {{ $question->year }}
                                        @else
                                            Custom
                                        @endif
                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="question-text mb-3">
                                        <h6 class="fw-bold">Question:</h6>
                                        <p class="mb-3">{!! $question->question_text ?? $question->text !!}</p>
                                    </div>
                                    @if($question->image)
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/' . $question->image) }}" alt="Question Image" class="img-fluid rounded shadow-sm" style="max-width:400px;">
                                        </div>
                                    @endif
                                    @if($question->options && count($question->options))
                                        <div class="options-container">
                                            <div class="row">
                                                @foreach($question->options as $optIndex => $option)
                                                    <div class="col-md-6 mb-2">
                                                        <div class="option-item p-3 rounded @if(isset($question->correct_option) && $question->correct_option == chr(65 + $optIndex)) bg-success-subtle border border-success @else bg-light border @endif">
                                                            <strong>{{ chr(97 + $optIndex) }})</strong> {!! $option !!}
                                                            @if(isset($question->correct_option) && $question->correct_option == chr(65 + $optIndex))
                                                                <span class="badge bg-success ms-2">✓ Correct</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    @if($question->type === 'written')
                                        <div class="mb-2">
                                            <div class="alert alert-info">
                                                <i class="bi bi-pencil me-2"></i><strong>Written Answer Required</strong>
                                                <p class="mb-0 mt-2">This question requires a written response and will be manually evaluated.</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
        </div>
    </div>
</div>
@endsection