<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Questions - AAPP</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<style>
    body {
        background-color: #0f172a;
    }
    .main-header {
        background-color: #1e293b;
        color: white;
        padding: 1.5rem 1.75rem;
        margin-bottom: 2rem;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }
    .main-header h2 {
        font-size: 1.75rem;
        font-weight: 600;
    }
    .main-header p {
        font-size: 1rem;
        opacity: 0.9;
    }
    .filter-card {
        background: #1e293b;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        border: none;
    }
    .table-card {
        background: #1e293b;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
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
        border: 2px solid #475569;
        background-color: #334155;
        color: #e2e8f0;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #15803d;
        box-shadow: 0 0 0 0.2rem rgba(21, 128, 61, 0.25);
        background-color: #334155;
        color: #e2e8f0;
    }
    .form-control::placeholder {
        color: #94a3b8;
    }
    .form-select option {
        background-color: #334155;
        color: #e2e8f0;
    }
    .table th {
        background-color: #1e293b;
        border: none;
        font-weight: 600;
        color: #e2e8f0;
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
        box-shadow: 0 4px 12px rgba(0,0,0,0.4);
    }
    .image-controls {
        gap: 8px;
    }
    .btn-image-control {
        border-radius: 20px;
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
    }
    }
    .table td {
        border: none;
        border-bottom: 1px solid #475569;
        vertical-align: middle;
        color: #e2e8f0;
        background-color: #1e293b;
    }
    .table tbody tr:hover {
        background-color: #2d3748;
    }
    .options-display {
        background-color: #1e293b;
        padding: 8px;
        border-radius: 6px;
        border-left: none;
        color: #e2e8f0;
    }
    .question-card {
        background: #1e293b;
        border: 1px solid #475569;
        border-radius: 10px;
        padding: 1rem;
        transition: all 0.3s ease;
        color: #e2e8f0;
    }
    .question-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        border-color: #0F172A;
        
    }
    
    /* Custom Pagination Styles */
    .pagination-wrapper {
        background: #1e293b;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
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
        border-color: #15803d;
        background-color: #15803d;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(21, 128, 61, 0.3);
    }
    
    .pagination .page-item.active .page-link {
        background-color: #15803d;
        border-color: #15803d;
        color: white;
        box-shadow: 0 4px 12px rgba(21, 128, 61, 0.4);
        transform: translateY(-1px);
    }
    
    .pagination .page-item.disabled .page-link {
        background-color: #334155;
        border-color: #475569;
        color: #64748b;
        cursor: not-allowed;
    }
    
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        padding: 0.375rem 0.75rem;
        font-weight: 600;
        min-width: auto;
        background-color: #334155;
        border-color: #475569;
        color: #e2e8f0;
    }
    
    .pagination .page-link {
        line-height: 1;
        background-color: #334155;
        border-color: #475569;
        color: #e2e8f0;
    }
    
    /* Ensure consistent button heights */
    .pagination .page-item .page-link,
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        height: 35px;
        box-sizing: border-box;
    }
    
    .pagination-info {
        color: #94a3b8;
        font-size: 0.85rem;
        margin-bottom: 0.75rem;
    }
    .option-item {
        font-size: 0.9rem;
        line-height: 1.4;
        transition: all 0.3s ease;
        color: #e2e8f0;
    }
    .question-meta .badge {
        font-size: 0.75rem;
    }
    
    /* Button Styles */
    .btn-primary, .btn-success {
        background-color: #15803d !important;
        border-color: #15803d !important;
        box-shadow: none !important;
    }
    .btn-primary:hover, .btn-success:hover {
        background-color: #166534 !important;
        border-color: #166534 !important;
        box-shadow: none !important;
    }
    .btn-primary:focus, .btn-primary:active,
    .btn-success:focus, .btn-success:active {
        background-color: #166534 !important;
        border-color: #166534 !important;
        box-shadow: none !important;
    }
    
    /* Alert Styles */
    .alert-success {
        background-color: #15803d;
        border-color: #15803d;
        color: white;
    }
    .alert-danger {
        background-color: #dc2626;
        border-color: #dc2626;
        color: white;
    }
    
    /* Badge Styles */
    .badge-custom {
        background-color: #334155 !important;
        color: #e2e8f0 !important;
    }
    
    /* Card Header */
    .card-header {
        color: #e2e8f0;
    }
    
    /* Override any white backgrounds */
    .table, .table tbody, .table tbody tr, .table tbody tr td {
        background-color: #1e293b !important;
        color: #e2e8f0 !important;
    }
    
    .table thead tr th {
        background-color: #334155 !important;
    }
    
    /* Options specific styling */
    .option-item, .options-display div, .options-display p {
        background-color: transparent !important;
        color: #e2e8f0 !important;
    }
    
    /* Ensure all text is visible */
    p, span, div, small, label {
        color: #e2e8f0;
    }
    
    .text-muted {
        color: #94a3b8 !important;
    }
    
    .fw-semibold, .form-label {
        color: #e2e8f0 !important;
    }
