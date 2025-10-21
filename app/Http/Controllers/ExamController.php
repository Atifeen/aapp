<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use App\Models\ExamStatistic;
use App\Models\Question;
use App\Models\RatingChange;
use App\Models\Subject;
use App\Models\User;
use App\Services\RatingCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ExamController extends Controller
{
    public function index()
    {
        $query = Exam::with(['subject', 'chapter'])->latest();

        // Allow filtering by exam_type via query string
        if (request()->filled('exam_type')) {
            $type = request()->get('exam_type');
            if (in_array($type, ['board', 'university', 'custom'])) {
                $query->where('exam_type', $type);
            }
        }

        // Allow filtering by subject
        if (request()->filled('subject_id')) {
            $query->where('subject_id', request()->get('subject_id'));
        }

        $exams = $query->paginate(30)->withQueryString();

        $filterType = request()->get('exam_type');
        
        // Get all subjects for filter dropdown
        $subjects = \App\Models\Subject::orderBy('name')->get();
        
        // Calculate stats based on filter type
        $stats = [];
        if ($filterType) {
            $baseQuery = Exam::where('exam_type', $filterType);
            
            $stats['total'] = $baseQuery->count();
            
            // For custom exams (contests), show additional stats
            if ($filterType === 'custom') {
                $stats['active'] = (clone $baseQuery)
                    ->where('start_time', '<=', now())
                    ->where('end_time', '>', now())
                    ->count();
                    
                $stats['upcoming'] = (clone $baseQuery)
                    ->where('start_time', '>', now())
                    ->count();
            }
        }
        
        return view('exams.index', compact('exams', 'filterType', 'stats', 'subjects'));
    }

    public function create(Request $request)
    {
        // Filter subjects by class if provided
        $query = Subject::orderBy('class')->orderBy('name');
        
        if ($request->filled('class')) {
            $query->where('class', $request->class);
            \Log::info('Filtering subjects by class: ' . $request->class);
        }
        
        $subjects = $query->get();
        $selectedClass = $request->input('class', '');
        
        // Get boards and universities
        $boards = \App\Models\Board::all();
        $universities = \App\Models\University::all();
        
        \Log::info('Total subjects: ' . $subjects->count() . ', Selected class: ' . $selectedClass);
        
        return view('exams.create', compact('subjects', 'selectedClass', 'boards', 'universities'));
    }

    public function getChapters(Subject $subject)
    {
        return response()->json($subject->chapters);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'exam_type' => ['required', Rule::in(['board', 'university', 'custom'])],
            'board_name' => 'required_if:exam_type,board|nullable|string|max:255',
            'university_name' => 'required_if:exam_type,university|nullable|string|max:255',
            'institution_name' => 'nullable|string|max:255',
            'year' => 'required_unless:exam_type,custom|nullable|integer|min:1900|max:' . (date('Y') + 1),
            'subject_id' => 'required_unless:exam_type,custom|nullable|exists:subjects,id',
            'chapter_id' => 'nullable|exists:chapters,id',
            'start_time' => 'required_if:is_rated,1|nullable|date',
            'duration' => 'required|integer|min:1',
            'is_rated' => 'boolean',
            'difficulty_level' => 'required_if:is_rated,1|nullable|integer|min:1|max:4',
            'custom_criteria' => 'nullable|array'
        ]);

        // Process custom criteria
        if ($request->has('custom_criteria')) {
            $keys = $request->input('custom_criteria.key', []);
            $values = $request->input('custom_criteria.value', []);
            
            $validated['custom_criteria'] = array_combine(
                array_filter($keys),
                array_filter($values)
            );
        }

        // Cast duration to integer and calculate end time
        if (!empty($validated['start_time'])) {
            $startTime = \Carbon\Carbon::parse($validated['start_time']);
            $validated['end_time'] = $startTime->copy()->addMinutes((int) $validated['duration']);
        }

        try {
            DB::beginTransaction();

            // Create the exam
            $exam = Exam::create($validated);

            DB::commit();

            return redirect()->route('exams.questions.select', $exam)
                ->with('success', 'Exam created successfully. Now select questions for the exam.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create exam. ' . $e->getMessage());
        }
    }

    private function getQuestionsBasedOnCriteria(array $data)
    {
        $query = Question::query();

        if ($data['subject_id']) {
            $query->where('subject_id', $data['subject_id']);
        }

        if (!empty($data['chapter_id'])) {
            $query->where('chapter_id', $data['chapter_id']);
        }

        if ($data['exam_type'] === 'custom' && !empty($data['custom_criteria'])) {
            foreach ($data['custom_criteria'] as $key => $value) {
                $query->where($key, $value);
            }
        } else {
            if (!empty($data['institution_name'])) {
                $query->where('institution', $data['institution_name']);
            }
            if (!empty($data['year'])) {
                $query->where('year', $data['year']);
            }
        }

        return $query;
    }

    public function selectQuestions(Request $request, Exam $exam)
    {
        // Start with all questions - let user filter manually
        $query = Question::query();

        // Apply filters from request
        if ($request->filled('question_text')) {
            $query->where('question_text', 'like', '%' . $request->question_text . '%');
        }
        
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        
        if ($request->filled('chapter_id')) {
            $query->where('chapter_id', $request->chapter_id);
        }
        
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        
        if ($request->filled('source_name')) {
            $query->where('source_name', 'like', '%' . $request->source_name . '%');
        }
        
        if ($request->filled('source_type')) {
            $query->where('source_type', 'like', '%' . $request->source_type . '%');
        }

        // Class filter - filter questions by subject class
        if ($request->filled('class')) {
            $query->whereHas('subject', function($q) use ($request) {
                $q->where('class', $request->class);
            });
        }

        $questions = $query->with(['subject', 'chapter'])->paginate(50);

        // Filter subjects based on selected class
        $subjects = $request->filled('class') 
            ? \App\Models\Subject::where('class', $request->class)->get()
            : \App\Models\Subject::all();
            
        $chapters = \App\Models\Chapter::all();
        $boards = \App\Models\Board::all();
        $universities = \App\Models\University::all();

        return view('exams.select-questions', compact('exam', 'questions', 'subjects', 'chapters', 'boards', 'universities'));
    }

    public function attachQuestions(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:questions,id'
        ]);

        try {
            DB::beginTransaction();
            
            // Detach any existing questions
            $exam->questions()->detach();
            
            // Attach the selected questions
            $exam->questions()->attach($validated['question_ids']);
            
            DB::commit();
            
            return redirect()->route('exams.show', $exam)
                ->with('success', 'Questions attached to exam successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to attach questions. ' . $e->getMessage());
        }
    }

    public function show(Exam $exam)
    {
        $exam->load(['subject', 'chapter', 'questions']);
        
        // Check if the current user has already attempted this exam (for students)
        $userAttempt = null;
        if (auth()->check() && auth()->user()->role === 'student') {
            $userAttempt = ExamAttempt::where('exam_id', $exam->id)
                ->where('user_id', auth()->id())
                ->with(['answers.question', 'ratingChanges' => function($query) {
                    $query->where('user_id', auth()->id());
                }])
                ->first();
        }
        
        return view('exams.show', compact('exam', 'userAttempt'));
    }

    public function selectRandomQuestions(Request $request, Exam $exam)
    {
        $request->validate([
            'count' => 'required|integer|min:1|max:100',
        ]);

        $count = $request->input('count');

        // Build the same query as in selectQuestions method
        $query = Question::query();

        // Apply filters (same logic as selectQuestions)
        if ($request->filled('question_text')) {
            $query->where('question_text', 'like', '%' . $request->input('question_text') . '%');
        }

        if ($request->filled('class')) {
            $query->whereHas('subject', function ($q) use ($request) {
                $q->where('class', $request->input('class'));
            });
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->input('subject_id'));
        }

        if ($request->filled('chapter_id')) {
            $query->where('chapter_id', $request->input('chapter_id'));
        }

        if ($request->filled('year')) {
            $query->where('year', $request->input('year'));
        }

        if ($request->filled('source_type')) {
            $query->where('source_type', $request->input('source_type'));
        }

        // Get total count of matching questions
        $totalAvailable = $query->count();

        if ($totalAvailable == 0) {
            return response()->json([
                'success' => false,
                'message' => 'No questions found matching the current filters.'
            ]);
        }

        if ($count > $totalAvailable) {
            return response()->json([
                'success' => false,
                'message' => "Cannot select {$count} questions. Only {$totalAvailable} questions available matching the current filters."
            ]);
        }

        // Randomly select questions
        $selectedQuestions = $query->inRandomOrder()->limit($count)->get(['id', 'question_text']);

        return response()->json([
            'success' => true,
            'questions' => $selectedQuestions,
            'message' => "Successfully selected {$count} random questions from {$totalAvailable} available questions."
        ]);
    }

    public function edit(Request $request, Exam $exam)
    {
        // Filter subjects by class if provided
        $query = Subject::orderBy('class')->orderBy('name');
        
        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }
        
        $subjects = $query->get();
        $chapters = $exam->subject ? $exam->subject->chapters : collect();
        $selectedClass = $request->input('class', '');
        
        return view('exams.edit', compact('exam', 'subjects', 'chapters', 'selectedClass'));
    }

    public function update(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'exam_type' => ['required', Rule::in(['board', 'university', 'custom'])],
            'institution_name' => 'required_if:exam_type,university|nullable|string|max:255',
            'year' => 'required_if:exam_type,university|nullable|integer|min:1900|max:' . (date('Y') + 1),
            'subject_id' => 'required_unless:exam_type,custom|nullable|exists:subjects,id',
            'chapter_id' => 'nullable|exists:chapters,id',
            'start_time' => 'required_if:is_rated,1|nullable|date',
            'duration' => 'required|integer|min:1',
            'is_rated' => 'boolean',
            'difficulty_level' => 'required_if:is_rated,1|nullable|integer|min:1|max:4',
            'custom_criteria' => 'nullable|array'
        ]);

        // Process custom criteria
        if ($request->has('custom_criteria')) {
            $keys = $request->input('custom_criteria.key', []);
            $values = $request->input('custom_criteria.value', []);
            
            $customCriteria = [];
            for ($i = 0; $i < count($keys); $i++) {
                if (!empty($keys[$i]) && !empty($values[$i])) {
                    $customCriteria[$keys[$i]] = $values[$i];
                }
            }
            $validated['custom_criteria'] = empty($customCriteria) ? null : json_encode($customCriteria);
        }

        try {
            $exam->update($validated);
            
            return redirect()->route('exams.index')->with('success', 'Exam updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update exam. Please try again.');
        }
    }

    public function destroy(Exam $exam)
    {
        try {
            $exam->questions()->detach();
            $exam->delete();
            return redirect()->route('exams.index')->with('success', 'Exam deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('exams.index')->with('error', 'Failed to delete exam: ' . $e->getMessage());
        }
    }

    /**
     * Preview exam questions with correct answers (for board exams or finished exams)
     */
    public function preview(Exam $exam)
    {
        // Check if exam is finished
        $isFinished = false;
        if ($exam->start_time && $exam->duration) {
            $endTime = $exam->start_time->copy()->addMinutes($exam->duration);
            $isFinished = now()->greaterThan($endTime);
        }
        
        // Allow preview for board exams OR finished exams
        $canPreview = ($exam->exam_type === 'board') || $isFinished;
        
        if (!$canPreview) {
            return redirect()->route('exams.show', $exam)
                ->with('error', 'Preview is only available for board exams or after the exam has finished.');
        }

        $exam->load(['subject', 'chapter', 'questions']);
        return view('exams.preview', compact('exam'));
    }

    /**
     * Take exam (student exam taking interface without correct answers)
     */
    public function take(Exam $exam)
    {
        // Check if exam is finished
        $isFinished = false;
        if ($exam->start_time && $exam->duration) {
            $endTime = $exam->start_time->copy()->addMinutes($exam->duration);
            $isFinished = now()->greaterThan($endTime);
        }
        
        // Don't allow taking finished exams
        if ($isFinished) {
            return redirect()->route('exams.show', $exam)
                ->with('error', 'This exam has finished. You can review the questions using "Show Questions" button.');
        }
        
        // Check if exam is available (either always available or has started)
        if (!is_null($exam->start_time) && $exam->start_time > now()) {
            return redirect()->route('exams.show', $exam)
                ->with('error', 'This exam has not started yet. Start time: ' . $exam->start_time->format('M d, Y H:i'));
        }

        // For rated exams, check if user has already attempted
        if ($exam->is_rated) {
            $existingAttempt = ExamAttempt::where('exam_id', $exam->id)
                ->where('user_id', auth()->id())
                ->first();
            
            if ($existingAttempt) {
                return redirect()->route('exams.show', $exam)
                    ->with('error', 'You have already attempted this rated exam. You can only take rated exams once.');
            }
        }

        $exam->load(['subject', 'chapter', 'questions']);
        return view('exams.take', compact('exam'));
    }

    /**
     * Submit exam answers
     */
    public function submit(Request $request, Exam $exam)
    {
        try {
            // Check if exam is available
            if (!is_null($exam->start_time) && $exam->start_time > now()) {
                return redirect()->route('exams.show', $exam)
                    ->with('error', 'This exam has not started yet.');
            }

            // Validate answers
            $request->validate([
                'answers' => 'required|array|min:1',
                'answers.*' => 'nullable|string|in:A,B,C,D'
            ], [
                'answers.required' => 'Please answer at least one question.',
                'answers.min' => 'Please answer at least one question.',
                'answers.*.in' => 'Invalid answer option selected.'
            ]);

            $answers = $request->input('answers');
            $exam->load('questions');

            // Calculate score
            $totalQuestions = $exam->questions->count();
            $correctAnswers = 0;

            foreach ($exam->questions as $question) {
                $userAnswer = $answers[$question->id] ?? null;
                if ($userAnswer === $question->correct_answer) {
                    $correctAnswers++;
                }
            }

            $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;

            // ✅ NEW: Determine if we should save this attempt
            // Save if: (exam is rated) OR (exam has assigned users)
            $shouldSaveAttempt = $exam->is_rated || $exam->assignedUsers()->exists();
            
            $attempt = null;
            $ratingChange = null;
            
            if ($shouldSaveAttempt) {
                // Save to exam_attempts table
                $attempt = ExamAttempt::create([
                    'exam_id' => $exam->id,
                    'user_id' => auth()->id(),
                    'score' => $score,
                    'correct_ans' => $correctAnswers,
                    'wrong_ans' => $totalQuestions - $correctAnswers,
                    'total_ques' => $totalQuestions,
                ]);
                
                // Save individual answers to exam_answers table
                foreach ($exam->questions as $question) {
                    ExamAnswer::create([
                        'exam_attempt_id' => $attempt->id,
                        'question_id' => $question->id,
                        'user_answer' => $answers[$question->id] ?? null,
                        'is_correct' => ($answers[$question->id] ?? null) === $question->correct_answer,
                    ]);
                }
                
                // If exam is rated, update rating
                if ($exam->is_rated) {
                    $ratingChange = $this->updateRating($exam, auth()->user(), $attempt);
                }
            }

            // Prepare detailed results
            $results = [];
            foreach ($exam->questions as $question) {
                $userAnswer = $answers[$question->id] ?? null;
                $results[] = [
                    'question' => $question,
                    'user_answer' => $userAnswer,
                    'is_correct' => $userAnswer === $question->correct_answer
                ];
            }

            return view('exams.result', compact(
                'exam', 
                'score', 
                'correctAnswers', 
                'totalQuestions', 
                'results',
                'ratingChange',
                'attempt'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Exam submission error: ' . $e->getMessage(), [
                'exam_id' => $exam->id,
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('exams.show', $exam)
                ->with('error', 'An error occurred while submitting your exam. Please try again. Error: ' . $e->getMessage());
        }
    }

    private function updateRating(Exam $exam, User $user, ExamAttempt $attempt)
    {
        // Get all attempts for this exam to calculate rank
        $allAttempts = ExamAttempt::where('exam_id', $exam->id)
            ->orderBy('score', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();
        
        $rank = $allAttempts->search(function($att) use ($attempt) {
            return $att->id === $attempt->id;
        }) + 1;
        
        // Use RatingCalculator service
        $ratingCalculator = app(RatingCalculator::class);
        $oldRating = $user->rating;
        $newRating = $ratingCalculator->calculateNewRating(
            $oldRating,
            $exam->difficulty_level ?? 2, // Default to medium difficulty if not set
            $attempt->score,
            $rank,
            $allAttempts->count()
        );
        
        // Save rating change
        $ratingChange = RatingChange::create([
            'user_id' => $user->id,
            'exam_id' => $exam->id,
            'old_rating' => $oldRating,
            'new_rating' => $newRating,
            'rank_in_contest' => $rank,
        ]);
        
        // Update user rating
        $user->update([
            'rating' => $newRating,
            'max_rating' => max($user->max_rating, $newRating),
            'total_solved' => $user->total_solved + 1,
        ]);
        
        // Update exam statistics
        $this->updateExamStatistics($exam);
        
        return $ratingChange;
    }

    private function updateExamStatistics(Exam $exam)
    {
        $attempts = ExamAttempt::where('exam_id', $exam->id)->get();
        
        ExamStatistic::updateOrCreate(
            ['exam_id' => $exam->id],
            [
                'total_participants' => $attempts->count(),
                'average_score' => $attempts->avg('score'),
                'highest_score' => $attempts->max('score'),
                'lowest_score' => $attempts->min('score'),
            ]
        );
    }
}

