<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here you can register web routes for your application.
|
*/

// Redirect root to login page
Route::get('/', fn() => redirect()->route('login'));

// Guest routes (only accessible when NOT logged in)
Route::middleware('guest')->group(function () {
    // Registration
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes (only accessible when logged in)
Route::middleware('auth')->group(function () {
    // Dashboard/Home page
    Route::get('/home', fn() => view('home'))->name('home');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Questions management - full CRUD handled inside controller
    Route::resource('questions', QuestionController::class);
});
