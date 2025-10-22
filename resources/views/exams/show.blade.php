@extends('layouts.app')

@section('title', $exam->title)

@section('content')
<style>
    :root {
        --bg-primary: #0f172a;
        --bg-secondary: #1e293b;
        --bg-tertiary: #334155;
        --bg-hover: #2d3748;
        --border-primary: #475569;
        --text-primary: #e2e8f0;
        --text-secondary: #f1f5f9;
        --text-muted: #94a3b8;
        --accent-green: #15803d;
        --accent-green-hover: #166534;
        --shadow-md: 0 4px 15px rgba(0, 0, 0, 0.3);
    }
    
    .question-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-primary);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        color: var(--text-primary);
    }
    
    .question-card:hover {
        box-shadow: var(--shadow-md);
        border-color: var(--bg-primary);
    }
    
    .question-image {
        margin: 1rem 0;
        text-align: center;
        background-color: transparent;
        padding: 10px;
        border-radius: 8px;
    }
    
    .question-image img {
        max-height: 300px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        background-color: white;
        transition: transform 0.3s ease;
    }
    
    .question-image img:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0,0,0,0.5);
    }
    
    .options-display {
        background-color: var(--bg-secondary);
        padding: 8px;
        border-radius: 6px;
        border: 1px solid var(--border-primary);
    }
    
    .option-item {
        background-color: var(--bg-tertiary);
        border: 1px solid var(--border-primary);
        color: var(--text-primary);
        padding: 0.75rem;
        border-radius: 6px;
        margin-bottom: 0.5rem;
    }
    
    .option-item.correct {
        background-color: var(--accent-green-hover);
        border-color: var(--accent-green);
        color: white;
    }
    
    .btn-image-control {
        border-radius: 20px;
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
        margin-right: 0.5rem;
    }
    
    .image-controls {
        gap: 8px;
        margin-bottom: 1rem;
    }
</style>

