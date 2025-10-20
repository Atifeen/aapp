<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('student.dashboard');
    }

    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    public function studentDashboard()
    {
        $user = auth()->user();
        
        // Get available exams with updated logic
        $availableExams = Exam::with(['subject', 'questions'])
            ->where(function($query) use ($user) {
                $query->where(function($q) {
                    // Board and university exams (always available)
                    $q->whereIn('exam_type', ['board', 'university']);
                })
                ->orWhere(function($q) {
                    // Custom unrated exams with no start time
                    $q->where('exam_type', 'custom')
                      ->where('is_rated', false)
                      ->whereNull('start_time');
                })
                ->orWhere(function($q) {
                    // Rated exams (show all future and recent rated exams)
                    $q->where('is_rated', true)
                      ->where(function($subQ) {
                          $subQ->whereNull('start_time')
                               ->orWhere('start_time', '>=', now()->subDays(1)); // Show rated exams from last 24 hours
                      });
                })
                ->orWhereHas('assignedUsers', function($q) use ($user) {
                    // Exams assigned to this user
                    $q->where('user_id', $user->id);
                });
            })
            ->orderByRaw('start_time IS NULL DESC, start_time ASC')
            ->get();
        
        // Get student's rating and rank
        $currentRating = $user->rating;
        $maxRating = $user->max_rating;
        $totalSolved = $user->total_solved;
        
        // Calculate rank (number of students with higher rating + 1)
        $rank = User::where('role', 'student')
            ->where('rating', '>', $currentRating)
            ->count() + 1;
        
        // Update user's rank
        $user->update(['rank' => $rank]);
        
        // Get recent attempts
        $recentAttempts = ExamAttempt::with('exam')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
        
        // Legacy statistics for compatibility
        $totalParticipations = $user->attempts()->count();
        $successRate = $user->attempts()->avg('score') ?? 0;
        $successRate = round($successRate);

        return view('student.dashboard', compact(
            'availableExams',
            'currentRating',
            'maxRating',
            'rank',
            'totalSolved',
            'recentAttempts',
            'totalParticipations',
            'successRate'
        ));
    }

    public function leaderboard()
    {
        $students = User::where('role', 'student')
            ->orderBy('rating', 'desc')
            ->orderBy('name', 'asc')
            ->paginate(50);
        
        return view('student.leaderboard', compact('students'));
    }

    public function examHistory()
    {
        $attempts = ExamAttempt::with(['exam', 'exam.subject'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(20);
        
        return view('student.exam-history', compact('attempts'));
    }
}