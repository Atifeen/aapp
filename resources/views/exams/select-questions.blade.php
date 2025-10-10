@extends('layouts.app')

@section('title', 'Select Questions - ' . $exam->title)

@push('styles')
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<style>
    body {
        background-color: #f8f9fa;
    }
    .filter-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border: none;
    }
    .table-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border: none;
        overflow: hidden;
    }
    .form-control, .form-select {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .question-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 1rem;
        transition: all 0.3s ease;
        margin-bottom: 1rem;
    }
    .question-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-color: #667eea;
    }
    .option-item {
        font-size: 0.9rem;
        line-height: 1.4;
        transition: all 0.3s ease;
        padding: 8px;
        margin: 4px 0;
        border-radius: 6px;
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
    }
    .question-meta .badge {
        font-size: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Filter Sidebar -->
        <div class="col-md-3">
            <div class="card filter-card">
                <div class="card-header bg-transparent border-0 pt-4">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-funnel me-2"></i>Filter Questions
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('exams.questions.select', $exam) }}" method="GET" id="filterForm">
                        <!-- Question Text Filter -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Question Text</label>
                            <input type="text" name="question_text" class="form-control" placeholder="Search in question text..." value="{{ request('question_text') }}">
                        </div>

                        <!-- Subject Filter -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Subject</label>
                            <select name="subject_id" class="form-select">
                                <option value="">All Subjects</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Chapter Filter -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Chapter</label>
                            <select name="chapter_id" class="form-select">
                                <option value="">All Chapters</option>
                                @foreach($chapters as $chapter)
                                    <option value="{{ $chapter->id }}" {{ request('chapter_id') == $chapter->id ? 'selected' : '' }}>
                                        {{ $chapter->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Source Type Filter -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Source Type</label>
                            <select name="source_type" class="form-select" id="source_type">
                                <option value="">All Types</option>
                                <option value="board" {{ request('source_type') == 'board' ? 'selected' : '' }}>Board</option>
                                <option value="university" {{ request('source_type') == 'university' ? 'selected' : '' }}>University</option>
                                <option value="custom" {{ request('source_type') == 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                        </div>

                        <!-- Source Name Filter -->
                        <div class="mb-3" id="sourceNameBoard" style="display:none;">
                            <label class="form-label fw-semibold">Board Name</label>
                            <select name="source_name" id="source_name_board" class="form-select">
                                <option value="">All Boards</option>
                                @foreach($boards as $board)
                                    <option value="{{ $board->name }}" {{ request('source_name') == $board->name ? 'selected' : '' }}>{{ $board->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3" id="sourceNameUniversity" style="display:none;">
                            <label class="form-label fw-semibold">University Name</label>
                            <select name="source_name" id="source_name_university" class="form-select">
                                <option value="">All Universities</option>
                                @foreach($universities as $university)
                                    <option value="{{ $university->name }}" {{ request('source_name') == $university->name ? 'selected' : '' }}>{{ $university->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3" id="sourceNameCustom" style="display:none;">
                            <label class="form-label fw-semibold">Custom Source Name</label>
                            <input type="text" name="source_name" id="source_name_custom" class="form-control" placeholder="Enter source name..." value="{{ request('source_name') }}">
                        </div>

                        <!-- Year Filter -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Year</label>
                            <input type="number" name="year" class="form-control" placeholder="e.g., 2023" value="{{ request('year') }}">
                        </div>

                        <!-- Filter Buttons -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-2"></i>Apply Filters
                            </button>
                            <a href="{{ route('exams.questions.select', $exam) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise me-2"></i>Clear All
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
                        <!-- Questions List -->
        <div class="col-md-9">
            <div class="card table-card">
                <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-0 pt-4">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-list-check me-2"></i>Select Questions for {{ $exam->title }}
                        <span class="badge bg-primary ms-2">{{ $questions->total() }} questions</span>
                    </h5>
                    <button type="button" class="btn btn-success" onclick="saveSelectedQuestions()">
                        <i class="bi bi-save me-1"></i>Save Selected
                    </button>
                </div>
                <div class="card-body">
                    <form id="questionForm" action="{{ route('exams.questions.attach', $exam) }}" method="POST">
                        @csrf
                        
                        <!-- Select All Option -->
                        <div class="mb-3 p-3 bg-light rounded">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                                <label class="form-check-label fw-semibold" for="selectAll">
                                    Select All Questions on This Page
                                </label>
                            </div>
                        </div>

                        <!-- Questions List -->
                        @forelse($questions as $question)
                            <div class="question-card">
                                <div class="d-flex">
                                    <!-- Checkbox -->
                                    <div class="me-3 pt-1">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input question-checkbox" 
                                                   name="question_ids[]" value="{{ $question->id }}"
                                                   {{ $exam->questions->contains($question->id) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    
                                    <!-- Question Content -->
                                    <div class="flex-grow-1">
                                        <!-- Question Header -->
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="question-header">
                                                <span class="badge bg-primary me-2">#{{ $question->id }}</span>
                                                <strong class="text-primary">Question</strong>
                                            </div>
                                            <div class="question-meta d-flex flex-wrap gap-1">
                                                @if($question->subject)
                                                    <span class="badge bg-info">{{ $question->subject->class }}</span>
                                                    <span class="badge bg-secondary">{{ $question->subject->name }}</span>
                                                @endif
                                                @if($question->chapter)
                                                    <span class="badge bg-light text-dark">{{ $question->chapter->name }}</span>
                                                @endif
                                                @if($question->source_type)
                                                    <span class="badge bg-warning text-dark">{{ ucfirst($question->source_type) }}</span>
                                                @endif
                                                @if($question->year)
                                                    <span class="badge bg-dark">{{ $question->year }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Question Text -->
                                        <div class="question-text mb-3">
                                            <p class="mb-2 fw-semibold">{!! $question->question_text !!}</p>
                                            @if($question->image)
                                                <div class="mb-2">
                                                    <img src="{{ asset('storage/' . $question->image) }}" alt="Question Image" class="img-fluid rounded shadow-sm" style="max-width:300px;">
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Options -->
                                        <div class="options-display">
                                            <div class="row g-2">
                                                @php
                                                    $options = ['a' => $question->option_a, 'b' => $question->option_b, 'c' => $question->option_c, 'd' => $question->option_d];
                                                    $correctOption = strtolower($question->correct_option);
                                                @endphp
                                                @foreach($options as $key => $option)
                                                    <div class="col-md-6">
                                                        <div class="option-item @if($correctOption === $key) bg-success text-white border-success @endif">
                                                            <strong>{{ $key }})</strong> {!! $option !!}
                                                            @if($correctOption === $key)
                                                                <i class="bi bi-check-circle-fill ms-2"></i>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        
                                        <!-- Additional Info -->
                                        @if($question->source_name)
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <i class="bi bi-building me-1"></i>Source: {{ $question->source_name }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
                                <h5 class="text-muted mt-3">No questions found</h5>
                                <p class="text-muted">Try adjusting your filters to find questions.</p>
                            </div>
                        @endforelse

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $questions->appends(request()->query())->links() }}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- MathJax for LaTeX rendering -->
<script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
<script>
    window.MathJax = {
        tex: {
            inlineMath: [['$', '$'], ['\\(', '\\)']],
            displayMath: [['$$', '$$'], ['\\[', '\\]']]
        }
    };
</script>

<script>
    // Handle "Select All" checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.question-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });

    // Submit selected questions
    function saveSelectedQuestions() {
        const form = document.getElementById('questionForm');
        const selectedCount = document.querySelectorAll('.question-checkbox:checked').length;
        
        if (selectedCount === 0) {
            alert('Please select at least one question.');
            return;
        }

        form.submit();
    }

    // Dynamic source name filtering
    document.addEventListener('DOMContentLoaded', function() {
        function toggleSourceName() {
            var type = document.getElementById('source_type').value;
            // Disable all source_name fields
            document.getElementById('source_name_board').disabled = true;
            document.getElementById('source_name_university').disabled = true;
            document.getElementById('source_name_custom').disabled = true;
            // Hide all
            document.getElementById('sourceNameBoard').style.display = 'none';
            document.getElementById('sourceNameUniversity').style.display = 'none';
            document.getElementById('sourceNameCustom').style.display = 'none';
            // Enable and show the selected one
            if(type === 'board') {
                document.getElementById('sourceNameBoard').style.display = '';
                document.getElementById('source_name_board').disabled = false;
            } else if(type === 'university') {
                document.getElementById('sourceNameUniversity').style.display = '';
                document.getElementById('source_name_university').disabled = false;
            } else if(type === 'custom') {
                document.getElementById('sourceNameCustom').style.display = '';
                document.getElementById('source_name_custom').disabled = false;
            }
        }
        document.getElementById('source_type').addEventListener('change', toggleSourceName);
        toggleSourceName();
    });
</script>
@endsection