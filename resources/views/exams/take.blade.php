@extends('layouts.app')

@section('title', 'Take Exam - ' . $exam->title)

@section('content')
<div class="container-fluid py-4 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Back Button -->
            <div class="mb-3">
                <a href="{{ route('exams.show', $exam) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to Exam Details
                </a>
            </div>

            <!-- Exam Header Card -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-pencil-square me-2"></i>{{ $exam->title }}
                        </h5>
                        <div class="text-end">
                            <div class="fs-6 mb-1">Time Remaining</div>
                            <div class="fs-3 fw-bold" id="timer">{{ $exam->duration }}:00</div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            @if($exam->subject)
                                <p class="mb-2"><strong>Subject:</strong> {{ $exam->subject->name }} (Class {{ $exam->subject->class }})</p>
                            @endif
                            @if($exam->chapter)
                                <p class="mb-2"><strong>Chapter:</strong> {{ $exam->chapter->name }}</p>
                            @endif
                            <p class="mb-0"><strong>Total Questions:</strong> {{ $exam->questions->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($exam->questions->isEmpty())
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>No questions have been added to this exam yet.
                </div>
            @else
                <!-- Display Validation Errors -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong>Error!</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('exams.submit', $exam) }}" method="POST" id="examForm">
                    @csrf
                    
                    <div id="questions-container">
                        @foreach($exam->questions as $index => $question)
                            <div class="card mb-4 shadow-sm">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Question {{ $index + 1 }}</strong>
                                            
                                                
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="question-text mb-3">
                                        <p class="mb-3 fw-semibold">{!! $question->question_text !!}</p>
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
                                                     class="img-fluid rounded border shadow-sm"
                                                     style="max-height: 400px;">
                                            </div>
                                        </div>
                                    @endif

                                    <div class="options-container">
                                        <div class="row g-3">
                                            @foreach(['A', 'B', 'C', 'D'] as $option)
                                                @php
                                                    $optionField = 'option_' . strtolower($option);
                                                @endphp
                                                @if($question->$optionField)
                                                    <div class="col-md-6">
                                                        <div class="option-item p-3 rounded border bg-light answer-option d-flex align-items-start" 
                                                                data-question="{{ $question->id }}" 
                                                                style="cursor: pointer;">
                                                            <input class="form-check-input me-2 answer-radio flex-shrink-0" 
                                                                    type="radio" 
                                                                    name="answers[{{ $question->id }}]" 
                                                                    id="q{{ $question->id }}_{{ $option }}" 
                                                                    value="{{ $option }}"
                                                                    style="margin-top: 0.25rem;">
                                                            <label class="form-check-label" for="q{{ $question->id }}_{{ $option }}" style="cursor: pointer; flex: 1;">
                                                                <strong>{{ $option }})</strong> {!! $question->$optionField !!}
                                                            </label>
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

                    <div class="text-center sticky-bottom mb-4" style="bottom: 20px;">
                        <button type="submit" class="btn btn-success btn-lg px-4" id="submitBtn">
                            <i class="bi bi-check-circle me-2"></i>Submit Exam
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

<script>
var timeLeft;
var timerInterval;

document.addEventListener("DOMContentLoaded", function() {
    // Timer variables
    var isActiveExam = {{ $isActiveExam ? 'true' : 'false' }};
    var duration = {{ $exam->duration }};
    var remainingSeconds = {{ $remainingSeconds ?? 'null' }};
    
    // Calculate initial time left (ensure it's an integer)
    if (isActiveExam && remainingSeconds !== null) {
        timeLeft = Math.floor(remainingSeconds);
    } else {
        timeLeft = duration * 60;
    }
    
    var timerElement = document.getElementById('timer');
    var examForm = document.getElementById('examForm');
    var submitBtn = document.getElementById('submitBtn');
    var totalQuestions = {{ $exam->questions->count() }};
    
    // Timer update function (DISPLAY ONLY - no auto-submit)
    function updateTimer() {
        if (!timerElement) return;
        
        var minutes = Math.floor(timeLeft / 60);
        var seconds = timeLeft % 60;
        var displayText = minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
        timerElement.textContent = displayText;
        
        if (timeLeft <= 300 && timeLeft > 0) {
            timerElement.classList.add('text-danger');
        }
        
        if (timeLeft === 300) {
            alert('Warning: Only 5 minutes remaining!');
        }
        
        // ✅ REMOVED AUTO-SUBMIT: Backend will validate time
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            timerElement.textContent = '0:00';
            timerElement.classList.add('text-danger');
            alert('Time is up! Please submit your exam. The server will validate your submission time.');
            // Note: User can still try to submit, but backend will reject if time expired
            return;
        }
        
        timeLeft--;
    }
    
    // Start timer (DISPLAY ONLY - actual validation happens on server)
    updateTimer();
    timerInterval = setInterval(updateTimer, 1000);
    
    // Answer selection handlers
    document.querySelectorAll('.answer-radio').forEach(function(radio) {
        radio.addEventListener('change', function() {
            var questionId = this.closest('.answer-option').dataset.question;
            var allRadios = document.querySelectorAll('input[name="answers[' + questionId + ']"]');
            
            // Remove highlight from all options for this question
            allRadios.forEach(function(r) {
                var optionDiv = r.closest('.answer-option');
                optionDiv.classList.remove('bg-primary', 'bg-opacity-10', 'border-primary');
                optionDiv.classList.add('bg-light');
                optionDiv.style.borderWidth = '';
            });
            
            // Highlight selected option
            this.closest('.answer-option').classList.remove('bg-light');
            this.closest('.answer-option').classList.add('bg-primary', 'bg-opacity-10', 'border-primary');
            this.closest('.answer-option').style.borderWidth = '2px';
        });
    });
    
    // MathJax rendering
    setTimeout(function() {
        if (typeof renderMathInElement !== 'undefined') {
            renderMathInElement(document.body, {
                delimiters: [
                    {left: '$$', right: '$$', display: true},
                    {left: '$', right: '$', display: false}
                ],
                throwOnError: false
            });
        }
    }, 200);
    
    // Form submission handler
    window.onbeforeunload = function(e) {
        e.preventDefault();
        return 'Are you sure you want to leave?';
    };
    
    if (examForm) {
        examForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            var answeredQuestions = document.querySelectorAll('input[type="radio"]:checked').length;
            var confirmMessage = 'Are you sure you want to submit your exam?';
            
            if (answeredQuestions < totalQuestions) {
                var unanswered = totalQuestions - answeredQuestions;
                confirmMessage = 'You have ' + unanswered + ' unanswered question(s). ' + confirmMessage;
            }
            
            if (confirm(confirmMessage)) {
                clearInterval(timerInterval);
                window.onbeforeunload = null;
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';
                }
                
                // Submit the form
                HTMLFormElement.prototype.submit.call(this);
            }
        });
    }
});
</script>

<style>
.sticky-bottom {
    position: sticky;
    z-index: 1020;
}
</style>

@endsection