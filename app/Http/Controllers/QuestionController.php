<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;



class QuestionController extends Controller 
{
        public function index() {
        $questions = Question::all();
        return view('questions.index', compact('questions'));
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

}
