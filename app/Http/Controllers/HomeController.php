<?php

namespace App\Http\Controllers;

use App\Models\Exam;
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
        $data = [
            'totalExams' => Exam::count(),
            'totalRatedExams' => Exam::where('is_rated', true)->count(),
            'upcomingExams' => Exam::where('start_time', '>', now())->count(),
            'activeExams' => Exam::where('start_time', '<=', now())
                                ->where('end_time', '>', now())
                                ->count(),
            'recentExams' => Exam::with(['subject'])
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get()
        ];
        return view('admin.dashboard', $data);
    }

    public function studentDashboard()
    {
        $user = auth()->user();
        
        // Get available exams
        $availableExams = Exam::where('start_time', '>', now())
                           ->orderBy('start_time')
                           ->take(6)
                           ->get();

        // Get student statistics
        $totalParticipations = $user->attempts()->count();
        $successRate = $user->attempts()->avg('score') ?? 0;
        $successRate = round($successRate);

        // Get next exam time
        $nextExam = Exam::where('start_time', '>', now())
                       ->orderBy('start_time')
                       ->first();
        $nextExamTime = $nextExam ? $nextExam->start_time->format('H:i') : null;

        // Get student rank
        $rank = \App\Models\User::where('role', 'student')
                               ->where('rating', '>=', $user->rating)
                               ->count();

        $data = [
            'availableExams' => $availableExams,
            'totalParticipations' => $totalParticipations,
            'successRate' => $successRate,
            'nextExamTime' => $nextExamTime,
            'rank' => $rank
        ];

        return view('student.dashboard', $data);
    }
}