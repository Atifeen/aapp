<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root to login page
Route::get('/', function () {
    return redirect()->route('login');
});

// Guest routes (only accessible when NOT logged in)
Route::middleware('guest')->group(function () {
    // Show registration form
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    
    // Handle registration submission
    Route::post('/register', [AuthController::class, 'register']);
    
    // Show login form
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    
    // Handle login submission
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes (only accessible when logged in)
Route::middleware('auth')->group(function () {
    // Dashboard/Home page
    Route::get('/home', function () {
        return view('home');
    })->name('home');
    
    // Handle logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // You can add more protected routes here later
    // Example:
    // Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');
    // Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
});