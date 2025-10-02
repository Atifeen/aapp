<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'name', 'subject_id', 'chapter_id', 'start_time', 'end_time'
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

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'exam_question');
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }
}
