@extends('layouts.app')

@section('title', 'Select Questions - ' . $exam->title)

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<style>
    body {
        background: #0f172a !important;
        color: #e2e8f0 !important;
    }
    .main-header {
        background: #334155;
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-radius: 0 0 15px 15px;
    }
    .filter-card {
        background: #1e293b !important;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        border: 1px solid #475569;
    }
    .table-card {
        background: #1e293b !important;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        border: 1px solid #475569;
        overflow: hidden;
    }
    .btn-custom {
        border-radius: 25px;
        padding: 0.5rem 1.5rem;
        font-weight: 500;
    }
    .form-control, .form-select {
        background-color: #334155 !important;
        color: #e2e8f0 !important;
        border: 1px solid #475569 !important;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        background-color: #334155 !important;
        color: #e2e8f0 !important;
        border-color: #15803d !important;
        box-shadow: 0 0 0 0.2rem rgba(21, 128, 61, 0.25) !important;
    }
    .table th {
        background-color: #334155 !important;
        border: none;
        font-weight: 600;
        color: #e2e8f0 !important;
    }
    .table td {
        background-color: #1e293b !important;
        color: #e2e8f0 !important;
        border: none;
        border-bottom: 1px solid #475569;
        vertical-align: middle;
    }
    .table {
        background-color: #1e293b !important;
    }
    .question-image {
        transition: all 0.3s ease;
        background: #334155;
        padding: 10px;
        border-radius: 8px;
        text-align: center;
    }
    .question-image img {
        transition: transform 0.3s ease;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }
    .question-image img:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0,0,0,0.5);
    }
    .image-controls {
        gap: 8px;
    }
    .btn-image-control {
        border-radius: 20px;
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
    }
    .options-display {
        background-color: #1e293b !important;
        padding: 8px;
        border-radius: 6px;
        border: 1px solid #475569;
    }
    .question-card {
        background: #1e293b !important;
        border: 1px solid #475569;
        border-radius: 10px;
        padding: 1rem;
        transition: all 0.3s ease;
        color: #e2e8f0;
    }
    .question-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        background: #2d3748 !important;
    }
    
    /* Custom Pagination Styles */
    .pagination-wrapper {
        background: #1e293b !important;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        padding: 1rem 1.5rem;
        margin: 2rem 0;
        border: 1px solid #475569;
    }
    
    .pagination {
        margin: 0;
        gap: 0.25rem;
    }
    
    .pagination .page-item .page-link {
        border: 1px solid #475569 !important;
        border-radius: 8px;
        color: #e2e8f0 !important;
        font-weight: 500;
        padding: 0.375rem 0.5rem;
        margin: 0 0.15rem;
        transition: all 0.3s ease;
        background: #334155 !important;
        min-width: 35px;
        height: 35px;
        text-align: center;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .pagination .page-item .page-link:hover {
        border-color: #15803d !important;
        background-color: #15803d !important;
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(21, 128, 61, 0.3);
    }
    
    .pagination .page-item.active .page-link {
        background-color: #15803d !important;
        border-color: #15803d !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(21, 128, 61, 0.4);
        transform: translateY(-1px);
    }
    
    .pagination .page-item.disabled .page-link {
        background-color: #1e293b !important;
        border-color: #475569 !important;
        color: #64748b !important;
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
        color: #94a3b8 !important;
        font-size: 0.85rem;
        margin-bottom: 0.75rem;
    }

    /* Selection styles */
    .question-checkbox {
        transform: scale(1.3);
        margin-right: 0.5rem;
    }
    
    .selected-question {
        background-color: #334155 !important;
        border-color: #15803d !important;
    }
    
    .random-selection-card {
        background: #334155 !important;
        color: white;
        border-radius: 15px;
        margin-bottom: 1rem;
        border: 1px solid #475569;
    }
    
    /* Button styles */
    .btn-primary, .btn-success, .btn-info {
        background-color: #15803d !important;
        border-color: #15803d !important;
        color: white !important;
        box-shadow: none !important;
    }
    
    .btn-primary:hover, .btn-success:hover, .btn-info:hover {
        background-color: #166534 !important;
        border-color: #166534 !important;
    }
    
    .btn-secondary {
        background-color: #475569 !important;
        border-color: #475569 !important;
        color: white !important;
    }
    
    .btn-secondary:hover {
        background-color: #334155 !important;
        border-color: #334155 !important;
    }
    
    .btn-outline-primary, .btn-outline-secondary {
        background-color: #334155 !important;
        border-color: #475569 !important;
        color: #e2e8f0 !important;
    }
    
    .btn-outline-primary:hover, .btn-outline-secondary:hover {
        background-color: #475569 !important;
        border-color: #475569 !important;
        color: white !important;
    }
    
    .btn-outline-danger {
        background-color: #7f1d1d !important;
        border-color: #dc2626 !important;
        color: white !important;
    }
    
    .btn-outline-danger:hover {
        background-color: #dc2626 !important;
        border-color: #dc2626 !important;
    }
    
    .btn-outline-info {
        background-color: #334155 !important;
        border-color: #475569 !important;
        color: #e2e8f0 !important;
    }
    
    .btn-outline-info:hover {
        background-color: #15803d !important;
        border-color: #15803d !important;
        color: white !important;
    }
    
    .btn-danger {
        background-color: #dc2626 !important;
        border-color: #dc2626 !important;
    }
    
    .badge {
        background-color: #15803d !important;
        color: white !important;
    }
    
    .badge.bg-light {
        background-color: #334155 !important;
        color: white !important;
    }
    
    .badge.bg-white {
        background-color: #475569 !important;
        color: white !important;
    }
    
    /* Alert styles */
    .alert-success {
        background-color: #15803d !important;
        border-color: #15803d !important;
        color: white !important;
    }
    
    .alert-danger {
        background-color: #dc2626 !important;
        border-color: #dc2626 !important;
        color: white !important;
    }
    
    /* Card header styles */
    .card-header {
        background-color: #334155 !important;
        color: #e2e8f0 !important;
        border-bottom: 1px solid #475569;
    }
    
    .card-body {
        background-color: #1e293b !important;
        color: #e2e8f0 !important;
    }
    
    /* Text colors */
    h1, h2, h3, h4, h5, h6, p, label, span {
        color: #e2e8f0 !important;
    }
    
    .text-muted {
        color: #94a3b8 !important;
    }
    
    .text-dark {
        color: #e2e8f0 !important;
    }
    
    /* Badge overrides */
    .badge.bg-info, .badge.bg-secondary, .badge.bg-warning, .badge.bg-dark {
        background-color: #15803d !important;
        color: white !important;
    }
    
    /* Table header styling */
    .table thead th {
        background-color: #334155 !important;
        color: #e2e8f0 !important;
        border: none;
    }
    
    /* Ensure all table cells are dark */
    .table tbody tr td {
        background-color: #1e293b !important;
        color: #e2e8f0 !important;
    }
    
    /* Override Bootstrap default colors */
    .bg-light {
        background-color: #1e293b !important;
        color: #e2e8f0 !important;
    }
    
    /* Form labels */
    .form-label, label {
        color: #e2e8f0 !important;
    }
    
    /* Question card hover effect - remove green border */
    .question-card:hover {
        background-color: #2d3748 !important;
        border-color: #475569 !important;
    }
    
    /* Empty state text */
    .text-center i, .text-center h5, .text-center p {
        color: #94a3b8 !important;
    }
</style>

<body>
<div class="main-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-1"><i class="bi bi-question-circle me-3"></i>Select Questions for: {{ $exam->title }}</h1>
                <p class="mb-0">Choose questions to include in your exam</p>
            </div>
            <div class="text-end">
                <span class="badge" style="background-color: #334155 !important; color: white !important;">
                    <i class="bi bi-list-check me-2"></i><span id="selectedCount">{{ $exam->questions->count() }}</span> selected
                </span>
                <div class="mt-2">
                    <span class="badge" style="background-color: #475569 !important; color: white !important;">
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
                            <option value="{{ $subject->id }}" data-class="{{ $subject->class }}" @if(request('subject_id') == $subject->id) selected @endif>{{ $subject->name }} ({{ $subject->class }})</option>
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
                <i class="bi bi-check-square me-2"></i>Select All (Page)
            </button>
            <button type="button" class="btn btn-outline-secondary btn-custom" onclick="deselectAll()">
                <i class="bi bi-square me-2"></i>Deselect All (Page)
            </button>
            <button type="button" class="btn btn-outline-danger btn-custom" onclick="clearAllSelections()">
                <i class="bi bi-trash me-2"></i>Clear All Selections
            </button>
        </div>
        <button type="button" class="btn btn-success btn-custom" onclick="saveSelectedQuestions()" id="saveBtn">
            <i class="bi bi-plus-circle me-2"></i>Add Selected Questions (<span id="saveCount">0</span>)
        </button>
    </div>

    <!-- Questions Table -->
    <div class="card table-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="px-4 py-3" style="width: 60px;">
                                <input type="checkbox" id="selectAllCheckbox" class="form-check-input" style="transform: scale(1.3);">
                            </th>
                            <th class="px-4 py-3">Question & Details</th>
                        </tr>
                    </thead>
                    <tbody>
            @foreach($questions as $question)
            <tr class="border-0 question-row" data-question-id="{{ $question->id }}">
                <td class="px-4 py-4 text-center">
                    <input type="checkbox" name="question_ids[]" value="{{ $question->id }}" 
                           class="form-check-input question-checkbox" style="transform: scale(1.3);"
                           {{ $exam->questions->contains($question->id) ? 'checked' : '' }}>
                </td>
                <td class="px-4 py-4">
                    <div class="question-card">
                        <!-- Question Header with Badge -->
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="fw-bold mb-0" style="color: #e2e8f0 !important;">
                                <i class="bi bi-hash me-1"></i>Question #{{ $question->id }}
                            </h6>
                            <div class="d-flex gap-1">
                                @if($question->subject)
                                    <span class="badge" style="background-color: #15803d !important; color: white !important;">{{ $question->subject->name }}</span>
                                @endif
                                @if($question->chapter)
                                    <span class="badge" style="background-color: #15803d !important; color: white !important;">{{ $question->chapter->name }}</span>
                                @endif
                                @if($question->year)
                                    <span class="badge" style="background-color: #15803d !important; color: white !important;">{{ $question->year }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Question Text -->
                        <div class="question-text mb-3">
                            <p class="mb-2" style="color: #e2e8f0 !important;">{!! $question->question_text !!}</p>
                        </div>
                        
                        <!-- Image Section -->
                        @if($question->image)
                            <div class="image-section mb-3">
                                <div class="image-controls d-flex align-items-center mb-2">
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
                                         style="max-width: 100%; max-height: 300px; object-fit: contain;"
                                         loading="lazy">
                                </div>
                            </div>
                        @endif
                        
                        <!-- Options with Correct Answer Highlighted -->
                        <div class="options-display">
                            <div class="row g-2">
                                @php
                                    $options = ['a', 'b', 'c', 'd'];
                                    $hasOptions = false;
                                    foreach($options as $opt) {
                                        if($question->{'option_' . $opt}) {
                                            $hasOptions = true;
                                            break;
                                        }
                                    }
                                @endphp
                                
                                @if($hasOptions)
                                    @foreach($options as $option)
                                        @if($question->{'option_' . $option})
                                            <div class="col-md-6">
                                                <div class="option-item p-2 rounded" style="background-color: {{ $question->correct_option === $option ? '#166534' : '#1e293b' }} !important; border: 1px solid {{ $question->correct_option === $option ? '#15803d' : '#475569' }} !important; color: #e2e8f0 !important;">
                                                    <strong>{{ strtoupper($option) }})</strong> {{ $question->{'option_' . $option} }}
                                                    @if($question->correct_option === $option)
                                                        <i class="bi bi-check-circle-fill ms-2"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="col-12">
                                        <div class="option-item p-2 rounded" style="background-color: #1e293b !important; border: 1px solid #475569 !important;">
                                            <em style="color: #94a3b8 !important;">No multiple choice options available</em>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Additional Info -->
                        @if($question->source_name)
                            <div class="mt-2">
                                <small style="color: #94a3b8 !important;">
                                    <i class="bi bi-building me-1"></i>Source: {{ $question->source_name }}
                                </small>
                            </div>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination Links -->
    @if($questions->hasPages())
        <div class="pagination-wrapper">
            <div class="pagination-info text-center">
                <small>
                    Showing <strong>{{ $questions->firstItem() }}</strong> to <strong>{{ $questions->lastItem() }}</strong> 
                    of <strong>{{ $questions->total() }}</strong> questions
                    (Page <strong>{{ $questions->currentPage() }}</strong> of <strong>{{ $questions->lastPage() }}</strong>)
                </small>
            </div>
            <div class="d-flex justify-content-center">
                {{ $questions->links('custom-pagination') }}
            </div>
        </div>
    @endif

    @if($questions->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
            <h5 class="text-muted mt-3">No questions found</h5>
            <p class="text-muted">Try adjusting your filters or add some questions to get started.</p>
        </div>
    @endif

    <!-- Hidden Form for Submission -->
    <form id="questionForm" action="{{ route('exams.questions.attach', $exam) }}" method="POST" style="display: none;">
        @csrf
        <div id="selectedQuestions"></div>
    </form>
</div>

<!-- MathJax Configuration -->
<script>
MathJax = {
  tex: {
    inlineMath: [['\\(', '\\)']],
    displayMath: [['\\[', '\\]']]
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

// Show/hide source name fields based on source type
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
                const subjectSelect = document.getElementById('subject_id');
                const currentValue = subjectSelect.value;
                
                // Clear current options except the first one
                subjectSelect.innerHTML = '<option value="">All Subjects</option>';
                
                // Add subjects based on selected class
                const subjects = @json($subjects);
                subjects.forEach(subject => {
                    if (!selectedClass || subject.class === selectedClass) {
                        const option = new Option(`${subject.name} (${subject.class})`, subject.id);
                        option.setAttribute('data-class', subject.class);
                        subjectSelect.add(option);
                    }
                });
                
                // Try to restore the previous selection if it matches the filter
                if (currentValue) {
                    const matchingOption = subjectSelect.querySelector(`option[value="${currentValue}"]`);
                    if (matchingOption) {
                        subjectSelect.value = currentValue;
                    }
                }
            });
        }
    }
    
    handleClassFilter();

    // Selection functionality
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const questionCheckboxes = document.querySelectorAll('.question-checkbox');

    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        questionCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
            const questionId = parseInt(checkbox.value);
            if (this.checked) {
                globalSelectedQuestions.add(questionId);
            } else {
                globalSelectedQuestions.delete(questionId);
            }
        });
        saveGlobalSelections();
        updateSelectionState();
    });

    // Individual checkbox change
    questionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const questionId = parseInt(this.value);
            if (this.checked) {
                globalSelectedQuestions.add(questionId);
            } else {
                globalSelectedQuestions.delete(questionId);
            }
            saveGlobalSelections();
            updateSelectionState();
        });
    });

    // Initialize state
    updateSelectionState();
});

