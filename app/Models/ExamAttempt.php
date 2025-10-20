<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    protected $fillable = [
        'exam_id', 'user_id', 'correct_ans', 'wrong_ans', 'total_ques', 'score'
    ];

    // Relationships
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(ExamAnswer::class, 'exam_attempt_id');
    }

    public function ratingChanges()
    {
        return $this->hasMany(RatingChange::class, 'exam_id', 'exam_id')
            ->where('user_id', $this->user_id);
    }
}
