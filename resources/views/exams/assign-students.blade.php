@extends('layouts.app')

@section('title', 'Assign Students - ' . $exam->title)

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-people me-2"></i>Assign Students to: {{ $exam->title }}
                    </h5>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Instructions:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Select specific students to assign this exam to them only</li>
                            <li>Click "Assign to All Students" to make this exam available to everyone</li>
                            <li>Use filters to find students by class or email</li>
                            <li>Currently assigned students are pre-selected</li>
                        </ul>
                    </div>

                    <form method="POST" action="{{ route('exams.assign-students.store', $exam) }}" id="assignForm">
                        @csrf

                        <!-- Filters -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Filter Students</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Class</label>
                                        <select name="class" id="filterClass" class="form-select">
                                            <option value="">All Classes</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class }}" {{ request('class') == $class ? 'selected' : '' }}>
                                                    Class {{ $class }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="name" id="filterName" class="form-control" 
                                               placeholder="Search by name" 
                                               value="{{ request('name') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Email</label>
                                        <input type="text" name="email" id="filterEmail" class="form-control" 
                                               placeholder="Search by email" 
                                               value="{{ request('email') }}">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-outline-primary w-100" onclick="applyFilters()">
                                            <i class="bi bi-search me-1"></i>Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="selectAll()">
                                    <i class="bi bi-check-square me-1"></i>Select All on Page
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="deselectAll()">
                                    <i class="bi bi-square me-1"></i>Deselect All
                                </button>
                                <span class="badge bg-info ms-2" id="selectedCount">0 selected</span>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i>Save Selected Students
                                </button>
                                <button type="submit" name="assign_all" value="1" class="btn btn-primary" 
                                        onclick="return confirm('This will make the exam available to ALL students. Continue?')">
                                    <i class="bi bi-people-fill me-1"></i>Assign to All Students
                                </button>
                                <a href="{{ route('exams.show', $exam) }}" class="btn btn-outline-secondary">
                                    Skip
                                </a>
                            </div>
                        </div>

                        <!-- Students Table -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">
                                            <input type="checkbox" id="selectAllCheckbox" onclick="toggleAll(this)">
                                        </th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Class</th>
                                        <th>Rating</th>
                                        <th>Rank</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($students as $student)
                                    <tr>
                                        <td>
                                            <input type="checkbox" 
                                                   name="student_ids[]" 
                                                   value="{{ $student->id }}" 
                                                   class="student-checkbox"
                                                   {{ in_array($student->id, $assignedStudentIds) ? 'checked' : '' }}
                                                   onchange="updateCount()">
                                        </td>
                                        <td>
                                            <strong>{{ $student->name }}</strong>
                                        </td>
                                        <td>{{ $student->email }}</td>
                                        <td>
                                            @if($student->class)
                                                <span class="badge bg-info">Class {{ $student->class }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-warning text-dark">{{ $student->rating }}</span>
                                        </td>
                                        <td>
                                            @if($student->rank)
                                                <span class="badge bg-success">#{{ $student->rank }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                            <p class="text-muted mt-2">No students found</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <p class="text-muted mb-0">
                                    Showing {{ $students->firstItem() ?? 0 }} to {{ $students->lastItem() ?? 0 }} of {{ $students->total() }} students
                                </p>
                            </div>
                            <div>
                                {{ $students->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function selectAll() {
    document.querySelectorAll('.student-checkbox').forEach(checkbox => {
        checkbox.checked = true;
    });
    updateCount();
}

function deselectAll() {
    document.querySelectorAll('.student-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('selectAllCheckbox').checked = false;
    updateCount();
}

function toggleAll(source) {
    document.querySelectorAll('.student-checkbox').forEach(checkbox => {
        checkbox.checked = source.checked;
    });
    updateCount();
}

function updateCount() {
    const count = document.querySelectorAll('.student-checkbox:checked').length;
    document.getElementById('selectedCount').textContent = count + ' selected';
    
    // Update selectAllCheckbox state
    const total = document.querySelectorAll('.student-checkbox').length;
    const checked = document.querySelectorAll('.student-checkbox:checked').length;
    document.getElementById('selectAllCheckbox').checked = (total > 0 && total === checked);
}

function applyFilters() {
    const params = new URLSearchParams();
    
    const classFilter = document.getElementById('filterClass').value;
    const nameFilter = document.getElementById('filterName').value;
    const emailFilter = document.getElementById('filterEmail').value;
    
    if (classFilter) params.append('class', classFilter);
    if (nameFilter) params.append('name', nameFilter);
    if (emailFilter) params.append('email', emailFilter);
    
    window.location.href = '{{ route('exams.assign-students', $exam) }}' + '?' + params.toString();
}

// Initialize count on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCount();
});
</script>
@endpush
@endsection
