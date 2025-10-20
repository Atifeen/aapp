@props(['questions', 'showActions' => true, 'showCheckbox' => false, 'examId' => null])

@foreach($questions as $question)
    <div class="question-card">
        @if($showCheckbox)
        <div class="d-flex">
            <!-- Checkbox -->
            <div class="me-3 pt-1">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input question-checkbox" 
                           name="question_ids[]" value="{{ $question->id }}"
                           @if($examId && isset($exam) && $exam->questions->contains($question->id)) checked @endif>
                </div>
            </div>
            
            <!-- Question Content -->
            <div class="flex-grow-1">
        @endif
                <!-- Question Header -->
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="question-header">
                        <span class="badge me-2" style="background-color: #15803d !important;">#{{ $question->id }}</span>
                        <strong style="color: #e2e8f0;">Question {{ $loop->iteration }}</strong>
                    </div>
                    <div class="question-meta d-flex flex-wrap gap-1">
                        @if($question->subject)
                            <span class="badge" style="background-color: #15803d !important;">{{ $question->subject->class }}</span>
                            <span class="badge" style="background-color: #15803d !important;">{{ $question->subject->name }}</span>
                        @endif
                        @if($question->chapter)
                            <span class="badge" style="background-color: #15803d !important;">{{ $question->chapter->name }}</span>
                        @endif
                        @if($question->source_type)
                            <span class="badge" style="background-color: #15803d !important;">{{ ucfirst($question->source_type) }}</span>
                        @endif
                        @if($question->year)
                            <span class="badge" style="background-color: #15803d !important;">{{ $question->year }}</span>
                        @endif
                    </div>
                </div>
                
                <!-- Question Text -->
                <div class="question-text mb-3">
                    <p class="mb-2 fw-semibold">{!! $question->question_text !!}</p>
                    @if($question->image)
                        <div class="mb-3">
                            <div class="d-flex image-controls align-items-center mb-2">
                                <a href="{{ $question->image }}" target="_blank" class="btn btn-sm btn-outline-info btn-image-control">
                                    <i class="bi bi-image me-1"></i>View Full Size
                                </a>
                                <button class="btn btn-sm btn-outline-secondary btn-image-control" onclick="toggleImage('img-{{ $question->id }}')">
                                    <i class="bi bi-eye-slash me-1"></i>Hide Image
                                </button>
                            </div>
                            <div id="img-{{ $question->id }}" class="question-image" style="display: block;">
                                <img src="{{ $question->image }}" alt="Question Image" 
                                     class="img-fluid rounded border shadow-sm" 
                                     style="max-width: 100%; max-height: 300px; object-fit: contain;">
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Options with Correct Answer Highlighted -->
                <div class="options-display" style="background-color: #1e293b !important; border: 1px solid #475569;">
                    <div class="row g-2">
                        @php
                            $options = ['a' => $question->option_a, 'b' => $question->option_b, 'c' => $question->option_c, 'd' => $question->option_d];
                            $correctOption = strtolower($question->correct_option);
                        @endphp
                        @foreach($options as $key => $option)
                            <div class="col-md-6">
                                <div class="option-item p-2 rounded border @if($correctOption === $key) border-success @endif" style="background-color: #1e293b !important; border-color: #475569 !important; color: #e2e8f0 !important; @if($correctOption === $key) border-color: #15803d !important; background-color: #166534 !important; @endif">
                                    <strong>{{ $key }})</strong> {!! $option !!}
                                    @if($correctOption === $key)
                                        <i class="bi bi-check-circle-fill ms-2"></i>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Additional Info -->
                @if($question->source_name)
                    <div class="mt-2">
                        <small style="color: #94a3b8 !important;">
                            <i class="bi bi-building me-1"></i>Source: {{ $question->source_name }}
                        </small>
                    </div>
                @endif

                @if($showActions)
                    <div class="mt-3 d-flex gap-2 justify-content-end">
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#editModal{{ $question->id }}" title="Edit" style="background-color: #15803d !important; border-color: #15803d !important;">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </button>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $question->id }}" title="Delete">
                            <i class="bi bi-trash me-1"></i>Delete
                        </button>
                    </div>
                @endif
        @if($showCheckbox)
            </div>
        </div>
        @endif
    </div>
@endforeach