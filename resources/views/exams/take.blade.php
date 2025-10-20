@extends('layouts.app')

@section('title', 'Take Exam - ' . $exam->title)

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
                        <div class="col-md-8">
                            @if($exam->subject)
                                <p class="mb-2"><strong>Subject:</strong> {{ $exam->subject->name }} (Class {{ $exam->subject->class }})</p>
                            @endif
                            @if($exam->chapter)
                                <p class="mb-2"><strong>Chapter:</strong> {{ $exam->chapter->name }}</p>
                            @endif
                            <p class="mb-0"><strong>Total Questions:</strong> {{ $exam->questions->count() }}</p>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-2">
                                <small class="text-muted">Progress: <span id="progress-text">0/{{ $exam->questions->count() }}</span></small>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-success" role="progressbar" id="progress-bar" 
                                         style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                        0%
                                    </div>
                                </div>
                            </div>
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
                                            @if($question->year)
                                                <span class="badge bg-info ms-2">Year: {{ $question->year }}</span>
                                            @endif
                                            @if($question->source_type)
                                                <span class="badge bg-secondary ms-2">{{ ucfirst($question->source_type) }}</span>
                                            @endif
                                        </div>
                                        <span class="badge bg-warning" id="status-{{ $question->id }}">Not Answered</span>
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
                                                        <div class="option-item p-3 rounded border bg-light answer-option" 
                                                             data-question="{{ $question->id }}" 
                                                             style="cursor: pointer;">
                                                            <input class="form-check-input me-2 answer-radio" 
                                                                   type="radio" 
                                                                   name="answers[{{ $question->id }}]" 
                                                                   id="q{{ $question->id }}_{{ $option }}" 
                                                                   value="{{ $option }}">
                                                            <label class="form-check-label w-100" for="q{{ $question->id }}_{{ $option }}" style="cursor: pointer;">
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

                    <div class="card shadow-lg sticky-bottom mb-4" style="bottom: 20px;">
                        <div class="card-body text-center py-3">
                            <button type="submit" class="btn btn-success btn-lg px-5" id="submitBtn">
                                <i class="bi bi-check-circle me-2"></i>Submit Exam
                            </button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    setTimeout(() => {
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
    
    const totalQuestions = {{ $exam->questions->count() }};
    
    function updateProgress() {
        const answeredCount = document.querySelectorAll('input[type="radio"]:checked').length;
        const percentage = Math.round((answeredCount / totalQuestions) * 100);
        document.getElementById('progress-text').textContent = answeredCount + '/' + totalQuestions;
        document.getElementById('progress-bar').style.width = percentage + '%';
        document.getElementById('progress-bar').textContent = percentage + '%';
    }
    
    document.querySelectorAll('.answer-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            console.log('Radio changed for question:', this.name, 'value:', this.value);
            
            const questionId = this.closest('.answer-option').dataset.question;
            const allRadios = document.querySelectorAll('input[name="answers[' + questionId + ']"]');
            const statusBadge = document.getElementById('status-' + questionId);
            
            console.log('Question ID:', questionId);
            console.log('Status badge found:', statusBadge !== null);
            
            // Don't disable - just mark as answered
            // allRadios.forEach(r => r.disabled = true);
            
            // Update status badge
            if (statusBadge) {
                statusBadge.textContent = 'Answered';
                statusBadge.classList.remove('bg-warning');
                statusBadge.classList.add('bg-success');
                console.log('Status badge updated to Answered');
            }
            
            // Remove highlight from all options for this question
            allRadios.forEach(r => {
                const optionDiv = r.closest('.answer-option');
                optionDiv.classList.remove('bg-primary', 'bg-opacity-10', 'border-primary');
                optionDiv.classList.add('bg-light');
                optionDiv.style.borderWidth = '';
            });
            
            // Highlight selected option
            this.closest('.answer-option').classList.remove('bg-light');
            this.closest('.answer-option').classList.add('bg-primary', 'bg-opacity-10', 'border-primary');
            this.closest('.answer-option').style.borderWidth = '2px';
            
            // Update progress
            updateProgress();
            
            // Re-render LaTeX
            setTimeout(() => {
                if (typeof renderMathInElement !== 'undefined') {
                    renderMathInElement(this.closest('.answer-option'), {
                        delimiters: [{left: '$$', right: '$$', display: true}, {left: '$', right: '$', display: false}],
                        throwOnError: false
                    });
                }
            }, 50);
        });
    });
    
    const duration = {{ $exam->duration }};
    let timeLeft = duration * 60;
    const timerElement = document.getElementById('timer');
    const examForm = document.getElementById('examForm');
    const submitBtn = document.getElementById('submitBtn');
    
    function updateTimer() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerElement.textContent = minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
        
        if (timeLeft <= 300) {
            timerElement.classList.add('text-danger');
        }
        
        if (timeLeft === 300) {
            alert('Warning: Only 5 minutes remaining!');
        }
        
        if (timeLeft <= 0) {
            alert('Time is up! Submitting exam automatically.');
            clearInterval(timerInterval);
            window.onbeforeunload = null;
            examForm.submit();
            return;
        }
        
        timeLeft--;
    }
    
    const timerInterval = setInterval(updateTimer, 1000);
    
    window.onbeforeunload = function(e) {
        e.preventDefault();
        return 'Are you sure you want to leave?';
    };
    
    examForm.addEventListener('submit', function(e) {
        e.preventDefault();
        console.log('Form submit event triggered');
        
        const answeredQuestions = document.querySelectorAll('input[type="radio"]:checked').length;
        console.log('Answered questions:', answeredQuestions, 'of', totalQuestions);
        
        let confirmMessage = 'Are you sure you want to submit your exam?';
        
        if (answeredQuestions < totalQuestions) {
            const unanswered = totalQuestions - answeredQuestions;
            confirmMessage = 'You have ' + unanswered + ' unanswered question(s). ' + confirmMessage;
        }
        
        console.log('Showing confirmation dialog');
        if (confirm(confirmMessage)) {
            console.log('User confirmed - submitting form');
            clearInterval(timerInterval);
            window.onbeforeunload = null;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';
            
            console.log('Form action:', this.action);
            console.log('Form method:', this.method);
            
            // Create a new form submit event without preventDefault
            // This bypasses the event listener
            HTMLFormElement.prototype.submit.call(this);
            console.log('Form submitted via HTMLFormElement.prototype.submit');
        } else {
            console.log('User cancelled submission');
        }
    });
});
</script>

<style>
.sticky-bottom {
    position: sticky;
    z-index: 1020;
}
</style>
@endpush
@endsection
