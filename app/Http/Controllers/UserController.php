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
        $userName = $user->name;
        $user->delete();

        return redirect()->route('students.index')
            ->with('success', "Student '{$userName}' has been deleted successfully.");
    }
}
