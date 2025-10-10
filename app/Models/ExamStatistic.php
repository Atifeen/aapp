<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamStatistic extends Model
{
    protected $fillable = [
        'exam_id',
        'total_participants',
        'average_score',
        'highest_score',
        'lowest_score',
        'score_distribution'
    ];

    protected $casts = [
        'score_distribution' => 'array'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}