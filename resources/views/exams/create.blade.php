@extends('layouts.dashboard')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none"><i class="bi bi-house-door"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('exams.index') }}" class="text-decoration-none">Exams</a></li>
                    <li class="breadcrumb-item active">Create New Exam</li>
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
            <h3 class="mb-4">Create New Exam</h3>
            <form action="{{ route('exams.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Exam Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', request('title')) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="exam_type" class="form-label">Exam Type</label>
                    <select class="form-select @error('exam_type') is-invalid @enderror" id="exam_type" name="exam_type" required>
                        <option value="">Select Type</option>
                        <option value="board" {{ old('exam_type', request('exam_type')) == 'board' ? 'selected' : '' }}>Board Exam</option>
                        <option value="university" {{ old('exam_type', request('exam_type')) == 'university' ? 'selected' : '' }}>University Exam</option>
                        <option value="custom" {{ old('exam_type', request('exam_type')) == 'custom' ? 'selected' : '' }}>Custom Exam</option>
                    </select>
                    @error('exam_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_rated" name="is_rated" value="1" {{ old('is_rated', request('is_rated')) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_rated">
                            Rated Exam (affects user rating)
                        </label>
                    </div>
                </div>

                <div class="mb-3 rated-exam-field" style="{{ old('is_rated', request('is_rated')) ? 'display: block;' : 'display: none;' }}">
                    <label for="difficulty_level" class="form-label">Difficulty Level</label>
                    <select class="form-select" id="difficulty_level" name="difficulty_level">
                        <option value="1" {{ old('difficulty_level', request('difficulty_level')) == '1' ? 'selected' : '' }}>Easy (800-1200)</option>
                        <option value="2" {{ old('difficulty_level', request('difficulty_level')) == '2' ? 'selected' : '' }}>Medium (1200-1600)</option>
                        <option value="3" {{ old('difficulty_level', request('difficulty_level')) == '3' ? 'selected' : '' }}>Hard (1600-2000)</option>
                        <option value="4" {{ old('difficulty_level', request('difficulty_level')) == '4' ? 'selected' : '' }}>Expert (2000+)</option>
                    </select>
                </div>

                <!-- Board Exam Fields -->
                <div id="boardFields" style="display: none;">
                    <div class="mb-3">
                        <label for="board_name" class="form-label">Board Name</label>
                        <select class="form-select @error('board_name') is-invalid @enderror" id="board_name" name="board_name">
                            <option value="">Select Board</option>
                            @foreach($boards as $board)
                                <option value="{{ $board->name }}" {{ old('board_name', request('board_name')) == $board->name ? 'selected' : '' }}>{{ $board->name }}</option>
                            @endforeach
                        </select>
                        @error('board_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="board_year" class="form-label">Year</label>
                        <input type="number" class="form-control @error('year') is-invalid @enderror" id="board_year" name="year" value="{{ old('year', request('year')) }}" min="1900" max="{{ date('Y') + 1 }}">
                        @error('year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- University Exam Fields -->
                <div id="universityFields" style="display: none;">
                    <div class="mb-3">
                        <label for="university_name" class="form-label">University Name</label>
                        <select class="form-select @error('university_name') is-invalid @enderror" id="university_name" name="university_name">
                            <option value="">Select University</option>
                            @foreach($universities as $university)
                                <option value="{{ $university->name }}" {{ old('university_name', request('university_name')) == $university->name ? 'selected' : '' }}>{{ $university->name }}</option>
                            @endforeach
                        </select>
                        @error('university_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="university_year" class="form-label">Year</label>
                        <input type="number" class="form-control @error('year') is-invalid @enderror" id="university_year" name="year" value="{{ old('year', request('year')) }}" min="1900" max="{{ date('Y') + 1 }}">
                        @error('year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div id="customFields" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Custom Criteria</label>
                        <div id="criteriaContainer">
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
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="class_filter" class="form-label">Filter by Class</label>
                    <select class="form-select" id="class_filter">
                        <option value="" {{ ($selectedClass ?? '') == '' ? 'selected' : '' }}>All Classes</option>
                        <option value="SSC" {{ ($selectedClass ?? '') == 'SSC' ? 'selected' : '' }}>SSC</option>
                        <option value="HSC" {{ ($selectedClass ?? '') == 'HSC' ? 'selected' : '' }}>HSC</option>
                    </select>
                    <div class="form-text">Filter subjects by class for easier selection</div>
                </div>

                <div class="mb-3">
                    <label for="subject_id" class="form-label">Subject</label>
                    <select class="form-select @error('subject_id') is-invalid @enderror" id="subject_id" name="subject_id">
                        <option value="">Select Subject (Optional for custom exams)</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id', request('subject_id')) == $subject->id ? 'selected' : '' }}>{{ $subject->name }} ({{ $subject->class }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="chapter_id" class="form-label">Chapter</label>
                    <select class="form-select @error('chapter_id') is-invalid @enderror" id="chapter_id" name="chapter_id" disabled>
                        <option value="">Select Chapter (Optional for custom exams)</option>
                    </select>
                </div>

                <div class="mb-3" id="start_time_container">
                    <label for="start_time" class="form-label">Start Time</label>
                    <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time', request('start_time')) }}">
                    <div class="form-text">Required for rated exams. Default is set to tomorrow at 9 AM.</div>
                    @error('start_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="duration" class="form-label">Duration (minutes)</label>
                    <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration', request('duration')) }}" min="1" required>
                    @error('duration')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Create Exam
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
    const institutionFields = document.getElementById('institutionFields');
    const customFields = document.getElementById('customFields');
    const subjectSelect = document.getElementById('subject_id');
    const chapterSelect = document.getElementById('chapter_id');
    const isRatedCheckbox = document.getElementById('is_rated');
    const ratedFields = document.querySelector('.rated-exam-field');
    const startTimeField = document.getElementById('start_time_container');
    const startTimeInput = document.getElementById('start_time');
    const difficultyLevelInput = document.getElementById('difficulty_level');

    // Set default start time to tomorrow at 9 AM if not already set
    if (!startTimeInput.value) {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        tomorrow.setHours(9, 0, 0, 0);
        startTimeInput.value = tomorrow.toISOString().slice(0, 16);
    }

    // Initialize fields based on initial rated status
    if (isRatedCheckbox.checked) {
        ratedFields.style.display = 'block';
        startTimeField.style.display = 'block';
        startTimeInput.required = true;
        difficultyLevelInput.required = true;
    }

    // Initialize start time visibility based on exam type
    startTimeField.style.display = isRatedCheckbox.checked ? 'block' : 'none';

    // Trigger exam type change to show/hide appropriate fields on page load
    if (examType.value) {
        examType.dispatchEvent(new Event('change'));
    }

    // Restore chapter selection if subject_id is in URL
    const urlParams = new URLSearchParams(window.location.search);
    const savedSubjectId = urlParams.get('subject_id');
    const savedChapterId = urlParams.get('chapter_id');
    
    if (savedSubjectId) {
        subjectSelect.value = savedSubjectId;
        // Trigger change to load chapters
        fetch(`/exams/chapters/${savedSubjectId}`)
            .then(response => response.json())
            .then(chapters => {
                chapterSelect.innerHTML = '<option value="">Select Chapter</option>';
                chapters.forEach(chapter => {
                    const option = new Option(chapter.name, chapter.id);
                    if (savedChapterId && chapter.id == savedChapterId) {
                        option.selected = true;
                    }
                    chapterSelect.add(option);
                });
                chapterSelect.disabled = false;
            });
    }

    // Handle exam type change
    examType.addEventListener('change', function() {
        const boardFields = document.getElementById('boardFields');
        const universityFields = document.getElementById('universityFields');
        const customFields = document.getElementById('customFields');
        const subjectField = document.getElementById('subject_id');
        const chapterField = document.getElementById('chapter_id');
        
        // Hide all conditional fields first
        boardFields.style.display = 'none';
        universityFields.style.display = 'none';
        customFields.style.display = 'none';
        
        if (this.value === 'custom') {
            customFields.style.display = 'block';
            // Make subject and chapter optional for custom exams
            subjectField.required = false;
            chapterField.required = false;
        } else if (this.value === 'university') {
            universityFields.style.display = 'block';
            // Make subject and chapter required for university exams
            subjectField.required = true;
            chapterField.required = false;
        } else if (this.value === 'board') {
            boardFields.style.display = 'block';
            // Make subject required for board exams
            subjectField.required = true;
            chapterField.required = false;
        } else {
            subjectField.required = false;
            chapterField.required = false;
        }
    });

    // Handle rated exam checkbox
    isRatedCheckbox.addEventListener('change', function() {
        ratedFields.style.display = this.checked ? 'block' : 'none';
        startTimeField.style.display = this.checked ? 'block' : 'none';
        if (this.checked) {
            document.getElementById('start_time').required = true;
            document.getElementById('difficulty_level').required = true;
        } else {
            document.getElementById('start_time').required = false;
            document.getElementById('difficulty_level').required = false;
        }
    });

    // Handle subject change
    subjectSelect.addEventListener('change', function() {
        const subjectId = this.value;
        chapterSelect.disabled = !subjectId;
        
        if (subjectId) {
            fetch(`/exams/chapters/${subjectId}`)
                .then(response => response.json())
                .then(chapters => {
                    chapterSelect.innerHTML = '<option value="">Select Chapter</option>';
                    chapters.forEach(chapter => {
                        const option = new Option(chapter.name, chapter.id);
                        chapterSelect.add(option);
                    });
                });
        } else {
            chapterSelect.innerHTML = '<option value="">Select Chapter</option>';
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
    
    console.log('Class filter element:', classFilterElement);
    
    if (classFilterElement) {
        classFilterElement.addEventListener('change', function() {
            const selectedClass = this.value;
            const currentUrl = new URL(window.location.href);
            
            console.log('Selected class:', selectedClass);
            console.log('Current URL before:', currentUrl.toString());
            
            // Get all form values
            const formData = {};
            
            // Get text inputs
            const titleInput = document.getElementById('title');
            if (titleInput && titleInput.value) {
                formData.title = titleInput.value;
            }
            
            const institutionNameInput = document.getElementById('institution_name');
            if (institutionNameInput && institutionNameInput.value) {
                formData.institution_name = institutionNameInput.value;
            }
            
            const yearInput = document.getElementById('year');
            if (yearInput && yearInput.value) {
                formData.year = yearInput.value;
            }
            
            const durationInput = document.getElementById('duration');
            if (durationInput && durationInput.value) {
                formData.duration = durationInput.value;
            }
            
            const startTimeInput = document.getElementById('start_time');
            if (startTimeInput && startTimeInput.value) {
                formData.start_time = startTimeInput.value;
            }
            
            // Get select values
            const examTypeSelect = document.getElementById('exam_type');
            if (examTypeSelect && examTypeSelect.value) {
                formData.exam_type = examTypeSelect.value;
            }
            
            const subjectSelect = document.getElementById('subject_id');
            if (subjectSelect && subjectSelect.value) {
                formData.subject_id = subjectSelect.value;
            }
            
            const chapterSelect = document.getElementById('chapter_id');
            if (chapterSelect && chapterSelect.value) {
                formData.chapter_id = chapterSelect.value;
            }
            
            const difficultySelect = document.getElementById('difficulty_level');
            if (difficultySelect && difficultySelect.value) {
                formData.difficulty_level = difficultySelect.value;
            }
            
            // Get checkbox value
            const isRatedCheckbox = document.getElementById('is_rated');
            if (isRatedCheckbox && isRatedCheckbox.checked) {
                formData.is_rated = '1';
            }
            
            // Clear existing params except CSRF
            const newUrl = new URL(currentUrl.origin + currentUrl.pathname);
            
            // Set class filter
            if (selectedClass) {
                newUrl.searchParams.set('class', selectedClass);
            }
            
            // Add all form data to URL
            Object.keys(formData).forEach(key => {
                newUrl.searchParams.set(key, formData[key]);
            });
            
            console.log('Current URL after:', newUrl.toString());
            
            // Reload page with all parameters
            window.location.href = newUrl.toString();
        });
    }
});
</script>

<style>
    /* Dark theme for exam creation form */
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