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
            'subject_id' => 'required|exists:subjects,id',
            'chapter_id' => 'required_if:exam_type,custom|nullable|exists:chapters,id',
            'start_time' => 'required_if:is_rated,1|nullable|date',
            'duration' => 'required|integer|min:1',
            'is_rated' => 'boolean',
            'difficulty_level' => 'required_if:is_rated,1|nullable|integer|min:1|max:4',
            'custom_criteria' => 'required_if:exam_type,custom|nullable|array'
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
        $questions = Question::where('subject_id', $exam->subject_id)
            ->when($exam->chapter_id, function($query) use ($exam) {
                return $query->where('chapter_id', $exam->chapter_id);
            })
            ->when($request->filled('source_type'), function($query) use ($request) {
                return $query->where('source_type', $request->source_type);
            })
            ->when($request->filled('year'), function($query) use ($request) {
                return $query->where('year', $request->year);
            })
            ->with(['subject', 'chapter'])
            ->paginate(15);

        // Get all unique source types and years for filters
        $sourceTypes = Question::distinct('source_type')->whereNotNull('source_type')->pluck('source_type');
        $years = Question::distinct('year')->whereNotNull('year')->orderByDesc('year')->pluck('year');
        $subjects = Subject::orderBy('name')->get();

        return view('exams.select-questions', compact('exam', 'questions', 'sourceTypes', 'years', 'subjects'));
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
