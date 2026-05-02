<?php

namespace App\Http\Controllers;

use App\Helpers\AuditLogger;
use App\Models\Classes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LecturerController extends Controller
{
    public function students()
    {
        $students = User::where('role', 'student')->with('classes')->latest()->get();
        return view('lecturer.students.index', compact('students'));
    }

    public function createStudent()
    {
        $classes = Classes::all();
        return view('lecturer.students.create', compact('classes'));
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'class_ids' => 'nullable|array',
        ]);

        $student = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        if ($request->class_ids) {
            $student->classes()->sync($request->class_ids);
        }

        AuditLogger::log('create_student', 'Created student: ' . $student->name);

        return redirect()->route('lecturer.students.index')->with('success', 'Student created successfully!');
    }

    public function destroyStudent(User $user)
    {
        AuditLogger::log('delete_student', 'Deleted student: ' . $user->name);
        $user->delete();
        return redirect()->route('lecturer.students.index')->with('success', 'Student deleted successfully!');
    }
}