<div class="container py-4">
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
                                <dt class="col-sm-4">Type</dt>
                                <dd class="col-sm-8">{{ ucfirst($exam->exam_type) }}</dd>

                                @if($exam->subject)
                                    <dt class="col-sm-4">Subject</dt>
                                    <dd class="col-sm-8">{{ $exam->subject->name }}</dd>
                                @endif

                                @if($exam->exam_type == 'board')
                                    <dt class="col-sm-4">Board</dt>
                                    <dd class="col-sm-8">{{ $exam->board_name }}</dd>

                                    <dt class="col-sm-4">Year</dt>
                                    <dd class="col-sm-8">{{ $exam->year }}</dd>
                                @endif

                                @if($exam->exam_type == 'university')
                                    <dt class="col-sm-4">University</dt>
                                    <dd class="col-sm-8">{{ $exam->university_name }}</dd>

                                    <dt class="col-sm-4">Year</dt>
                                    <dd class="col-sm-8">{{ $exam->year }}</dd>
                                @endif

                                @if($exam->exam_type == 'custom')
                                    @if($exam->chapter)
                                        <dt class="col-sm-4">Chapter</dt>
                                        <dd class="col-sm-8">{{ $exam->chapter->name }}</dd>
                                    @endif

                                    @if($exam->custom_criteria)
                                        <dt class="col-sm-4">Criteria</dt>
                                        <dd class="col-sm-8">
                                            @foreach($exam->custom_criteria as $key => $value)
                                                <span class="badge bg-secondary me-1">{{ $key }}: {{ $value }}</span>
                                            @endforeach
                                        </dd>
                                    @endif
                                @endif
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">Duration</dt>
                                <dd class="col-sm-8">{{ $exam->duration }} minutes</dd>

                                <dt class="col-sm-4">Questions</dt>
                                <dd class="col-sm-8">{{ $exam->questions->count() }}</dd>

                                @if($exam->start_time)
                                    <dt class="col-sm-4">Start Time</dt>
                                    <dd class="col-sm-8">{{ $exam->start_time->format('M d, Y H:i') }}</dd>

                                    <dt class="col-sm-4">End Time</dt>
                                    <dd class="col-sm-8">{{ $exam->end_time->format('M d, Y H:i') }}</dd>
                                @else
                                    <dt class="col-sm-4">Availability</dt>
                                    <dd class="col-sm-8"><span class="badge bg-success">Available Anytime</span></dd>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student's Performance Section -->
            @if(isset($userAttempt) && $userAttempt)
            <div class="card mb-4" style="background-color: #1e293b !important; border: 1px solid #475569 !important; border-radius: 12px !important; color: #e2e8f0 !important;">
                <div class="card-header" style="background-color: #334155 !important; color: #e2e8f0 !important; border-bottom: 1px solid #475569; border-radius: 12px 12px 0 0 !important;">
                    <h5 class="mb-0" style="color: #e2e8f0 !important;">
                        <i class="bi bi-clipboard-check me-2"></i>Your Performance
                    </h5>
                </div>
                <div class="card-body" style="background-color: #1e293b !important; color: #e2e8f0 !important;">
                    <!-- Result Summary -->
                    <div style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border-radius: 15px; padding: 2rem; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3); border: 1px solid #475569;" class="mb-4">
                        <div class="row g-4">
                            <div class="col-md-3">
                                <div style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 2px solid #475569; border-radius: 12px; padding: 1.5rem; transition: transform 0.3s ease;" class="text-center">
                                    <div style="font-size: 3rem; font-weight: 700; line-height: 1; color: {{ $userAttempt->score >= 80 ? '#10b981' : ($userAttempt->score >= 60 ? '#fbbf24' : '#f43f5e') }} !important;">
                                        {{ $userAttempt->score }}%
                                    </div>
                                    <p style="color: #94a3b8 !important;" class="mb-0 mt-2">Your Score</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 2px solid #475569; border-radius: 12px; padding: 1.5rem; transition: transform 0.3s ease;" class="text-center">
                                    <div style="font-size: 3rem; font-weight: 700; line-height: 1; color: #10b981 !important;">{{ $userAttempt->correct_ans }}</div>
                                    <p style="color: #94a3b8 !important;" class="mb-0 mt-2">Correct</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 2px solid #475569; border-radius: 12px; padding: 1.5rem; transition: transform 0.3s ease;" class="text-center">
                                    <div style="font-size: 3rem; font-weight: 700; line-height: 1; color: #f43f5e !important;">{{ $userAttempt->wrong_ans }}</div>
                                    <p style="color: #94a3b8 !important;" class="mb-0 mt-2">Wrong</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 2px solid #475569; border-radius: 12px; padding: 1.5rem; transition: transform 0.3s ease;" class="text-center">
                                    <div style="font-size: 3rem; font-weight: 700; line-height: 1; color: #3b82f6 !important;">{{ $userAttempt->total_ques }}</div>
                                    <p style="color: #94a3b8 !important;" class="mb-0 mt-2">Total</p>
                                </div>
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
                        <h6 class="mb-3" style="color: #e2e8f0 !important;"><i class="bi bi-list-check me-2"></i>Detailed Results</h6>
                        @foreach($userAttempt->answers as $index => $answer)
                        <div style="background: #1e293b; border-radius: 12px; border: 1px solid #475569; overflow: hidden; margin-bottom: 1.5rem;">
                            <div style="padding: 1rem 1.25rem; display: flex; justify-content: space-between; align-items: center; background-color: #334155; border-bottom: 1px solid #475569;">
                                <strong style="color: #e2e8f0 !important;">Question {{ $index + 1 }}</strong>
                                @if($answer->is_correct)
                                    <span class="badge" style="background-color: #10b981 !important; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 500;">
                                        <i class="bi bi-check-circle me-1"></i>Correct
                                    </span>
                                @elseif($answer->chosen_option)
                                    <span class="badge" style="background-color: #f43f5e !important; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 500;">
                                        <i class="bi bi-x-circle me-1"></i>Wrong
                                    </span>
                                @else
                                    <span class="badge" style="background-color: #3b82f6 !important; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 500;">
                                        <i class="bi bi-dash-circle me-1"></i>Skipped
                                    </span>
                                @endif
                            </div>
                            <div class="p-4">
                                <p class="mb-3" style="color: #e2e8f0 !important;">{!! str_replace(['\\(', '\\)', '\\[', '\\]'], ['\(', '\)', '\[', '\]'], html_entity_decode($answer->question->question_text)) !!}</p>
                                
                                @if($answer->question->image_url)
                                    <div class="mb-3">
                                        <button class="btn btn-sm" style="background-color: #334155 !important; border-color: #475569 !important; color: #e2e8f0 !important; border-radius: 8px; padding: 0.65rem 1.5rem;" type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#perf-image-{{ $answer->question->id }}">
                                            <i class="bi bi-image me-1"></i>View Image
                                        </button>
                                        <div class="collapse mt-2" id="perf-image-{{ $answer->question->id }}">
                                            <img src="{{ $answer->question->image_url }}" 
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
                                            $isCorrect = strtoupper($answer->question->correct_option) === $option || $answer->question->correct_answer === $option;
                                            $isUserAnswer = $answer->chosen_option === $option;
                                        @endphp
                                        @if($answer->question->$optionField)
                                            <div class="col-md-6">
                                                <div style="background-color: #334155; border: 2px solid #475569; border-radius: 10px; padding: 1rem; margin-bottom: 0.75rem; transition: all 0.3s ease; 
                                                    @if($isCorrect) 
                                                        background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(16, 185, 129, 0.05) 100%); border-color: #10b981; border-width: 2px;
                                                    @elseif($isUserAnswer)
                                                        background: linear-gradient(135deg, rgba(244, 63, 94, 0.15) 0%, rgba(244, 63, 94, 0.05) 100%); border-color: #f43f5e; border-width: 2px;
                                                    @endif">
                                                    <div class="d-flex align-items-start">
                                                        <div class="flex-shrink-0 me-2">
                                                            <strong style="color: #e2e8f0 !important;">{{ $option }})</strong>
                                                        </div>
                                                        <div class="flex-grow-1" style="color: #e2e8f0 !important;">
                                                            {!! str_replace(['\\(', '\\)', '\\[', '\\]'], ['\(', '\)', '\[', '\]'], html_entity_decode($answer->question->$optionField)) !!}
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
                        <div class="card-body" style="background-color: var(--bg-primary); padding: 2rem;">
                            @foreach($exam->questions as $index => $question)
                            <div class="question-card">
                                <!-- Question Header -->
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="question-header">
                                        <span class="badge me-2" style="background-color: var(--accent-green);">#{{ $question->id }}</span>
                                        <strong style="color: var(--text-secondary);">Question {{ $index + 1 }}</strong>
                                    </div>
                                    <div class="question-meta d-flex flex-wrap gap-1">
                                        @if($question->subject)
                                            <span class="badge" style="background-color: var(--accent-green);">{{ $question->subject->name }}</span>
                                        @endif
                                        @if($question->chapter)
                                            <span class="badge" style="background-color: var(--accent-green);">{{ $question->chapter->name }}</span>
                                        @endif
                                        @if($question->source_type)
                                            <span class="badge" style="background-color: var(--accent-green);">{{ ucfirst($question->source_type) }}</span>
                                        @endif
                                        @if($question->year)
                                            <span class="badge" style="background-color: var(--accent-green);">{{ $question->year }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Question Text -->
                                <div class="question-text mb-3">
                                    <p class="mb-2" style="color: var(--text-primary);">{!! $question->question_text !!}</p>
                                </div>
                                
                                <!-- Image Section -->
                                @if($question->image)
                                    <div class="mb-3">
                                        <div class="image-controls d-flex align-items-center">
                                            <a href="{{ $question->image }}" target="_blank" class="btn btn-sm btn-outline-info btn-image-control">
                                                <i class="bi bi-image me-1"></i>View Full Size
                                            </a>
                                            <button class="btn btn-sm btn-outline-secondary btn-image-control" onclick="toggleImage('img-{{ $question->id }}')">
                                                <i class="bi bi-eye-slash me-1"></i>Hide Image
                                            </button>
                                        </div>
                                        <div id="img-{{ $question->id }}" class="question-image" style="display: block;">
                                            <img src="{{ $question->image }}" alt="Question Image" 
                                                 class="img-fluid rounded border shadow-sm" 
                                                 style="max-width: 100%; max-height: 300px; object-fit: contain; background-color: white !important;"
                                                 loading="lazy">
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Options with Correct Answer Highlighted -->
                                <div class="options-display">
                                    <div class="row g-2">
                                        @php
                                            $options = ['a', 'b', 'c', 'd'];
                                        @endphp
                                        @foreach($options as $option)
                                            @if($question->{'option_' . $option})
                                                <div class="col-md-6">
                                                    <div class="option-item {{ strtoupper($question->correct_option) === strtoupper($option) ? 'correct' : '' }}">
                                                        <strong>{{ strtoupper($option) }})</strong> {{ $question->{'option_' . $option} }}
                                                        @if(strtoupper($question->correct_option) === strtoupper($option))
                                                            <i class="bi bi-check-circle-fill ms-2"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- Additional Info -->
                                @if($question->source_name)
                                    <div class="mt-2">
                                        <small style="color: var(--text-muted);">
                                            <i class="bi bi-building me-1"></i>Source: {{ $question->source_name }}
                                        </small>
                                    </div>
                                @endif
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
                         
                            @endif
                            
                            <div class="d-flex justify-content-center gap-3 flex-wrap">
                                @if($canShowQuestions)
                                    <a href="{{ route('exams.preview', $exam) }}" class="btn btn-info btn-lg">
                                        <i class="bi bi-eye me-2"></i>Show Questions</a>
                                @endif
                                
                                @if($canTakeExam)
                                    <a href="{{ route('exams.take', $exam) }}" class="btn btn-success btn-lg">
                                        <i class="bi bi-pencil-square me-2"></i>Give Exam
                                        
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
    
    /* Dark theme for exam show page */
    .card {
        background-color: #1e293b !important;
        border: none !important;
        color: #e2e8f0 !important;
    }
    
    .card-header {
        background-color: #334155 !important;
        border-bottom: 1px solid #475569 !important;
        color: #e2e8f0 !important;
    }
    
    .card-title {
        color: #e2e8f0 !important;
    }
    
    .card-body {
        color: #e2e8f0 !important;
    }
    
    .list-group-item {
        background-color: #1e293b !important;
        border-color: #475569 !important;
        color: #e2e8f0 !important;
    }
    
    .list-group-item:hover {
        background-color: #2d3748 !important;
    }
    
    .badge {
        background-color: #15803d !important;
    }
    
    .text-muted {
        color: #94a3b8 !important;
    }
    
    /* Button styles */
    .btn-primary, .btn-outline-primary {
        background-color: #15803d !important;
        border-color: #15803d !important;
        color: white !important;
    }
    
    .btn-primary:hover, .btn-outline-primary:hover {
        background-color: #166534 !important;
        border-color: #166534 !important;
    }
    
    .btn-success {
        background-color: #15803d !important;
        border-color: #15803d !important;
    }
    
    .btn-success:hover {
        background-color: #166534 !important;
        border-color: #166534 !important;
    }
    
    .btn-warning {
        background-color: #ca8a04 !important;
        border-color: #ca8a04 !important;
    }
    
    .btn-secondary {
        background-color: #475569 !important;
        border-color: #475569 !important;
    }
    
    /* Alert styles */
    .alert-warning {
        background-color: #ca8a04;
        border-color: #ca8a04;
        color: #000;
    }
    
    .alert-success {
        background-color: #15803d;
        border-color: #15803d;
        color: white;
    }
    
    .alert-info {
        background-color: #334155;
        border-color: #475569;
        color: #e2e8f0;
    }
    
    /* Question display */
    .question-text {
        color: #e2e8f0 !important;
    }
    
    .option-label {
        color: #e2e8f0 !important;
    }
    
    /* Performance section */
    .performance-card {
        background-color: #1e293b !important;
        border: 1px solid #475569 !important;
    }
    
    /* Form elements if any */
    .form-control, .form-select {
        background-color: #334155 !important;
        border-color: #475569 !important;
        color: #e2e8f0 !important;
    }
</style>

@endsection