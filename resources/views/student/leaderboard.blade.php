@extends('layouts.dashboard')

@section('content')
@include('components.student-nav')

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-trophy-fill me-2 text-warning"></i>Leaderboard</h2>
            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Rank</th>
                                <th class="py-3">Name</th>
                                <th class="py-3">Rating</th>
                                <th class="py-3">Max Rating</th>
                                <th class="py-3">Contests Solved</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                            <tr class="{{ $student->id === auth()->id() ? 'table-primary' : '' }}">
                                <td class="px-4">
                                    <strong class="fs-5">#{{ $students->firstItem() + $index }}</strong>
                                    @if($students->firstItem() + $index == 1)
                                        <i class="bi bi-trophy-fill text-warning ms-2"></i>
                                    @elseif($students->firstItem() + $index == 2)
                                        <i class="bi bi-trophy-fill text-secondary ms-2"></i>
                                    @elseif($students->firstItem() + $index == 3)
                                        <i class="bi bi-trophy-fill ms-2" style="color: #CD7F32;"></i>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $student->name }}</strong>
                                    @if($student->id === auth()->id())
                                        <span class="badge bg-primary ms-2">You</span>
                                    @endif
                                </td>
                                <td><strong class="text-primary fs-5">{{ $student->rating }}</strong></td>
                                <td class="text-muted">{{ $student->max_rating }}</td>
                                <td class="text-muted">{{ $student->total_solved }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white">
                {{ $students->links('custom-pagination') }}
            </div>
        </div>
    </div>
@endsection