</style>
</head>
<body>

<div class="container">
    <div class="main-header mt-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-2"><i class="bi bi-question-circle me-2"></i>Questions Management</h2>
                <p class="mb-0">Manage and organize exam questions efficiently</p>
            </div>
            <div class="col-md-4 text-end">
                <span class="badge badge-custom px-3 py-2 fs-6">
                    <i class="bi bi-list-check me-2"></i>{{ $questions->count() }} question{{ $questions->count() !== 1 ? 's' : '' }} found
                </span>
            </div>
        </div>
    </div>

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

    <!-- Filter Form -->
    <div class="card filter-card mb-4">
        <div class="card-header bg-transparent border-0 pt-4">
            <h5 class="card-title mb-0"><i class="bi bi-funnel me-2"></i>Filter Questions</h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
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
                    <label for="source_name_custom" class="form-label fw-semibold">Custom Source Name</label>
                    <input type="text" name="source_name" id="source_name_custom" class="form-control" placeholder="Enter source name..." value="{{ request('source_name') }}">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-custom me-2">
                        <i class="bi bi-search me-2"></i>Apply Filters
                    </button>
                    <a href="{{ route('questions.index') }}" class="btn btn-outline-secondary btn-custom">
                        <i class="bi bi-arrow-clockwise me-2"></i>Clear All
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Question Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0 text-muted"><i class="bi bi-table me-2"></i>Questions Table</h5>
        <button class="btn btn-success btn-custom" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
            <i class="bi bi-plus-circle me-2"></i>Add New Question
        </button>
    </div>

    <!-- Questions Table -->
    <div class="card table-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="px-4 py-3">Question & Details</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
            @foreach($questions as $question)
            <tr class="border-0">
                <td class="px-4 py-4">
                    <div class="question-card">
                        <!-- Question Header -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="question-header">
                                <span class="badge bg-primary me-2">#{{ $question->id }}</span>
                                <strong class="text-primary">Question {{ $loop->iteration }}</strong>
                            </div>
                            <div class="question-meta d-flex flex-wrap gap-1">
                                @if($question->subject)
                                    <span class="badge" style="background-color: #15803d;">{{ $question->subject->class }}</span>
                                    <span class="badge" style="background-color: #15803d;">{{ $question->subject->name }}</span>
                                @endif
                                @if($question->chapter)
                                    <span class="badge" style="background-color: #15803d;">{{ $question->chapter->name }}</span>
                                @endif
                                @if($question->source_type)
                                    <span class="badge" style="background-color: #15803d;">{{ ucfirst($question->source_type) }}</span>
                                @endif
                                @if($question->year)
                                    <span class="badge" style="background-color: #15803d;">{{ $question->year }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Question Text -->
                        <div class="question-text mb-3">
                            <p class="mb-2 fw-semibold">{!! $question->question_text !!}</p>
                            @if($question->image)
                                <div class="mb-3">
                                    <div class="d-flex image-controls align-items-center mb-2">
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
                        </div>
                        
                        <!-- Options with Correct Answer Highlighted -->
                        <div class="options-display">
                            <div class="row g-2">
                                @php
                                    $options = ['a' => $question->option_a, 'b' => $question->option_b, 'c' => $question->option_c, 'd' => $question->option_d];
                                    $correctOption = strtolower($question->correct_option);
                                @endphp
                                @foreach($options as $key => $option)
                                    <div class="col-md-6">
                                        <div class="option-item p-2 rounded border @if($correctOption === $key) bg-success text-white border-success @else bg-light @endif">
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
                </td>
                <td class="px-4 py-4 text-center">
                    <div class="d-flex flex-column gap-2" role="group">
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#editModal{{ $question->id }}" title="Edit">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </button>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $question->id }}" title="Delete">
                            <i class="bi bi-trash me-1"></i>Delete
                        </button>
                    </div>
                </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal{{ $question->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('questions.update', $question->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Question #{{ $question->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Question Text</label>
                                    <textarea name="question_text" class="form-control" rows="3" required>{{ $question->question_text }}</textarea>
                                    <small class="text-muted">Use \( \) for inline math, \[ \] for display math</small>
                                </div>

                                <div class="mb-3">
                                    <label>Image URL (optional)</label>
                                    <input type="text" name="image" class="form-control" value="{{ $question->image }}">
                                </div>

                                <div class="mb-3">
                                    <label>Option A</label>
                                    <textarea name="option_a" class="form-control" rows="2" required>{{ $question->option_a }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Option B</label>
                                    <textarea name="option_b" class="form-control" rows="2" required>{{ $question->option_b }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Option C</label>
                                    <textarea name="option_c" class="form-control" rows="2" required>{{ $question->option_c }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Option D</label>
                                    <textarea name="option_d" class="form-control" rows="2" required>{{ $question->option_d }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Correct Option</label>
                                    <select name="correct_option" class="form-control" required>
                                        <option value="A" {{ $question->correct_option=='A'?'selected':'' }}>A</option>
                                        <option value="B" {{ $question->correct_option=='B'?'selected':'' }}>B</option>
                                        <option value="C" {{ $question->correct_option=='C'?'selected':'' }}>C</option>
                                        <option value="D" {{ $question->correct_option=='D'?'selected':'' }}>D</option>
                                    </select>
                                </div>

                                <!-- Optional Class -->
                                <div class="mb-3">
                                    <label>Class (Optional)</label>
                                    <select name="class" class="form-control edit-class">
                                        <option value="">-- Select Class --</option>
                                        <option value="SSC" {{ $question->subject && $question->subject->class=='SSC'?'selected':'' }}>SSC</option>
                                        <option value="HSC" {{ $question->subject && $question->subject->class=='HSC'?'selected':'' }}>HSC</option>
                                    </select>
                                </div>

                                <!-- Optional Subject -->
                                <div class="mb-3">
                                    <label>Subject (Optional)</label>
                                    <select name="subject_id" class="form-control edit-subject">
                                        <option value="">-- Select Subject --</option>
                                        @if($question->subject)
                                            <option value="{{ $question->subject->id }}" selected>{{ $question->subject->name }}</option>
                                        @endif
                                    </select>
                                </div>

                                <!-- Optional Chapter -->
                                <div class="mb-3">
                                    <label>Chapter (Optional)</label>
                                    <select name="chapter_id" class="form-control edit-chapter">
                                        <option value="">-- Select Chapter --</option>
                                        @if($question->chapter)
                                            <option value="{{ $question->chapter->id }}" selected>{{ $question->chapter->name }}</option>
                                        @endif
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label>Source Name</label>
                                    <input type="text" name="source_name" class="form-control" value="{{ $question->source_name }}">
                                </div>
                                <div class="mb-3">
                                    <label>Source Type</label>
                                    <input type="text" name="source_type" class="form-control" value="{{ $question->source_type }}">
                                </div>
                                <div class="mb-3">
                                    <label>Year</label>
                                    <input type="number" name="year" class="form-control" value="{{ $question->year }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-warning">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteModal{{ $question->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('questions.destroy', $question->id) }}">
                        @csrf
                        @method('DELETE')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Delete Question #{{ $question->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete this question?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

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
</div>

<!-- Add Question Modal -->
<div class="modal fade" id="addQuestionModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('questions.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Question</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Question Text</label>
                        <textarea name="question_text" class="form-control" rows="3" required></textarea>
                        <small class="text-muted">Use \( \) for inline math: \( x^2 \), use \[ \] for display math: \[ \frac{a}{b} \]</small>
                    </div>

                    <div class="mb-3">
                        <label>Image URL (optional)</label>
                        <input type="text" name="image" class="form-control" value="">
                    </div>

                    <div class="mb-3">
                        <label>Option A</label>
                        <textarea name="option_a" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Option B</label>
                        <textarea name="option_b" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Option C</label>
                        <textarea name="option_c" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Option D</label>
                        <textarea name="option_d" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Correct Option</label>
                        <select name="correct_option" class="form-control" required>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>

                    <!-- Optional Class -->
                    <div class="mb-3">
                        <label>Class (Optional)</label>
                        <select name="class" class="form-control add-class">
                            <option value="">-- Select Class --</option>
                            <option value="SSC">SSC</option>
                            <option value="HSC">HSC</option>
                        </select>
                    </div>

                    <!-- Optional Subject -->
                    <div class="mb-3">
                        <label>Subject (Optional)</label>
                        <select name="subject_id" class="form-control add-subject">
                            <option value="">-- Select Subject --</option>
                        </select>
                    </div>

                    <!-- Optional Chapter -->
                    <div class="mb-3">
                        <label>Chapter (Optional)</label>
                        <select name="chapter_id" class="form-control add-chapter">
                            <option value="">-- Select Chapter --</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Source Name</label>
                        <input type="text" name="source_name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Source Type</label>
                        <input type="text" name="source_type" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Year</label>
                        <input type="number" name="year" class="form-control">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Question</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- MathJax Configuration -->
<script>
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
        const subjectSelect = document.getElementById('subject_id');
        
        if (classSelect && subjectSelect) {
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
</script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

<!-- Dynamic Class -> Subject -> Chapter -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    function setupDynamicDropdown(formSelector, classSelector, subjectSelector, chapterSelector) {
        const form = document.querySelector(formSelector);
        if(!form) return;

        const classSelect = form.querySelector(classSelector);
        const subjectSelect = form.querySelector(subjectSelector);
        const chapterSelect = form.querySelector(chapterSelector);

        if(classSelect){
            classSelect.addEventListener('change', function(){
                subjectSelect.innerHTML = '<option value="">-- Select Subject --</option>';
                chapterSelect.innerHTML = '<option value="">-- Select Chapter --</option>';
                const classVal = this.value;
                if(classVal){
                    fetch(`/questions/get-subjects?class=${classVal}`)
                    .then(res=>res.json())
                    .then(data=>{
                        data.forEach(sub=>{
                            const opt = document.createElement('option');
                            opt.value = sub.id;
                            opt.text = sub.name;
                            subjectSelect.appendChild(opt);
                        });
                    });
                }
            });
        }

        if(subjectSelect){
            subjectSelect.addEventListener('change', function(){
                chapterSelect.innerHTML = '<option value="">-- Select Chapter --</option>';
                const subjectVal = this.value;
                if(subjectVal){
                    fetch(`/questions/get-chapters?subject_id=${subjectVal}`)
                    .then(res=>res.json())
                    .then(data=>{
                        data.forEach(chap=>{
                            const opt = document.createElement('option');
                            opt.value = chap.id;
                            opt.text = chap.name;
                            chapterSelect.appendChild(opt);
                        });
                    });
                }
            });
        }
    }

    // Setup for Add Question Modal
    setupDynamicDropdown('#addQuestionModal', '.add-class', '.add-subject', '.add-chapter');

    // Setup for each Edit Question Modal
    @foreach($questions as $question)
        setupDynamicDropdown('#editModal{{ $question->id }}', '.edit-class', '.edit-subject', '.edit-chapter');
    @endforeach

    // Auto hide alerts
    setTimeout(()=>{
        document.querySelectorAll('.alert').forEach(alert=>{
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 3000);
});

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
</script>
</body>
</html