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

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'exam_user_assignments');
    }

    // Helper methods
    public function isAvailable()
    {
        // An exam is available if:
        // 1. It has questions
        // 2. If it's scheduled, the start time hasn't passed or there's no end time restriction
        
        if ($this->questions()->count() == 0) {
            return false;
        }

        // If exam has a start time, check if it's available
        if ($this->start_time) {
            $now = now();
            
            // If start time is in the future, not available yet
            if ($this->start_time > $now) {
                return false;
            }
            
            // If end time is set and has passed, not available
            if ($this->end_time && $this->end_time < $now) {
                return false;
            }
        }

        return true;
    }

    public function getStatusAttribute()
    {
        if (!$this->isAvailable()) {
            return 'Not Available';
        }

        if ($this->start_time) {
            $now = now();
            
            if ($this->start_time > $now) {
                return 'Scheduled';
            }
            
            if ($this->end_time && $this->end_time < $now) {
                return 'Ended';
            }
            
            return 'Active';
        }

        return 'Available';
    }
}
