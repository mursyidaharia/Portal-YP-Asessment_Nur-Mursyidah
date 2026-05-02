<?php

namespace App\Http\Controllers;

use App\Helpers\AuditLogger;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = Classes::withCount('students')->with('subjects')->latest()->get();
        return view('lecturer.classes.index', compact('classes'));
    }

    public function create()
    {
        $subjects = Subject::all();
        $students = User::where('role', 'student')->get();
        return view('lecturer.classes.create', compact('subjects', 'students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject_ids' => 'nullable|array',
            'student_ids' => 'nullable|array',
        ]);

        $class = Classes::create(['name' => $request->name]);

        if ($request->subject_ids) {
            $class->subjects()->sync($request->subject_ids);
        }

        if ($request->student_ids) {
            $class->students()->sync($request->student_ids);
        }

        AuditLogger::log('create_class', 'Created class: ' . $class->name);

        return redirect()->route('lecturer.classes.index')->with('success', 'Class created successfully!');
    }

    public function show(Classes $class)
    {
        $class->load('subjects', 'students');
        return view('lecturer.classes.show', compact('class'));
    }

    public function edit(Classes $class)
    {
        $subjects = Subject::all();
        $students = User::where('role', 'student')->get();
        $class->load('subjects', 'students');
        return view('lecturer.classes.edit', compact('class', 'subjects', 'students'));
    }

    public function update(Request $request, Classes $class)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject_ids' => 'nullable|array',
            'student_ids' => 'nullable|array',
        ]);

        $class->update(['name' => $request->name]);
        $class->subjects()->sync($request->subject_ids ?? []);
        $class->students()->sync($request->student_ids ?? []);

        AuditLogger::log('update_class', 'Updated class: ' . $class->name);

        return redirect()->route('lecturer.classes.index')->with('success', 'Class updated successfully!');
    }

    public function destroy(Classes $class)
    {
        AuditLogger::log('delete_class', 'Deleted class: ' . $class->name);
        $class->delete();
        return redirect()->route('lecturer.classes.index')->with('success', 'Class deleted successfully!');
    }
}