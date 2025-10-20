<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Chapter;

class QuestionController extends Controller 
{
    public function index(Request $request) {
        $query = Question::with(['subject', 'chapter']);

        // Class filter - filter questions by subject class
        if ($request->filled('class')) {
            $query->whereHas('subject', function($q) use ($request) {
                $q->where('class', $request->class);
            });
        }

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

        // Add pagination with 50 questions per page
        $questions = $query->latest()->paginate(50)->withQueryString();
        
        // Filter subjects based on selected class
        $subjects = $request->filled('class') 
            ? \App\Models\Subject::where('class', $request->class)->get()
            : \App\Models\Subject::all();
            
        $chapters = \App\Models\Chapter::all();
        $boards = \App\Models\Board::all();
        $universities = \App\Models\University::all();
        return view('questions.index', compact('questions', 'subjects', 'chapters', 'boards', 'universities'));
    }


    public function store(Request $request) {
        Question::create($request->all());
        return redirect()->back()->with('success', 'Question added!');
    }

    public function update(Request $request, Question $question) {
        $question->update($request->all());
        return redirect()->back()->with('success', 'Question updated!');
    }

    public function destroy(Question $question) {
        $question->delete();
        return redirect()->back()->with('success', 'Question deleted!');
    }

    // AJAX: Get subjects by class
public function getSubjects(Request $request) {
    $class = $request->query('class');
    $subjects = \App\Models\Subject::where('class', $class)->get();
    return response()->json($subjects);
}

public function getChapters(Request $request) {
    $subject_id = $request->query('subject_id');
    $chapters = \App\Models\Chapter::where('subject_id', $subject_id)->get();
    return response()->json($chapters);
}

}
