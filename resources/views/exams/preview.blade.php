@extends('layouts.app')

@section('title', 'Preview - ' . $exam->title)

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Back Button -->
            <div class="mb-3">
                <a href="{{ route('exams.show', $exam) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to Exam Details
                </a>
            </div>

            <!-- Exam Details Card -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-eye me-2"></i>Preview: {{ $exam->title }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Exam Type:</strong> <span class="badge bg-secondary">{{ ucfirst($exam->exam_type) }}</span></p>
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
                            <p><strong>Availability:</strong> <span class="badge bg-success">Always Available</span></p>
                        </div>
                    </div>
                    <div class="alert alert-info mt-3">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Preview Mode:</strong> This is a preview showing all questions with correct answers marked. Use "Give Exam" to take the actual exam.
                    </div>
                </div>
            </div>

            <!-- Questions List -->
            @if($exam->questions->isEmpty())
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>No questions have been added to this exam yet.
                </div>
            @else
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-list-ol me-2"></i>Questions ({{ $exam->questions->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach($exam->questions as $index => $question)
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <strong>Question {{ $index + 1 }}</strong>
                                    @if($question->year)
                                        <span class="badge bg-info ms-2">Year: {{ $question->year }}</span>
                                    @endif
                                    @if($question->source_type)
                                        <span class="badge bg-secondary ms-2">{{ ucfirst($question->source_type) }}</span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="question-text mb-3">
                                        <p class="mb-3">{!! $question->question_text !!}</p>
                                    </div>

                                    @if($question->image_url)
                                        <div class="mb-3">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" 
                                                    data-bs-toggle="collapse" 
                                                    data-bs-target="#image-{{ $question->id }}">
                                                <i class="bi bi-image me-1"></i>Toggle Image
                                            </button>
                                            <div class="collapse mt-2" id="image-{{ $question->id }}">
                                                <img src="{{ $question->image_url }}" 
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
                                                    $isCorrect = $question->correct_answer === $option;
                                                @endphp
                                                @if($question->$optionField)
                                                    <div class="col-md-6 mb-2">
                                                        <div class="option-item p-3 rounded @if($isCorrect) bg-success bg-opacity-10 border border-success @else bg-light border @endif">
                                                            <strong>{{ $option }})</strong> {!! $question->$optionField !!}
                                                            @if($isCorrect)
                                                                <span class="badge bg-success ms-2">✓ Correct</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
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
    // Initialize MathJax for LaTeX rendering
    if (typeof MathJax !== 'undefined') {
        MathJax.typesetPromise();
    }
</script>
@endpush
@endsection
