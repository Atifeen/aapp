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
        
        // Get board exams
        $boardExams = Exam::with(['subject', 'questions'])
            ->where('exam_type', 'board')
            ->orderBy('year', 'desc')
            ->orderBy('title')
            ->get();
        
        // Get university exams
        $universityExams = Exam::with(['subject', 'questions'])
            ->where('exam_type', 'university')
            ->orderBy('year', 'desc')
            ->orderBy('title')
            ->get();
        
        // Get custom exams (available ones)
        $customExams = Exam::with(['subject', 'questions'])
            ->where('exam_type', 'custom')
            ->where(function($q) use ($user) {
                $q->whereNull('start_time') // Available anytime
                  ->orWhere(function($subQ) {
                      // Active or upcoming custom exams
                      $subQ->whereNotNull('start_time')
                           ->where('start_time', '>=', now()->subDays(1));
                  })
                  ->orWhereHas('assignedUsers', function($assignQ) use ($user) {
                      // Assigned to this user
                      $assignQ->where('user_id', $user->id);
                  });
            })
            ->orderByRaw('start_time IS NULL DESC, start_time ASC')
            ->get();

        return view('student.dashboard', compact(
            'boardExams',
            'universityExams',
            'customExams'
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