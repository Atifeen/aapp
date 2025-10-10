@props(['subjects', 'chapters', 'boards', 'universities', 'action' => '', 'showQuestionText' => true])

<div class="card filter-card mb-4">
    <div class="card-header bg-transparent border-0 pt-4">
        <h5 class="card-title mb-0"><i class="bi bi-funnel me-2"></i>Filter Questions</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ $action }}" class="row g-3">
            @if($showQuestionText)
            <div class="col-md-4">
                <label for="question_text" class="form-label fw-semibold">Question Text</label>
                <input type="text" name="question_text" id="question_text" class="form-control" placeholder="Search in question text..." value="{{ request('question_text') }}">
            </div>
            @endif
            <div class="col-md-2">
                <label for="subject_id" class="form-label fw-semibold">Subject</label>
                <select name="subject_id" id="subject_id" class="form-select">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" @if(request('subject_id') == $subject->id) selected @endif>{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="chapter_id" class="form-label fw-semibold">Chapter</label>
                <select name="chapter_id" id="chapter_id" class="form-select">
                    <option value="">All Chapters</option>
                    @foreach($chapters as $chapter)
                        <option value="{{ $chapter->id }}" @if(request('chapter_id') == $chapter->id) selected @endif>{{ $chapter->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="year" class="form-label fw-semibold">Year</label>
                <input type="number" name="year" id="year" class="form-control" placeholder="e.g., 2023" value="{{ request('year') }}">
            </div>
            <div class="col-md-2">
                <label for="source_type" class="form-label fw-semibold">Source Type</label>
                <select name="source_type" id="source_type" class="form-select">
                    <option value="">All Types</option>
                    <option value="board" @if(request('source_type') == 'board') selected @endif>Board</option>
                    <option value="university" @if(request('source_type') == 'university') selected @endif>University</option>
                    <option value="custom" @if(request('source_type') == 'custom') selected @endif>Custom</option>
                </select>
            </div>
            <div class="col-md-3" id="sourceNameBoard" style="display:none;">
                <label for="source_name_board" class="form-label fw-semibold">Board Name</label>
                <select name="source_name" id="source_name_board" class="form-select">
                    <option value="">All Boards</option>
                    @foreach($boards as $board)
                        <option value="{{ $board->name }}" @if(request('source_name') == $board->name) selected @endif>{{ $board->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3" id="sourceNameUniversity" style="display:none;">
                <label for="source_name_university" class="form-label fw-semibold">University Name</label>
                <select name="source_name" id="source_name_university" class="form-select">
                    <option value="">All Universities</option>
                    @foreach($universities as $university)
                        <option value="{{ $university->name }}" @if(request('source_name') == $university->name) selected @endif>{{ $university->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3" id="sourceNameCustom" style="display:none;">
                <label for="source_name_custom" class="form-label fw-semibold">Custom Source Name</label>
                <input type="text" name="source_name" id="source_name_custom" class="form-control" placeholder="Enter source name..." value="{{ request('source_name') }}">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-custom me-2">
                    <i class="bi bi-search me-2"></i>Apply Filters
                </button>
                <a href="{{ $action }}" class="btn btn-outline-secondary btn-custom">
                    <i class="bi bi-arrow-clockwise me-2"></i>Clear All
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Dynamic source name filtering
document.addEventListener('DOMContentLoaded', function() {
    function toggleSourceName() {
        var type = document.getElementById('source_type').value;
        // Disable all source_name fields
        document.getElementById('source_name_board').disabled = true;
        document.getElementById('source_name_university').disabled = true;
        document.getElementById('source_name_custom').disabled = true;
        // Hide all
        document.getElementById('sourceNameBoard').style.display = 'none';
        document.getElementById('sourceNameUniversity').style.display = 'none';
        document.getElementById('sourceNameCustom').style.display = 'none';
        // Enable and show the selected one
        if(type === 'board') {
            document.getElementById('sourceNameBoard').style.display = '';
            document.getElementById('source_name_board').disabled = false;
        } else if(type === 'university') {
            document.getElementById('sourceNameUniversity').style.display = '';
            document.getElementById('source_name_university').disabled = false;
        } else if(type === 'custom') {
            document.getElementById('sourceNameCustom').style.display = '';
            document.getElementById('source_name_custom').disabled = false;
        }
    }
    document.getElementById('source_type').addEventListener('change', toggleSourceName);
    toggleSourceName();
});
</script>