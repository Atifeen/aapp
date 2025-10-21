<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of students.
     */
    public function index()
    {
        $students = User::where('role', 'student')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.students.index', compact('students'));
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(User $user)
    {
        // Check if the user is a student
        if ($user->role !== 'student') {
            return redirect()->route('students.index')
                ->with('error', 'Only students can be deleted from this page.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('students.index')
            ->with('success', "Student '{$userName}' has been deleted successfully.");
    }
}
