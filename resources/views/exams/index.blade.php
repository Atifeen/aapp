@extends('layouts.app')

@section('content')
 

<div class="container py-4">
    @if(isset($stats) && !empty($stats))
        <!-- Stats Section -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm" style="background-color: #334155;">
                    <div class="card-body text-center py-4">
                        <h6 class="text-white mb-2 opacity-75">Total Exams</h6>
                        <h1 class="mb-0 text-white fw-bold">{{ $stats['total'] ?? 0 }}</h1>
                    </div>
                </div>
            </div>
            
            @if($filterType === 'custom')
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm" style="background-color: #334155;">
                        <div class="card-body text-center py-4">
                            <h6 class="text-white mb-2 opacity-75">Active Now</h6>
                            <h1 class="mb-0 text-white fw-bold">{{ $stats['active'] ?? 0 }}</h1>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm" style="background-color: #334155;">
                        <div class="card-body text-center py-4">
                            <h6 class="text-white mb-2 opacity-75">Upcoming</h6>
                            <h1 class="mb-0 text-white fw-bold">{{ $stats['upcoming'] ?? 0 }}</h1>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Filter Section -->
    <div class="card shadow-sm mb-4" style="background-color: #1e293b; border: none;">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('exams.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="subject_id" class="form-label text-white mb-2">
                        <i class="bi bi-book me-1"></i>Subject
                    </label>
                    <select name="subject_id" id="subject_id" class="form-select" style="background-color: #334155; color: #e2e8f0; border-color: #475569;">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label for="exam_type" class="form-label text-white mb-2">
                        <i class="bi bi-folder me-1"></i>Exam Type
                    </label>
                    <select name="exam_type" id="exam_type" class="form-select" style="background-color: #334155; color: #e2e8f0; border-color: #475569;">
                        <option value="">All Types</option>
                        <option value="board" {{ request('exam_type') == 'board' ? 'selected' : '' }}>Board Exam</option>
                        <option value="university" {{ request('exam_type') == 'university' ? 'selected' : '' }}>University Exam</option>
                        <option value="custom" {{ request('exam_type') == 'custom' ? 'selected' : '' }}>Custom Exam</option>
                    </select>
                </div>
                
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn flex-grow-1" style="background-color: var(--accent-green); color: white; border: none;">
                        <i class="bi bi-funnel me-1"></i>Apply Filters
                    </button>
                    @if(request()->filled('subject_id') || request()->filled('exam_type'))
                        <a href="{{ route('exams.index') }}" class="btn btn-outline-secondary" style="border-color: #475569; color: #e2e8f0;">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm" style="background-color: #1e293b; border: none;">
        <div class="card-header d-flex justify-content-between align-items-center py-3 px-4" style="background-color: #334155; border: none;">
            <h3 class="card-title text-white mb-0 fw-bold">
                @if($filterType === 'board')
                    <i class="bi bi-mortarboard me-2"></i>Board Exams
                @elseif($filterType === 'university')
                    <i class="bi bi-building me-2"></i>University Exams
                @elseif($filterType === 'custom')
                    <i class="bi bi-gear me-2"></i>Custom Exams
                @else
                    <i class="bi bi-folder2-open me-2"></i>All Exams
                @endif
            </h3>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('exams.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle me-1"></i>Create New Exam
                </a>
            @endif
        </div>
        <div class="card-body" style="background-color: #1e293b;">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover mb-0" style="color: #e2e8f0; background-color: #1e293b;">
                    <thead style="background-color: #334155; border-color: #475569;">
                        <tr>
                            <th class="text-white">Name</th>
                            <th class="text-white">Type</th>
                            <th class="text-white">Subject</th>
                            <th class="text-white">Chapter</th>
                            <th class="text-white">Start Time</th>
                            <th class="text-white">Duration</th>
                            <th class="text-white">Status</th>
                            <th class="text-white">Actions</th>
                        </tr>
                    </thead>
                    <tbody style="border-color: #475569; background-color: #1e293b;">
                        @foreach($exams as $exam)
                            <tr style="border-color: #475569; background-color: #1e293b;" class="hover-row">
                                <td>{{ $exam->title }}</td>
                                <td>
                                    {{ ucfirst($exam->exam_type) }}
                                    @if($exam->institution_name)
                                        <br>
                                        <small>{{ $exam->institution_name }} ({{ $exam->year }})</small>
                                    @endif
                                </td>
                                <td>{{ $exam->subject?->name ?? 'N/A' }}</td>
                                <td>{{ $exam->chapter?->name ?? 'N/A' }}</td>
                                <td>
                                    @if($exam->start_time)
                                        {{ $exam->start_time->format('Y-m-d H:i') }}
                                    @else
                                        Always Available
                                    @endif
                                </td>
                                <td>
                                    @if($exam->duration)
                                        {{ $exam->duration }} minutes
                                    @else
                                        No limit
                                    @endif
                                </td>
                                <td>
                                    @if($exam->isAvailable())
                                        <span class="badge bg-success">Available</span>
                                    @else
                                        <span class="badge bg-danger">Not Available</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('exams.show', $exam) }}" class="btn btn-sm btn-success me-1" title="View Exam">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        @if(auth()->user()->role === 'admin')
                                            <a href="{{ route('exams.edit', $exam) }}" class="btn btn-sm btn-success me-1" title="Edit Exam">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form action="{{ route('exams.destroy', $exam) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this exam? This action cannot be undone.');" style="display:inline-block; margin-left:4px;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete Exam">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        @else
                                            <!-- <a href="{{ route('exams.preview', $exam) }}" class="btn btn-sm btn-primary me-1" title="Start Exam">
                                                <i class="bi bi-play-circle"></i> Start
                                            </a> -->
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                            @if($exams->hasPages())
                {{ $exams->links('custom-pagination') }}
            @endif
            </div>
        </div>
    </div>
</div>

<style>
    .hover-row {
        transition: background-color 0.2s ease;
        background-color: #1e293b !important;
    }
    .hover-row:hover {
        background-color: #2d3748 !important;
    }
    .hover-row td {
        background-color: transparent !important;
        color: #e2e8f0 !important;
        padding: 1rem !important;
    }
    .table thead th {
        padding: 1rem !important;
    }
    .table > :not(caption) > * > * {
        border-color: #475569 !important;
        background-color: #1e293b;
    }
    .table-responsive {
        background-color: #1e293b;
    }
    .btn-success {
        background-color: #15803d !important;
        border-color: #15803d !important;
        box-shadow: none !important;
    }
    .btn-success:hover {
        background-color: #166534 !important;
        border-color: #166534 !important;
        box-shadow: none !important;
    }
    .btn-success:focus, .btn-success:active {
        background-color: #166534 !important;
        border-color: #166534 !important;
        box-shadow: none !important;
    }
</style>

@endsection