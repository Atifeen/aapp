<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Questions - AAPP</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Questions Management</h2>

    <!-- Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Add Question Button -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
        <i class="bi bi-plus-circle"></i> Add Question
    </button>

    <!-- Questions Table -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Question</th>
                <th>Options (A-D)</th>
                <th>Correct</th>
                <th>Source</th>
                <th>Year</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($questions as $q)
            <tr>
                <td>{{ $q->id }}</td>
                <td>{{ $q->question_text }}</td>
                <td>
                    A: {{ $q->option_a }}<br>
                    B: {{ $q->option_b }}<br>
                    C: {{ $q->option_c }}<br>
                    D: {{ $q->option_d }}
                </td>
                <td>{{ $q->correct_option }}</td>
                <td>{{ $q->source_name ?? '-' }} / {{ $q->source_type ?? '-' }}</td>
                <td>{{ $q->year ?? '-' }}</td>
                <td>
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $q->id }}">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $q->id }}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal{{ $q->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('questions.update', $q->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Question #{{ $q->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="text" name="question_text" class="form-control mb-2" value="{{ $q->question_text }}" placeholder="Question" required>
                                <input type="text" name="option_a" class="form-control mb-2" value="{{ $q->option_a }}" placeholder="Option A" required>
                                <input type="text" name="option_b" class="form-control mb-2" value="{{ $q->option_b }}" placeholder="Option B" required>
                                <input type="text" name="option_c" class="form-control mb-2" value="{{ $q->option_c }}" placeholder="Option C" required>
                                <input type="text" name="option_d" class="form-control mb-2" value="{{ $q->option_d }}" placeholder="Option D" required>
                                <select name="correct_option" class="form-control mb-2" required>
                                    <option value="A" {{ $q->correct_option=='A'?'selected':'' }}>A</option>
                                    <option value="B" {{ $q->correct_option=='B'?'selected':'' }}>B</option>
                                    <option value="C" {{ $q->correct_option=='C'?'selected':'' }}>C</option>
                                    <option value="D" {{ $q->correct_option=='D'?'selected':'' }}>D</option>
                                </select>
                                <input type="text" name="source_name" class="form-control mb-2" value="{{ $q->source_name }}" placeholder="Source Name (Optional)">
                                <input type="text" name="source_type" class="form-control mb-2" value="{{ $q->source_type }}" placeholder="Source Type (Optional)">
                                <input type="number" name="year" class="form-control" value="{{ $q->year }}" placeholder="Year (Optional)">
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
            <div class="modal fade" id="deleteModal{{ $q->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('questions.destroy', $q->id) }}">
                        @csrf
                        @method('DELETE')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Delete Question #{{ $q->id }}</h5>
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

<!-- Add Question Modal -->
<div class="modal fade" id="addQuestionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('questions.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Question</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="question_text" class="form-control mb-2" placeholder="Question" required>
                    <input type="text" name="option_a" class="form-control mb-2" placeholder="Option A" required>
                    <input type="text" name="option_b" class="form-control mb-2" placeholder="Option B" required>
                    <input type="text" name="option_c" class="form-control mb-2" placeholder="Option C" required>
                    <input type="text" name="option_d" class="form-control mb-2" placeholder="Option D" required>
                    <select name="correct_option" class="form-control mb-2" required>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                    <input type="text" name="source_name" class="form-control mb-2" placeholder="Source Name (Optional)">
                    <input type="text" name="source_type" class="form-control mb-2" placeholder="Source Type (Optional)">
                    <input type="number" name="year" class="form-control" placeholder="Year (Optional)">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Question</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Auto-close alerts -->
<script>
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 3000);
</script>

</body>
</html>
