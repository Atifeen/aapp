@extends('layouts.app')

@section('title', $exam->title)

@section('content')
<style>
    .question-image {
        margin: 0.5rem 0;
        text-align: center;
    }
    
    .question-image img {
        max-height: 300px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .question-image img:hover {
        transform: scale(1.02);
        transition: transform 0.2s ease-in-out;
    }
</style>
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
                        @auth
                            @if(auth()->user()->role === 'admin')
                                @if($exam->questions->isEmpty())
                                    <a href="{{ route('exams.questions.select', $exam) }}" class="btn btn-primary">
                                        <i class="bi bi-plus-lg me-1"></i>Add Questions
                                    </a>
                                @else
                                    <a href="{{ route('exams.questions.select', $exam) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-pencil me-1"></i>Edit Questions
                                    </a>
                                @endif
                            @elseif(auth()->user()->role === 'student')
                                @if($exam->questions->isNotEmpty())
                                    @php
                                        // Check if exam is finished (start_time + duration has passed)
                                        $isFinished = false;
                                        if ($exam->start_time && $exam->duration) {
                                            $endTime = $exam->start_time->copy()->addMinutes($exam->duration);
                                            $isFinished = now()->greaterThan($endTime);
                                        }
                                        
                                        // Show Questions button: for board exams OR finished exams
                                        $canShowQuestions = ($exam->exam_type === 'board') || $isFinished;
                                        
                                        // Check if user has already attempted this exam
                                        $hasAttempted = isset($userAttempt) && $userAttempt;
                                        
                                        // Give Exam button: only if exam is available, not finished, and (not rated OR not attempted)
                                        $canTakeExam = !$isFinished 
                                            && (is_null($exam->start_time) || $exam->start_time <= now())
                                            && (!$exam->is_rated || !$hasAttempted);
                                    @endphp
                                    
                                    <!-- Show Questions button: for board exams or finished exams -->
                                    @if($canShowQuestions)
                                        <a href="{{ route('exams.preview', $exam) }}" class="btn btn-info me-2">
                                            <i class="bi bi-eye me-1"></i>Show Questions
                                        </a>
                                    @endif
                                    
                                    <!-- Give Exam button: only if exam is available and not finished -->
                                    @if($canTakeExam)
                                        <a href="{{ route('exams.take', $exam) }}" class="btn btn-success">
                                            <i class="bi bi-pencil-square me-1"></i>Give Exam
                                        </a>
                                    @elseif($hasAttempted && $exam->is_rated)
                                        <!-- Already attempted a rated exam -->
                                        <span class="badge bg-info fs-6 p-2">
                                            <i class="bi bi-check-circle-fill me-1"></i>Already Attempted
                                        </span>
                                    @elseif($isFinished)
                                        <!-- Exam has finished -->
                                        <span class="badge bg-secondary fs-6 p-2">
                                            <i class="bi bi-check-circle me-1"></i>Exam Finished
                                        </span>
                                    @elseif($exam->start_time && $exam->start_time > now())
                                        <!-- Exam hasn't started yet - Show Countdown -->
                                        <div class="upcoming-exam-container">
                                            <button class="btn btn-secondary" disabled>
                                                <i class="bi bi-clock me-1"></i>Not Started Yet
                                            </button>
                                            <div class="mt-3 p-3 bg-light rounded border">
                                                <h6 class="mb-2"><i class="bi bi-hourglass-split me-2"></i>Starts In:</h6>
                                                <div class="countdown-timer fs-4 fw-bold text-primary" 
                                                     data-start-time="{{ $exam->start_time->timestamp }}"
                                                     id="countdown-{{ $exam->id }}">
                                                    Calculating...
                                                </div>
                                                <small class="text-muted d-block mt-2">
                                                    Scheduled: {{ $exam->start_time->format('M d, Y H:i') }}
                                                </small>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <span class="badge bg-warning">No questions added yet</span>
                                @endif
                            @endif
                        @endauth
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

            <!-- Student's Performance Section -->
            @if(isset($userAttempt) && $userAttempt)
            <div class="card mb-4 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-clipboard-check me-2"></i>Your Performance
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-4">
                        <div class="col-md-3">
                            <div class="border rounded p-3 bg-light">
                                <h2 class="display-5 mb-0 text-{{ $userAttempt->score >= 80 ? 'success' : ($userAttempt->score >= 60 ? 'warning' : 'danger') }}">
                                    {{ $userAttempt->score }}%
                                </h2>
                                <p class="text-muted mb-0 small">Your Score</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 bg-light">
                                <h2 class="display-5 mb-0 text-success">{{ $userAttempt->correct_ans }}</h2>
                                <p class="text-muted mb-0 small">Correct</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 bg-light">
                                <h2 class="display-5 mb-0 text-danger">{{ $userAttempt->wrong_ans }}</h2>
                                <p class="text-muted mb-0 small">Wrong</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 bg-light">
                                <h2 class="display-5 mb-0 text-info">{{ $userAttempt->total_ques }}</h2>
                                <p class="text-muted mb-0 small">Total Questions</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($exam->is_rated)
                        @php
                            $ratingChange = $userAttempt->ratingChanges->first();
                        @endphp
                        @if($ratingChange)
                        <div class="alert alert-info">
                            <i class="bi bi-graph-up me-2"></i>
                            <strong>Rating Change:</strong>
                            {{ $ratingChange->old_rating }} 
                            <i class="bi bi-arrow-right mx-2"></i>
                            <span class="text-{{ $ratingChange->new_rating > $ratingChange->old_rating ? 'success' : 'danger' }} fw-bold">
                                {{ $ratingChange->new_rating }}
                                ({{ $ratingChange->new_rating > $ratingChange->old_rating ? '+' : '' }}{{ $ratingChange->new_rating - $ratingChange->old_rating }})
                            </span>
                            <span class="ms-3">
                                <i class="bi bi-trophy me-1"></i>Rank: #{{ $ratingChange->rank_in_contest }}
                            </span>
                        </div>
                        @endif
                    @endif
                    
                    <div class="alert alert-{{ $userAttempt->score >= 80 ? 'success' : ($userAttempt->score >= 60 ? 'warning' : 'info') }} mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        @if($exam->is_rated)
                            <strong>You have completed this rated exam.</strong> You cannot take it again, but you can review your answers below.
                        @else
                            <strong>Exam completed on {{ $userAttempt->created_at->format('M d, Y H:i') }}</strong>
                        @endif
                    </div>
                    
                    <!-- Show detailed answers -->
                    <div class="mt-4">
                        <h6 class="mb-3"><i class="bi bi-list-check me-2"></i>Your Detailed Answers</h6>
                        @foreach($userAttempt->answers as $index => $answer)
                        <div class="card mb-3 border-{{ $answer->is_correct ? 'success' : 'danger' }}">
                            <div class="card-header bg-{{ $answer->is_correct ? 'success' : 'danger' }} bg-opacity-10">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>Question {{ $index + 1 }}</strong>
                                    <span class="badge bg-{{ $answer->is_correct ? 'success' : 'danger' }}">
                                        @if($answer->is_correct)
                                            <i class="bi bi-check-circle me-1"></i>Correct
                                        @else
                                            <i class="bi bi-x-circle me-1"></i>Wrong
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="mb-3">{!! $answer->question->question_text !!}</p>
                                
                                @if($answer->question->image_url)
                                    <div class="mb-3">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#perf-image-{{ $answer->question->id }}">
                                            <i class="bi bi-image me-1"></i>Toggle Image
                                        </button>
                                        <div class="collapse mt-2" id="perf-image-{{ $answer->question->id }}">
                                            <img src="{{ $answer->question->image_url }}" 
                                                 alt="Question Image" 
                                                 class="img-fluid rounded border"
                                                 style="max-height: 300px;">
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="row">
                                    @foreach(['A', 'B', 'C', 'D'] as $option)
                                        @php
                                            $optionField = 'option_' . strtolower($option);
                                            $isCorrect = $answer->question->correct_answer === $option;
                                            $isUserAnswer = $answer->user_answer === $option;
                                        @endphp
                                        @if($answer->question->$optionField)
                                            <div class="col-md-6 mb-2">
                                                <div class="p-2 rounded 
                                                    @if($isCorrect) 
                                                        bg-success bg-opacity-10 border border-success
                                                    @elseif($isUserAnswer && !$isCorrect)
                                                        bg-danger bg-opacity-10 border border-danger
                                                    @else 
                                                        bg-light border
                                                    @endif">
                                                    <strong>{{ $option }})</strong> {!! $answer->question->$optionField !!}
                                                    
                                                    @if($isCorrect)
                                                        <span class="badge bg-success ms-2">✓</span>
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
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Questions List - Only visible to Admin -->
            @auth
                @if(auth()->user()->role === 'admin')
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
                                            <h6 class="fw-bold">Image:</h6>
                                            <div class="d-flex align-items-center mb-2">
                                                <a href="{{ $question->image }}" target="_blank" class="btn btn-sm btn-outline-info me-2">
                                                    <i class="bi bi-image me-1"></i>View Full Size
                                                </a>
                                                <button class="btn btn-sm btn-outline-secondary" onclick="toggleImage('img-{{ $question->id }}')">
                                                    <i class="bi bi-eye-slash me-1"></i>Hide Image
                                                </button>
                                            </div>
                                            <div id="img-{{ $question->id }}" class="question-image" style="display: block;">
                                                <img src="{{ $question->image }}" alt="Question Image" 
                                                     class="img-fluid rounded shadow-sm" style="max-width:400px;">
                                            </div>
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
                @endif
            @endauth

            <!-- Student Instructions - Only visible to Students -->
            @auth
                @if(auth()->user()->role === 'student' && $exam->questions->isNotEmpty() && !isset($userAttempt))
                    @php
                        // Check if exam is finished
                        $isFinished = false;
                        if ($exam->start_time && $exam->duration) {
                            $endTime = $exam->start_time->copy()->addMinutes($exam->duration);
                            $isFinished = now()->greaterThan($endTime);
                        }
                        
                        $canShowQuestions = ($exam->exam_type === 'board') || $isFinished;
                        $canTakeExam = !$isFinished && (is_null($exam->start_time) || $exam->start_time <= now());
                    @endphp
                    
                    <div class="card">
                        <div class="card-body text-center py-5">
                            @if($isFinished)
                                <i class="bi bi-check-circle display-1 text-success mb-4"></i>
                                <h4 class="mb-4">Exam Completed</h4>
                                <p class="text-muted mb-4">
                                    This exam has finished. You can now review the questions and correct answers for learning purposes.
                                </p>
                            @else
                                <i class="bi bi-info-circle display-1 text-info mb-4"></i>
                                <h4 class="mb-4">Ready to Start?</h4>
                                <p class="text-muted mb-4">
                                    This exam contains {{ $exam->questions->count() }} questions and must be completed in {{ $exam->duration }} minutes.
                                    Choose an option below to proceed:
                                </p>
                            @endif
                            
                            <div class="d-flex justify-content-center gap-3 flex-wrap">
                                @if($canShowQuestions)
                                    <a href="{{ route('exams.preview', $exam) }}" class="btn btn-info btn-lg">
                                        <i class="bi bi-eye me-2"></i>Show Questions
                                        <small class="d-block mt-1">
                                            @if($exam->exam_type === 'board')
                                                Board exam - Preview available
                                            @else
                                                Review with answers
                                            @endif
                                        </small>
                                    </a>
                                @endif
                                
                                @if($canTakeExam)
                                    <a href="{{ route('exams.take', $exam) }}" class="btn btn-success btn-lg">
                                        <i class="bi bi-pencil-square me-2"></i>Give Exam
                                        <small class="d-block mt-1">Start the test</small>
                                    </a>
                                @elseif(!$isFinished && $exam->start_time && $exam->start_time > now())
                                    <!-- Upcoming Exam with Countdown -->
                                    <div class="text-center">
                                        <button class="btn btn-secondary btn-lg w-100 mb-3" disabled>
                                            <i class="bi bi-clock me-2"></i>Not Started Yet
                                        </button>
                                        <div class="p-4 bg-light rounded border">
                                            <h5 class="mb-3"><i class="bi bi-hourglass-split me-2"></i>Countdown</h5>
                                            <div class="countdown-timer fs-2 fw-bold text-primary" 
                                                 data-start-time="{{ $exam->start_time->timestamp }}"
                                                 id="countdown-bottom-{{ $exam->id }}">
                                                Calculating...
                                            </div>
                                            <small class="text-muted d-block mt-3">
                                                Scheduled: {{ $exam->start_time->format('M d, Y H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endauth
        </div>
    </div>
</div>

<!-- MathJax Configuration -->
<script>
// Toggle image display function
function toggleImage(imageId) {
    const imageDiv = document.getElementById(imageId);
    const button = document.querySelector(`button[onclick="toggleImage('${imageId}')"]`);
    
    if (imageDiv.style.display === 'none') {
        imageDiv.style.display = 'block';
        button.innerHTML = '<i class="bi bi-eye-slash me-1"></i>Hide Image';
    } else {
        imageDiv.style.display = 'none';
        button.innerHTML = '<i class="bi bi-eye me-1"></i>Show Image';
    }
}

MathJax = {
  tex: {
    inlineMath: [['\\(', '\\)'], ['$', '$']],
    displayMath: [['\\[', '\\]'], ['$$', '$$']]
  },
  startup: {
    ready: () => {
      MathJax.startup.defaultReady();
      MathJax.startup.promise.then(() => {
        console.log('MathJax loaded and ready');
      });
    }
  }
};

// Countdown Timer for Upcoming Exams
document.addEventListener('DOMContentLoaded', function() {
    const countdownElements = document.querySelectorAll('.countdown-timer');
    
    countdownElements.forEach(element => {
        const startTime = parseInt(element.dataset.startTime);
        
        function updateCountdown() {
            const now = Math.floor(Date.now() / 1000);
            const diff = startTime - now;
            
            if (diff <= 0) {
                element.textContent = 'Starting...';
                element.classList.add('text-success');
                element.classList.remove('text-primary');
                // Reload page when exam starts
                setTimeout(() => location.reload(), 2000);
                return;
            }
            
            const days = Math.floor(diff / 86400);
            const hours = Math.floor((diff % 86400) / 3600);
            const minutes = Math.floor((diff % 3600) / 60);
            const seconds = diff % 60;
            
            let timeString = '';
            if (days > 0) {
                timeString = `${days}d ${hours}h ${minutes}m ${seconds}s`;
            } else if (hours > 0) {
                timeString = `${hours}h ${minutes}m ${seconds}s`;
            } else if (minutes > 0) {
                timeString = `${minutes}m ${seconds}s`;
            } else {
                timeString = `${seconds}s`;
                element.classList.add('text-danger', 'pulse');
            }
            
            element.textContent = timeString;
        }
        
        // Update immediately and then every second
        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
});
</script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

<style>
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .pulse {
        animation: pulse 1s ease-in-out infinite;
    }
</style>

@endsection