// Global selection management
let globalSelectedQuestions = new Set();

// Load selections from localStorage on page load
function loadGlobalSelections() {
    const examId = {{ $exam->id }};
    const storageKey = `exam_${examId}_selected_questions`;
    const stored = localStorage.getItem(storageKey);
    if (stored) {
        globalSelectedQuestions = new Set(JSON.parse(stored));
    }
}

// Save selections to localStorage
function saveGlobalSelections() {
    const examId = {{ $exam->id }};
    const storageKey = `exam_${examId}_selected_questions`;
    localStorage.setItem(storageKey, JSON.stringify([...globalSelectedQuestions]));
}

// Clear selections from localStorage
function clearGlobalSelections() {
    const examId = {{ $exam->id }};
    const storageKey = `exam_${examId}_selected_questions`;
    localStorage.removeItem(storageKey);
}

// Update checkbox states based on global selection
function syncCheckboxesWithGlobalSelection() {
    document.querySelectorAll('.question-checkbox').forEach(checkbox => {
        const questionId = parseInt(checkbox.value);
        checkbox.checked = globalSelectedQuestions.has(questionId);
    });
}

// Initialize global selections on page load
document.addEventListener('DOMContentLoaded', function() {
    loadGlobalSelections();
    syncCheckboxesWithGlobalSelection();
    updateSelectionState();
});

