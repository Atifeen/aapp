<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Question;

class QuestionImportController extends Controller
{
    public function showForm()
    {
        return view('questions/import'); // Blade form
    }

    public function import(Request $request)
{
    $request->validate([
        'json_file'    => 'required|file|mimes:json',
        'subject_id'   => 'nullable|exists:subjects,id',
        'chapter_id'   => 'nullable|exists:chapters,id',
        'source_name'  => 'nullable|string|max:255',
        'source_type'  => 'nullable|string|max:255',
        'year'         => 'nullable|integer',
    ]);

    // Get the uploaded file
    $uploadedFile = $request->file('json_file');

    // Read JSON content directly from the uploaded file
    $json = file_get_contents($uploadedFile->getRealPath());

    $questions = json_decode($json, true);

    if (!$questions) {
        return redirect()->back()->with('error', 'Invalid JSON file.');
    }

    $mapping = ['ক' => 'A', 'খ' => 'B', 'গ' => 'C', 'ঘ' => 'D'];

    foreach ($questions as $q) {
        $correct_option = $mapping[$q['answer'] ?? 'ক'] ?? 'A';

        Question::create([
            'question_text'  => $q['question'] ?? '',
            'option_a'       => $q['options'][0] ?? '',
            'option_b'       => $q['options'][1] ?? '',
            'option_c'       => $q['options'][2] ?? '',
            'option_d'       => $q['options'][3] ?? '',
            'correct_option' => $correct_option,
            'image'          => $q['image'] ?? null,
            'subject_id'     => $request->subject_id,
            'chapter_id'     => $request->chapter_id,
            'source_name'    => $request->source_name,
            'source_type'    => $request->source_type,
            'year'           => $request->year,
        ]);
    }

    return redirect()->back()->with('success', count($questions) . ' questions imported successfully.');
}

}
