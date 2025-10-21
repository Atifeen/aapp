<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'title', 'subject_id', 'chapter_id', 'start_time',
        'exam_type', 'board_name', 'university_name', 'institution_name', 
        'year', 'custom_criteria', 'duration'
    ];

    protected $casts = [
        'custom_criteria' => 'array',
        'start_time' => 'datetime'
    ];

    // Accessor to calculate end_time dynamically
    public function getEndTimeAttribute()
    {
        if ($this->start_time && $this->duration) {
            return $this->start_time->copy()->addMinutes($this->duration);
        }
        return null;
    }

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
