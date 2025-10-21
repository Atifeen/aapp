<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'question_text', 'subject_id', 'chapter_id',
        'option_a', 'option_b', 'option_c', 'option_d',
        'correct_option', 'source_name', 'source_type', 'year',
        'image' // newly added field
    ];

    // Relationships
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_question');
    }

    public function examAnswers()
    {
        return $this->hasMany(ExamAnswer::class);
    }
    
    // Accessor for correct_answer (alias for correct_option)
    public function getCorrectAnswerAttribute()
    {
        return $this->correct_option;
    }
    
    // Accessor for options array
    public function getOptionsAttribute()
    {
        $options = [];
        foreach (['option_a', 'option_b', 'option_c', 'option_d'] as $key) {
            if (!empty($this->$key)) {
                $options[] = $this->$key;
            }
        }
        return $options;
    }
}
