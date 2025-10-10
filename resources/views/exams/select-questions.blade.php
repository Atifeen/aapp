@extends('layouts.app')

@section('title', 'Select Questions - ' . $exam->title)

@push('styles')
<style>
    .question-content {
        font-size: 0.95rem;
    }
    .options-list {
        margin-top: 0.5rem;
        padding-left: 1rem;
    }
    .option {
        margin-bottom: 0.25rem;
    }
    .table td {
        vertical-align: top;
        padding: 1rem;
    }
</style>
@endpush

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Filter Sidebar -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-funnel me-2"></i>Filters
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('exams.questions.select', $exam) }}" method="GET" id="filterForm">
                        <!-- Source Type Filter -->
                        <div class="mb-3">
                            <label class="form-label">Source Type</label>
                            <select name="source_type" class="form-select" onchange="this.form.submit()">
                                <option value="">All Sources</option>
                                @foreach($sourceTypes as $type)
                                    <option value="{{ $type }}" {{ request('source_type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Subject Filter -->
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <select name="subject_id" class="form-select" onchange="this.form.submit()">
                                <option value="">All Subjects</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Year Filter -->
                        <div class="mb-3">
                            <label class="form-label">Year</label>
                            <select name="year" class="form-select" onchange="this.form.submit()">
                                <option value="">All Years</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Reset Filters -->
                        <div class="d-grid">
                            <button type="button" class="btn btn-outline-secondary" onclick="resetFilters()">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Filters
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Questions List -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-list-check me-2"></i>Select Questions for {{ $exam->title }}
                    </h5>
                    <button type="button" class="btn btn-primary" onclick="saveSelectedQuestions()">
                        <i class="bi bi-save me-1"></i>Save Selected
                    </button>
                </div>
                <div class="card-body">
                    <form id="questionForm" action="{{ route('exams.questions.attach', $exam) }}" method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="50px">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="selectAll">
                                            </div>
                                        </th>
                                        <th>Question</th>
                                        <th>Subject</th>
                                        <th>Source</th>
                                        <th>Year</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($questions as $question)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input question-checkbox" 
                                                           name="question_ids[]" value="{{ $question->id }}"
                                                           {{ $exam->questions->contains($question->id) ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="question-content">
                                                    <p class="mb-2">{{ $question->question_text }}</p>
                                                    @if($question->image)
                                                        <img src="{{ asset('storage/' . $question->image) }}" alt="Question Image" class="img-fluid mb-2" style="max-height: 200px;">
                                                    @endif
                                                    <div class="options-list">
                                                        <div class="option"><strong>A)</strong> {{ $question->option_a }}</div>
                                                        <div class="option"><strong>B)</strong> {{ $question->option_b }}</div>
                                                        <div class="option"><strong>C)</strong> {{ $question->option_c }}</div>
                                                        <div class="option"><strong>D)</strong> {{ $question->option_d }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $question->subject->name }}</td>
                                            <td>{{ $question->source_name }} ({{ ucfirst($question->source_type) }})</td>
                                            <td>{{ $question->year }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <i class="bi bi-emoji-frown display-4 d-block mb-2"></i>
                                                <p class="text-muted mb-0">No questions found matching your criteria</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </form>

                    <div class="mt-4">
                        {{ $questions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Handle "Select All" checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.question-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });

    // Reset filters
    function resetFilters() {
        const form = document.getElementById('filterForm');
        const selects = form.querySelectorAll('select');
        selects.forEach(select => select.value = '');
        form.submit();
    }

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
</script>
@endsection