// Global function for updating selection state
function updateSelectionState() {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const questionCheckboxes = document.querySelectorAll('.question-checkbox');
    const selectedCountSpan = document.getElementById('selectedCount');
    const saveCountSpan = document.getElementById('saveCount');
    const saveBtn = document.getElementById('saveBtn');
    
    const checkedBoxes = document.querySelectorAll('.question-checkbox:checked');
    const pageCount = checkedBoxes.length;
    const globalCount = globalSelectedQuestions.size;
    
    if (selectedCountSpan) selectedCountSpan.textContent = globalCount;
    if (saveCountSpan) saveCountSpan.textContent = globalCount;
    
    if (saveBtn) {
        if (globalCount > 0) {
            saveBtn.disabled = false;
            saveBtn.classList.remove('btn-secondary');
            saveBtn.classList.add('btn-success');
        } else {
            saveBtn.disabled = true;
            saveBtn.classList.remove('btn-success');
            saveBtn.classList.add('btn-secondary');
        }
    }
    
    // Update select all checkbox state (only for current page)
    if (selectAllCheckbox) {
        const allChecked = questionCheckboxes.length > 0 && pageCount === questionCheckboxes.length;
        const someChecked = pageCount > 0;
        
        selectAllCheckbox.checked = allChecked;
        selectAllCheckbox.indeterminate = someChecked && !allChecked;
    }
}

