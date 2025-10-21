@extends('layouts.app')

@section('title', 'Manage Students')

@section('content')
<style>
    .students-container {
         
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .page-header {
        background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-tertiary) 100%);
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-lg);
    }
    
    .students-card {
        background-color: var(--bg-secondary);
        border-radius: 12px;
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }
    
    .table-container {
        overflow-x: auto;
    }
    
    .students-table {
        width: 100%;
        margin-bottom: 0;
    }
    
    .students-table thead {
        background-color: var(--bg-tertiary);
    }
    
    .students-table thead th {
        color: var(--text-secondary);
        font-weight: 600;
        padding: 1rem;
        border: none;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    
    .students-table tbody tr {
        border-bottom: 1px solid var(--border-primary);
        transition: background-color 0.2s ease;
    }
    
    .students-table tbody tr:hover {
        background-color: var(--bg-hover);
    }
    
    .students-table tbody td {
        color: var(--text-primary);
        padding: 1rem;
        vertical-align: middle;
    }
    
    .badge-role {
        background-color: var(--accent-green);
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .btn-delete {
        background-color: var(--accent-red);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    
    .btn-delete:hover {
        background-color: var(--accent-red-hover);
        color: white;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .btn-back {
        background-color: var(--bg-tertiary);
        color: var(--text-primary);
        border: 1px solid var(--border-primary);
        padding: 0.6rem 1.2rem;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-back:hover {
        background-color: var(--bg-hover);
        color: var(--text-secondary);
        border-color: var(--border-secondary);
        transform: translateY(-2px);
    }
    
    .alert {
        border-radius: 8px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        border: none;
    }
    
    .alert-success {
        background-color: rgba(21, 128, 61, 0.2);
        color: var(--accent-green);
        border-left: 4px solid var(--accent-green);
    }
    
    .alert-danger {
        background-color: rgba(220, 38, 38, 0.2);
        color: var(--accent-red);
        border-left: 4px solid var(--accent-red);
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-muted);
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .pagination {
        margin-top: 2rem;
        justify-content: center;
    }
    
    .pagination .page-link {
        background-color: var(--bg-tertiary);
        border: 1px solid var(--border-primary);
        color: var(--text-primary);
        padding: 0.5rem 0.75rem;
        margin: 0 0.2rem;
        border-radius: 6px;
    }
    
    .pagination .page-link:hover {
        background-color: var(--bg-hover);
        color: var(--text-secondary);
        border-color: var(--border-secondary);
    }
    
    .pagination .page-item.active .page-link {
        background-color: var(--accent-green);
        border-color: var(--accent-green);
        color: white;
    }
    
    .pagination .page-item.disabled .page-link {
        background-color: var(--bg-secondary);
        border-color: var(--border-primary);
        color: var(--text-muted);
        opacity: 0.5;
    }
</style>

<div class="students-container">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2" style="color: var(--text-secondary);">
                        <i class="bi bi-people-fill me-2"></i>Manage Students
                    </h1>
                    <p class="mb-0" style="color: var(--text-muted);">
                        View and manage all registered students
                    </p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="btn-back">
                    <i class="bi bi-arrow-left"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Students Card -->
        <div class="students-card">
            <div class="card-body p-0">
                @if($students->count() > 0)
                    <div class="table-container">
                        <table class="students-table">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 25%;">Name</th>
                                    <th style="width: 25%;">Email</th>
                                    <th style="width: 15%;">Class</th>
                                    <th style="width: 15%;">Joined Date</th>
                                    <th style="width: 15%;" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $index => $student)
                                    <tr>
                                        <td>{{ ($students->currentPage() - 1) * $students->perPage() + $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-person-circle" style="font-size: 1.5rem; color: var(--accent-green);"></i>
                                                <strong>{{ $student->name }}</strong>
                                            </div>
                                        </td>
                                        <td>{{ $student->email }}</td>
                                        <td>
                                            <span class="badge-role">
                                                Class {{ $student->class }}
                                            </span>
                                        </td>
                                        <td>{{ $student->created_at->format('M d, Y') }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('students.destroy', $student) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete {{ $student->name }}? This action cannot be undone.');"
                                                  style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-delete">
                                                    <i class="bi bi-trash-fill me-1"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="p-4">
                        {{ $students->links('custom-pagination') }}
                    </div>
                @else
                    <div class="empty-state">
                        <i class="bi bi-people"></i>
                        <h4 style="color: var(--text-muted);">No Students Found</h4>
                        <p style="color: var(--text-muted);">There are currently no registered students.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
