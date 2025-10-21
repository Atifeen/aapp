<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    protected $fillable = [
        'exam_id', 'user_id', 'started_at', 'correct_ans', 'wrong_ans', 'total_ques', 'score'
    ];

    protected $casts = [
        'started_at' => 'datetime',
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
        return $this->hasMany(ExamAnswer::class, 'attempt_id');
    }

    public function ratingChanges()
    {
        return $this->hasMany(RatingChange::class, 'exam_id', 'exam_id')
            ->where('user_id', $this->user_id);
    }
}
