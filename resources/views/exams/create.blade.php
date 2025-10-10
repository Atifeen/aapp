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
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="exam_type" class="form-label">Exam Type</label>
                    <select class="form-select @error('exam_type') is-invalid @enderror" id="exam_type" name="exam_type" required>
                        <option value="">Select Type</option>
                        <option value="board" {{ old('exam_type') == 'board' ? 'selected' : '' }}>Board Exam</option>
                        <option value="university" {{ old('exam_type') == 'university' ? 'selected' : '' }}>University Exam</option>
                        <option value="custom" {{ old('exam_type') == 'custom' ? 'selected' : '' }}>Custom Exam</option>
                    </select>
                    @error('exam_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_rated" name="is_rated" value="1" {{ old('is_rated') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_rated">
                            Rated Exam (affects user rating)
                        </label>
                    </div>
                </div>

                <div class="mb-3 rated-exam-field" style="{{ old('is_rated') ? 'display: block;' : 'display: none;' }}">
                    <label for="difficulty_level" class="form-label">Difficulty Level</label>
                    <select class="form-select" id="difficulty_level" name="difficulty_level">
                        <option value="1">Easy (800-1200)</option>
                        <option value="2">Medium (1200-1600)</option>
                        <option value="3">Hard (1600-2000)</option>
                        <option value="4">Expert (2000+)</option>
                    </select>
                </div>

                <div id="institutionFields" style="display: none;">
                    <div class="mb-3">
                        <label for="institution_name" class="form-label">Institution Name</label>
                        <input type="text" class="form-control @error('institution_name') is-invalid @enderror" id="institution_name" name="institution_name" value="{{ old('institution_name') }}">
                        @error('institution_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="year" class="form-label">Year</label>
                        <input type="number" class="form-control @error('year') is-invalid @enderror" id="year" name="year" value="{{ old('year') }}" min="1900" max="{{ date('Y') + 1 }}">
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
                    <label for="subject_id" class="form-label">Subject</label>
                    <select class="form-select @error('subject_id') is-invalid @enderror" id="subject_id" name="subject_id">
                        <option value="">Select Subject</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->class }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="chapter_id" class="form-label">Chapter</label>
                    <select class="form-select @error('chapter_id') is-invalid @enderror" id="chapter_id" name="chapter_id" disabled>
                        <option value="">Select Chapter</option>
                    </select>
                </div>

                <div class="mb-3" id="start_time_container">
                    <label for="start_time" class="form-label">Start Time</label>
                    <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time') }}">
                    <div class="form-text">Required for rated exams. Default is set to tomorrow at 9 AM.</div>
                    @error('start_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="duration" class="form-label">Duration (minutes)</label>
                    <input type="number" class="form-control" id="duration" name="duration" min="1">
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

@push('scripts')
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

    // Handle exam type change
    examType.addEventListener('change', function() {
        if (this.value === 'custom') {
            institutionFields.style.display = 'none';
            customFields.style.display = 'block';
            chapterSelect.required = true;
        } else if (this.value === 'university') {
            institutionFields.style.display = 'block';
            customFields.style.display = 'none';
            chapterSelect.required = false;
        } else if (this.value === 'board') {
            institutionFields.style.display = 'none';
            customFields.style.display = 'none';
            chapterSelect.required = false;
        } else {
            institutionFields.style.display = 'none';
            customFields.style.display = 'none';
            chapterSelect.required = false;
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
});
</script>
@endpush

@endsection