// Select all questions on page
function selectAll() {
    document.querySelectorAll('.question-checkbox').forEach(checkbox => {
        checkbox.checked = true;
        const questionId = parseInt(checkbox.value);
        globalSelectedQuestions.add(questionId);
    });
    saveGlobalSelections();
    updateSelectionState();
}

// Deselect all questions on page  
function deselectAll() {
    document.querySelectorAll('.question-checkbox').forEach(checkbox => {
        checkbox.checked = false;
        const questionId = parseInt(checkbox.value);
        globalSelectedQuestions.delete(questionId);
    });
    saveGlobalSelections();
    updateSelectionState();
}

// Clear all global selections
function clearAllSelections() {
    if (globalSelectedQuestions.size === 0) {
        alert('No questions are currently selected.');
        return;
    }
    
    if (confirm(`Are you sure you want to clear all ${globalSelectedQuestions.size} selected questions? This cannot be undone.`)) {
        globalSelectedQuestions.clear();
        clearGlobalSelections();
        syncCheckboxesWithGlobalSelection();
        updateSelectionState();
    }
}

// Random selection
function selectRandomQuestions() {
    const countInput = document.getElementById('randomCount');
    
    if (!countInput) {
        alert('Random count input not found!');
        return;
    }
    
    const count = parseInt(countInput.value);
    
    if (!count || count < 1) {
        alert('Please enter a valid number of questions to select.');
        return;
    }

    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Selecting...';

    // Get current filter parameters
    const urlParams = new URLSearchParams(window.location.search);
    const filterData = {
        count: count,
        question_text: urlParams.get('question_text') || '',
        class: urlParams.get('class') || '',
        subject_id: urlParams.get('subject_id') || '',
        chapter_id: urlParams.get('chapter_id') || '',
        year: urlParams.get('year') || '',
        source_type: urlParams.get('source_type') || ''
    };

    // Make AJAX request to get random questions
    fetch(`{{ route('exams.questions.random', $exam) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(filterData)
    })
    .then(response => response.json())
    .then(data => {
        // Restore button state
        button.disabled = false;
        button.innerHTML = originalText;

        if (data.success) {
            // Clear all current global selections
            globalSelectedQuestions.clear();
            
            // Add the returned questions to global selection
            data.questions.forEach(question => {
                globalSelectedQuestions.add(question.id);
            });
            
            // Save and sync with current page checkboxes
            saveGlobalSelections();
            syncCheckboxesWithGlobalSelection();
            updateSelectionState();
            alert(data.message);
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        // Restore button state
        button.disabled = false;
        button.innerHTML = originalText;
        
        console.error('Error:', error);
        alert('An error occurred while selecting random questions. Please try again.');
    });
}

// Save selected questions
function saveSelectedQuestions() {
    if (globalSelectedQuestions.size === 0) {
        alert('Please select at least one question.');
        return;
    }
    
    // Add selected question IDs to hidden form
    const form = document.getElementById('questionForm');
    const container = document.getElementById('selectedQuestions');
    container.innerHTML = '';
    
    globalSelectedQuestions.forEach(questionId => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'question_ids[]';
        input.value = questionId;
        container.appendChild(input);
    });
    
    // Clear selections after saving
    clearGlobalSelections();
    globalSelectedQuestions.clear();
    
    form.submit();
}

// Toggle image display function
function toggleImage(imageId) {
    const imageDiv = document.getElementById(imageId);
    const button = document.querySelector(`button[onclick="toggleImage('${imageId}')"]`);
    
    if (imageDiv.style.display === 'none' || imageDiv.style.display === '') {
        imageDiv.style.display = 'block';
        button.innerHTML = '<i class="bi bi-eye-slash me-1"></i>Hide Image';
    } else {
        imageDiv.style.display = 'none';
        button.innerHTML = '<i class="bi bi-eye me-1"></i>Show Image';
    }
}

// Auto hide alerts
setTimeout(()=>{
    document.querySelectorAll('.alert').forEach(alert=>{
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 3000);
</script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>