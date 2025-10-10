<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatingChange extends Model
{
    protected $fillable = [
        'user_id',
        'exam_id',
        'old_rating',
        'new_rating',
        'rank_in_contest'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}