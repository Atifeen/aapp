@extends('layouts.app')

@section('title', 'Select Questions - ' . $exam->title)

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<style>
    body {
        background-color: #f8f9fa;
    }
    .main-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-radius: 0 0 15px 15px;
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
    .btn-custom {
        border-radius: 25px;
        padding: 0.5rem 1.5rem;
        font-weight: 500;
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
    .table th {
        background-color: #f8f9fa;
        border: none;
        font-weight: 600;
        color: #495057;
    }
    .question-image {
        transition: all 0.3s ease;
        background: #f8f9fa;
        padding: 10px;
        border-radius: 8px;
        text-align: center;
    }
    .question-image img {
        transition: transform 0.3s ease;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .question-image img:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .image-controls {
        gap: 8px;
    }
    .btn-image-control {
        border-radius: 20px;
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
    }
    .table td {
        border: none;
        border-bottom: 1px solid #dee2e6;
        vertical-align: middle;
    }
    .options-display {
        background-color: #f8f9fa;
        padding: 8px;
        border-radius: 6px;
        border-left: 3px solid #dee2e6;
    }
    .question-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 1rem;
        transition: all 0.3s ease;
    }
    .question-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-color: #667eea;
    }
    
    /* Custom Pagination Styles */
    .pagination-wrapper {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        padding: 1rem 1.5rem;
        margin: 2rem 0;
    }
    
    .pagination {
        margin: 0;
        gap: 0.25rem;
    }
    
    .pagination .page-item .page-link {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        color: #495057;
        font-weight: 500;
        padding: 0.375rem 0.5rem;
        margin: 0 0.15rem;
        transition: all 0.3s ease;
        background: white;
        min-width: 35px;
        height: 35px;
        text-align: center;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .pagination .page-item .page-link:hover {
        border-color: #667eea;
        background-color: #667eea;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
    }
    
    .pagination .page-item.active .page-link {
        background-color: #667eea;
        border-color: #667eea;
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        transform: translateY(-1px);
    }
    
    .pagination .page-item.disabled .page-link {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #6c757d;
        cursor: not-allowed;
    }
    
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        padding: 0.375rem 0.75rem;
        font-weight: 600;
        min-width: auto;
    }
    
    .pagination .page-link {
        line-height: 1;
    }
    
    /* Ensure consistent button heights */
    .pagination .page-item .page-link,
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        height: 35px;
        box-sizing: border-box;
    }
    
    .pagination-info {
        color: #6c757d;
        font-size: 0.85rem;
        margin-bottom: 0.75rem;
    }

    /* Selection styles */
    .question-checkbox {
        transform: scale(1.2);
        margin-right: 0.5rem;
    }
    
    .selected-question {
        background-color: #e7f3ff;
        border-color: #667eea;
    }
    
    .random-selection-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        margin-bottom: 1rem;
    }
</style>

