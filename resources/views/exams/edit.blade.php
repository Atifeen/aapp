@extends('layouts.dashboard')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none"><i class="bi bi-house-door"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('exams.index') }}" class="text-decoration-none">Exams</a></li>
                    <li class="breadcrumb-item active">Edit Exam</li>
                </ol>
            </nav>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="dashboard-card">
        <div class="card-body p-4">
            <h3 class="mb-4">Edit Exam: {{ $exam->title }}</h3>
            <form action="{{ route('exams.update', $exam) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="title" class="form-label">Exam Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $exam->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="exam_type" class="form-label">Exam Type</label>
                    <select class="form-select @error('exam_type') is-invalid @enderror" id="exam_type" name="exam_type" required>
                        <option value="">Select Type</option>
                        <option value="board" {{ old('exam_type', $exam->exam_type) == 'board' ? 'selected' : '' }}>Board Exam</option>
                        <option value="university" {{ old('exam_type', $exam->exam_type) == 'university' ? 'selected' : '' }}>University Exam</option>
                        <option value="custom" {{ old('exam_type', $exam->exam_type) == 'custom' ? 'selected' : '' }}>Custom Exam</option>
                    </select>
                    @error('exam_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Board Exam Fields -->
                <div id="boardFields" style="display: {{ old('exam_type', $exam->exam_type) == 'board' ? 'block' : 'none' }};">
                    <div class="mb-3">
                        <label for="board_name" class="form-label">Board Name</label>
                        <select class="form-select @error('board_name') is-invalid @enderror" id="board_name" name="board_name">
                            <option value="">Select Board</option>
                            @foreach($boards as $board)
                                <option value="{{ $board->name }}" {{ old('board_name', $exam->board_name) == $board->name ? 'selected' : '' }}>{{ $board->name }}</option>
                            @endforeach
                        </select>
                        @error('board_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="board_year" class="form-label">Year</label>
                        <input type="number" class="form-control @error('year') is-invalid @enderror" id="board_year" name="year" value="{{ old('year', $exam->year) }}" min="1900" max="{{ date('Y') + 1 }}">
                        @error('year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- University Exam Fields -->
                <div id="universityFields" style="display: {{ old('exam_type', $exam->exam_type) == 'university' ? 'block' : 'none' }};">
                    <div class="mb-3">
                        <label for="university_name" class="form-label">University Name</label>
                        <select class="form-select @error('university_name') is-invalid @enderror" id="university_name" name="university_name">
                            <option value="">Select University</option>
                            @foreach($universities as $university)
                                <option value="{{ $university->name }}" {{ old('university_name', $exam->university_name) == $university->name ? 'selected' : '' }}>{{ $university->name }}</option>
                            @endforeach
                        </select>
                        @error('university_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="university_year" class="form-label">Year</label>
                        <input type="number" class="form-control @error('year') is-invalid @enderror" id="university_year" name="year" value="{{ old('year', $exam->year) }}" min="1900" max="{{ date('Y') + 1 }}">
                        @error('year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div id="customFields" style="{{ (old('exam_type', $exam->exam_type) == 'custom') ? 'display:block;' : 'display:none;' }}">
                    <div class="mb-3">
                        <label class="form-label">Custom Criteria</label>
                        <div id="criteriaContainer">
                            @php
                                $customCriteria = $exam->custom_criteria ?? [];
                                $criteriaCount = count($customCriteria);
                            @endphp
                            @if($criteriaCount > 0)
                                @foreach($customCriteria as $key => $value)
                                    <div class="row mb-2">
                                        <div class="col">
                                            <input type="text" class="form-control" name="custom_criteria[key][]" placeholder="Key" value="{{ $key }}">
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" name="custom_criteria[value][]" placeholder="Value" value="{{ $value }}">
                                        </div>
                                        <div class="col-auto">
                                            @if($loop->first)
                                                <button type="button" class="btn btn-primary btn-add-criteria">+</button>
                                            @else
                                                <button type="button" class="btn btn-danger btn-remove-criteria">-</button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="row mb-2">
                                    <div class="col">
                                        <input type="text" class="form-control" name="custom_criteria[key][]" placeholder="Key">
                                    </div>
                                    <div class="col">
                                        <input type="text" class="form-control" name="custom_criteria[value][]" placeholder="Value">
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-primary btn-add-criteria">+</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="class_filter" class="form-label">Filter by Class</label>
                    <select class="form-select" id="class_filter">
                        <option value="" {{ $selectedClass == '' ? 'selected' : '' }}>All Classes</option>
                        <option value="SSC" {{ $selectedClass == 'SSC' ? 'selected' : '' }}>SSC</option>
                        <option value="HSC" {{ $selectedClass == 'HSC' ? 'selected' : '' }}>HSC</option>
                    </select>
                    <div class="form-text">Filter subjects by class for easier selection</div>
                </div>

                <div class="mb-3">
                    <label for="subject_id" class="form-label">Subject</label>
                    <select class="form-select @error('subject_id') is-invalid @enderror" id="subject_id" name="subject_id">
                        <option value="">Select Subject (Optional for custom exams)</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" 
                                {{ old('subject_id', $exam->subject_id) == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }} ({{ $subject->class }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="chapter_id" class="form-label">Chapter</label>
                    <select class="form-select @error('chapter_id') is-invalid @enderror" id="chapter_id" name="chapter_id" {{ !$exam->subject_id ? 'disabled' : '' }}>
                        <option value="">Select Chapter (Optional for custom exams)</option>
                        @if($exam->subject_id && $chapters)
                            @foreach($chapters as $chapter)
                                <option value="{{ $chapter->id }}" 
                                    {{ old('chapter_id', $exam->chapter_id) == $chapter->id ? 'selected' : '' }}>
                                    {{ $chapter->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="mb-3">
                    <label for="start_time" class="form-label">Start Time <small class="text-muted">(optional - leave blank for anytime availability)</small></label>
                    <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" 
                        value="{{ old('start_time', $exam->start_time ? $exam->start_time->format('Y-m-d\TH:i') : '') }}">
                    <div class="form-text">Leave blank to make the exam available anytime.</div>
                    @error('start_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="duration" class="form-label">Duration (minutes)</label>
                    <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" 
                        value="{{ old('duration', $exam->duration) }}" min="1" required>
                    @error('duration')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Update Exam
                    </button>
                    <a href="{{ route('exams.index') }}" class="btn btn-link text-muted text-decoration-none">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const examType = document.getElementById('exam_type');
    const boardFields = document.getElementById('boardFields');
    const universityFields = document.getElementById('universityFields');
    const customFields = document.getElementById('customFields');
    const subjectSelect = document.getElementById('subject_id');
    const chapterSelect = document.getElementById('chapter_id');

    // Handle exam type change
    examType.addEventListener('change', function() {
        const subjectField = document.getElementById('subject_id');
        const chapterField = document.getElementById('chapter_id');
        
        // Hide all conditional fields first
        boardFields.style.display = 'none';
        universityFields.style.display = 'none';
        customFields.style.display = 'none';
        
        if (this.value === 'university') {
            universityFields.style.display = 'block';
            subjectField.required = true;
        } else if (this.value === 'custom') {
            customFields.style.display = 'block';
            subjectField.required = false;
        } else if (this.value === 'board') {
            boardFields.style.display = 'block';
            subjectField.required = true;
        } else {
            subjectField.required = false;
        }
    });

    // Handle subject change to load chapters
    subjectSelect.addEventListener('change', function() {
        if (this.value) {
            fetch(`/exams/chapters/${this.value}`)
                .then(response => response.json())
                .then(chapters => {
                    chapterSelect.disabled = false;
                    chapterSelect.innerHTML = '<option value="">Select Chapter</option>';
                    chapters.forEach(chapter => {
                        const option = new Option(chapter.name, chapter.id);
                        chapterSelect.add(option);
                    });
                });
        } else {
            chapterSelect.innerHTML = '<option value="">Select Chapter</option>';
            chapterSelect.disabled = true;
        }
    });

    // Handle custom criteria
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-add-criteria')) {
            const container = document.getElementById('criteriaContainer');
            const newRow = document.createElement('div');
            newRow.className = 'row mb-2';
            newRow.innerHTML = `
                <div class="col">
                    <input type="text" class="form-control" name="custom_criteria[key][]" placeholder="Key">
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="custom_criteria[value][]" placeholder="Value">
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-danger btn-remove-criteria">-</button>
                </div>
            `;
            container.appendChild(newRow);
        } else if (e.target.classList.contains('btn-remove-criteria')) {
            e.target.closest('.row').remove();
        }
    });

    // Handle class filtering for subjects (server-side filtering)
    const classFilterElement = document.getElementById('class_filter');
    
    if (classFilterElement) {
        classFilterElement.addEventListener('change', function() {
            const selectedClass = this.value;
            const currentUrl = new URL(window.location.href);
            
            if (selectedClass) {
                currentUrl.searchParams.set('class', selectedClass);
            } else {
                currentUrl.searchParams.delete('class');
            }
            
            // Reload page with class filter
            window.location.href = currentUrl.toString();
        });
    }
});
</script>

<style>
    /* Dark theme for exam edit form */
    .dashboard-card {
        background-color: #1e293b !important;
        border: none !important;
    }
    
    .dashboard-card .card-body {
        color: #e2e8f0;
    }
    
    .dashboard-card h3 {
        color: #e2e8f0;
    }
    
    .form-label {
        color: #e2e8f0 !important;
    }
    
    .form-control, .form-select, .form-check-input {
        background-color: #334155 !important;
        border-color: #475569 !important;
        color: #e2e8f0 !important;
    }
    
    .form-control:focus, .form-select:focus {
        background-color: #334155 !important;
        border-color: #15803d !important;
        color: #e2e8f0 !important;
        box-shadow: 0 0 0 0.2rem rgba(21, 128, 61, 0.25) !important;
    }
    
    .form-control::placeholder {
        color: #94a3b8 !important;
    }
    
    .form-select option {
        background-color: #334155;
        color: #e2e8f0;
    }
    
    .form-text {
        color: #94a3b8 !important;
    }
    
    .breadcrumb {
        background-color: transparent;
    }
    
    .breadcrumb-item a {
        color: #15803d !important;
    }
    
    .breadcrumb-item.active {
        color: #94a3b8;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        color: #94a3b8;
    }
    
    .alert-danger {
        background-color: #dc2626;
        border-color: #dc2626;
        color: white;
    }
    
    .btn-success {
        background-color: #15803d !important;
        border-color: #15803d !important;
        box-shadow: none !important;
    }
    
    .btn-success:hover {
        background-color: #166534 !important;
        border-color: #166534 !important;
    }
    
    .btn-warning {
        background-color: #15803d !important;
        border-color: #15803d !important;
    }
    
    .btn-warning:hover {
        background-color: #166534 !important;
        border-color: #166534 !important;
    }
    
    .btn-secondary {
        background-color: #475569 !important;
        border-color: #475569 !important;
    }
    
    .btn-secondary:hover {
        background-color: #64748b !important;
        border-color: #64748b !important;
    }
</style>

@endsection