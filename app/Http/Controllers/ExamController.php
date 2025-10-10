<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::with(['subject', 'chapter'])->latest()->paginate(10);
        return view('exams.index', compact('exams'));
    }

    public function create()
    {
        $subjects = Subject::orderBy('class')->orderBy('name')->get();
        return view('exams.create', compact('subjects'));
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
        $query = Question::where('subject_id', $exam->subject_id)
            ->when($exam->chapter_id, function($query) use ($exam) {
                return $query->where('chapter_id', $exam->chapter_id);
            });

        // Apply filters
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

        $questions = $query->with(['subject', 'chapter'])->paginate(15);

        // Get data for filters
        $subjects = \App\Models\Subject::all();
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
        return view('exams.show', compact('exam'));
    }
}
