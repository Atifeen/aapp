<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionImportController;
use App\Http\Controllers\UserController;

// ----------------------------
// Guest routes (unauthenticated)
// ----------------------------
// Exam management routes (admin only)
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':admin'])->group(function () {
    // Exam Management
    Route::prefix('exams')->name('exams.')->group(function () {
        Route::get('/create', [ExamController::class, 'create'])->name('create');
        Route::post('/', [ExamController::class, 'store'])->name('store');
        Route::get('/chapters/{subject}', [ExamController::class, 'getChapters'])->name('chapters');
        Route::get('/{exam}/edit', [ExamController::class, 'edit'])->name('edit');
        Route::put('/{exam}', [ExamController::class, 'update'])->name('update');
        Route::get('/{exam}/questions', [ExamController::class, 'selectQuestions'])->name('questions.select');
        Route::post('/{exam}/questions/random', [ExamController::class, 'selectRandomQuestions'])->name('questions.random');
        Route::post('/{exam}/questions', [ExamController::class, 'attachQuestions'])->name('questions.attach');
        Route::delete('/{exam}', [ExamController::class, 'destroy'])->name('destroy');
    });
});

// Shared exam routes (both admin and student)
Route::middleware(['auth'])->group(function () {
    Route::prefix('exams')->name('exams.')->group(function () {
        Route::get('/', [ExamController::class, 'index'])->name('index');
        Route::get('/{exam}', [ExamController::class, 'show'])->name('show');
    });
});

// Student exam routes
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':student'])->group(function () {
    Route::prefix('exams')->name('exams.')->group(function () {
        Route::get('/{exam}/preview', [ExamController::class, 'preview'])->name('preview');
        Route::get('/{exam}/take', [ExamController::class, 'take'])->name('take');
        Route::post('/{exam}/submit', [ExamController::class, 'submit'])->name('submit');
    });
});

Route::middleware('guest')->group(function () {
    // Registration
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Login
    Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
});

// Logout (requires auth)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
});

// ----------------------------
// Authenticated routes (any logged-in user)
// ----------------------------
Route::middleware('auth')->group(function () {
    // Home redirects to appropriate dashboard
    Route::get('/', [HomeController::class, 'index'])->name('home');
    
    // Admin routes
    Route::get('/admin/dashboard', [HomeController::class, 'adminDashboard'])
        ->middleware(\App\Http\Middleware\CheckRole::class . ':admin')
        ->name('admin.dashboard');

    // Student routes
    Route::get('/student/dashboard', [HomeController::class, 'studentDashboard'])
        ->middleware(\App\Http\Middleware\CheckRole::class . ':student')
        ->name('student.dashboard');
    
    Route::get('/leaderboard', [HomeController::class, 'leaderboard'])
        ->middleware(\App\Http\Middleware\CheckRole::class . ':student')
        ->name('leaderboard');
    
    Route::get('/exam-history', [HomeController::class, 'examHistory'])
        ->middleware(\App\Http\Middleware\CheckRole::class . ':student')
        ->name('exam.history');

    // Logout
    Route::post('/logout', [AuthController::class,'logout'])->name('logout');
});

// ----------------------------
// Admin-only routes
// ----------------------------
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class . ':admin'])->group(function () {
    // Student Management
    Route::get('/students', [UserController::class, 'index'])->name('students.index');
    Route::delete('/students/{user}', [UserController::class, 'destroy'])->name('students.destroy');
    
    // Questions CRUD
    Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');
    Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::put('/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');

    // AJAX dependent dropdowns
    Route::get('/subjects/by-class/{class}', [QuestionController::class, 'subjectsByClass']);
    Route::get('/chapters/by-subject/{subjectId}', [QuestionController::class, 'chaptersBySubject']);

    // API routes for dependent dropdowns (optional)
    Route::get('/questions/get-subjects', [QuestionController::class, 'getSubjects']);
    Route::get('/questions/get-chapters', [QuestionController::class, 'getChapters']);
    Route::get('/questions/import', [QuestionImportController::class, 'showForm'])->name('questions.import.form');
    Route::post('/questions/import', [QuestionImportController::class, 'import'])->name('questions.import');
});




 