@section('content')
<div class="main-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-1"><i class="bi bi-question-circle me-3"></i>Select Questions for: {{ $exam->title }}</h1>
                <p class="mb-0">Choose questions to include in your exam</p>
            </div>
            <div class="text-end">
                <span class="badge badge-custom bg-light text-dark">
                    <i class="bi bi-list-check me-2"></i><span id="selectedCount">0</span> selected
                </span>
                <div class="mt-2">
                    <span class="badge bg-white text-dark">
                        <i class="bi bi-search me-2"></i>{{ $questions->total() }} total questions
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 15px; border: none;">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 15px; border: none;">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Random Selection Card -->
    <div class="card random-selection-card">
        <div class="card-body">
            <h5 class="card-title mb-3"><i class="bi bi-shuffle me-2"></i>Random Selection</h5>
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Number of Questions</label>
                    <input type="number" id="randomCount" class="form-control" min="1" max="{{ $questions->total() }}" placeholder="e.g., 10">
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-light btn-custom" onclick="selectRandomQuestions()">
                        <i class="bi bi-shuffle me-2"></i>Select Random
                    </button>
                </div>
                <div class="col-md-6">
                    <small class="text-light">Select a random set of questions from the current filtered results</small>
                </div>
            </div>
        </div>
    </div>
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
    </div>

    <!-- Filter Form -->
    <div class="card filter-card mb-4">
        <div class="card-header bg-transparent border-0 pt-4">
            <h5 class="card-title mb-0"><i class="bi bi-funnel me-2"></i>Filter Questions</h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3" action="{{ route('exams.questions.select', $exam) }}">
                <div class="col-md-4">
                    <label for="question_text" class="form-label fw-semibold">Question Text</label>
                    <input type="text" name="question_text" id="question_text" class="form-control" placeholder="Search in question text..." value="{{ request('question_text') }}">
                </div>
                <div class="col-md-2">
                    <label for="class" class="form-label fw-semibold">Class</label>
                    <select name="class" id="class" class="form-select">
                        <option value="">All Classes</option>
                        <option value="SSC" @if(request('class') == 'SSC') selected @endif>SSC</option>
                        <option value="HSC" @if(request('class') == 'HSC') selected @endif>HSC</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="subject_id" class="form-label fw-semibold">Subject</label>
                    <select name="subject_id" id="subject_id" class="form-select">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" @if(request('subject_id') == $subject->id) selected @endif>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="chapter_id" class="form-label fw-semibold">Chapter</label>
                    <select name="chapter_id" id="chapter_id" class="form-select">
                        <option value="">All Chapters</option>
                        @foreach($chapters as $chapter)
                            <option value="{{ $chapter->id }}" @if(request('chapter_id') == $chapter->id) selected @endif>{{ $chapter->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="year" class="form-label fw-semibold">Year</label>
                    <input type="number" name="year" id="year" class="form-control" placeholder="e.g., 2023" value="{{ request('year') }}">
                </div>
                <div class="col-md-2">
                    <label for="source_type" class="form-label fw-semibold">Source Type</label>
                    <select name="source_type" id="source_type" class="form-select">
                        <option value="">All Types</option>
                        <option value="board" @if(request('source_type') == 'board') selected @endif>Board</option>
                        <option value="university" @if(request('source_type') == 'university') selected @endif>University</option>
                        <option value="custom" @if(request('source_type') == 'custom') selected @endif>Custom</option>
                    </select>
                </div>
                <div class="col-md-3" id="sourceNameBoard" style="display:none;">
                    <label for="source_name_board" class="form-label fw-semibold">Board Name</label>
                    <select name="source_name" id="source_name_board" class="form-select">
                        <option value="">All Boards</option>
                        @foreach($boards as $board)
                            <option value="{{ $board->name }}" @if(request('source_name') == $board->name) selected @endif>{{ $board->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3" id="sourceNameUniversity" style="display:none;">
                    <label for="source_name_university" class="form-label fw-semibold">University Name</label>
                    <select name="source_name" id="source_name_university" class="form-select">
                        <option value="">All Universities</option>
                        @foreach($universities as $university)
                            <option value="{{ $university->name }}" @if(request('source_name') == $university->name) selected @endif>{{ $university->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3" id="sourceNameCustom" style="display:none;">
                    <label for="source_name_custom" class="form-label fw-semibold">Custom Source</label>
                    <input type="text" name="source_name" id="source_name_custom" class="form-control" placeholder="Enter source name" value="{{ request('source_name') }}">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-custom me-2">
                        <i class="bi bi-search me-2"></i>Apply Filters
                    </button>
                    <a href="{{ route('exams.questions.select', $exam) }}" class="btn btn-outline-secondary btn-custom">
                        <i class="bi bi-arrow-clockwise me-2"></i>Clear Filters
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary btn-custom" onclick="selectAll()">
                <i class="bi bi-check-square me-2"></i>Select All
            </button>
            <button type="button" class="btn btn-outline-secondary btn-custom" onclick="deselectAll()">
                <i class="bi bi-square me-2"></i>Deselect All
            </button>
        </div>
        <button type="button" class="btn btn-success btn-custom" onclick="saveSelectedQuestions()" id="saveBtn" disabled>
            <i class="bi bi-plus-circle me-2"></i>Add Selected Questions
        </button>
    </div>
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

        // Handle class filtering for subjects
        function handleClassFilter() {
            const classSelect = document.getElementById('class');
            
            if (classSelect) {
                classSelect.addEventListener('change', function() {
                    const selectedClass = this.value;
                    
                    // If no class selected, reload page to show all subjects
                    if (!selectedClass) {
                        // Remove class parameter and reload
                        const url = new URL(window.location);
                        url.searchParams.delete('class');
                        url.searchParams.delete('subject_id'); // Clear subject selection
                        window.location.href = url.toString();
                        return;
                    }
                    
                    // If class is selected, reload page with class filter
                    const url = new URL(window.location);
                    url.searchParams.set('class', selectedClass);
                    url.searchParams.delete('subject_id'); // Clear subject selection
                    window.location.href = url.toString();
                });
            }
        }
        
        handleClassFilter();
    });
</script>
@endsection