<?php

namespace App\Http\Controllers;

use App\Helpers\AuditLogger;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::where('created_by', auth()->id())
            ->withCount('exams')
            ->with('classes')
            ->latest()
            ->get();
        return view('lecturer.subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('lecturer.subjects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $subject = Subject::create([
            'name' => $request->name,
            'created_by' => auth()->id(),
        ]);

        AuditLogger::log('create_subject', 'Created subject: ' . $subject->name);

        return redirect()->route('lecturer.subjects.index')->with('success', 'Subject created successfully!');
    }

    public function edit(Subject $subject)
    {
        if ($subject->created_by !== auth()->id()) {
            abort(403);
        }
        return view('lecturer.subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        if ($subject->created_by !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name,' . $subject->id,
        ]);

        $subject->update(['name' => $request->name]);

        AuditLogger::log('update_subject', 'Updated subject: ' . $subject->name);

        return redirect()->route('lecturer.subjects.index')->with('success', 'Subject updated successfully!');
    }

    public function destroy(Subject $subject)
    {
        if ($subject->created_by !== auth()->id()) {
            abort(403);
        }

        AuditLogger::log('delete_subject', 'Deleted subject: ' . $subject->name);
        $subject->delete();
        return redirect()->route('lecturer.subjects.index')->with('success', 'Subject deleted successfully!');
    }

    public function show(Subject $subject)
    {
        return redirect()->route('lecturer.subjects.index');
    }
}