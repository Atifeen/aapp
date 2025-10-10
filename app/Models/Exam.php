<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'title', 'subject_id', 'chapter_id', 'start_time', 'end_time',
        'exam_type', 'institution_name', 'year', 'custom_criteria',
        'duration', 'is_rated', 'difficulty_level'
    ];

    protected $casts = [
        'custom_criteria' => 'array',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_rated' => 'boolean'
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
