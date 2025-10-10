@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Exams</h3>
            <a href="{{ route('exams.create') }}" class="btn btn-primary">Create New Exam</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Subject</th>
                            <th>Chapter</th>
                            <th>Start Time</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($exams as $exam)
                            <tr>
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
                                    <a href="{{ route('exams.show', $exam) }}" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $exams->links() }}
            </div>
        </div>
    </div>
</div>
@endsection