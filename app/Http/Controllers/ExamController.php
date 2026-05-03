<?php

namespace App\Http\Controllers;

use App\Helpers\AuditLogger;
use App\Models\Classes;
use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $query = Exam::where('created_by', auth()->id())
            ->with('subject')
            ->withCount('questions');

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->status) {
            if ($request->status === 'published') {
                $query->where('is_published', true)->where(function($q) {
                    $q->whereNull('due_at')->orWhere('due_at', '>=', now());
                });
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false)->whereNull('publish_at');
            } elseif ($request->status === 'scheduled') {
                $query->where('is_published', false)->whereNotNull('publish_at')->where('publish_at', '>', now());
            } elseif ($request->status === 'expired') {
                $query->whereNotNull('due_at')->where('due_at', '<', now());
            }
        }

        $sort = $request->sort ?? 'created_at';
        $direction = $request->direction ?? 'desc';

        if (in_array($sort, ['title', 'time_limit', 'created_at'])) {
            $query->orderBy($sort, $direction);
        }

        $exams = $query->get();
        $subjects = Subject::where('created_by', auth()->id())->get();

        return view('lecturer.exams.index', compact('exams', 'subjects'));
    }

    public function create()
    {
        $subjects = Subject::where('created_by', auth()->id())->get();
        return view('lecturer.exams.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'time_limit' => 'required|integer|min:1',
        ]);

        $exam = Exam::create([
            'title' => $request->title,
            'subject_id' => $request->subject_id,
            'time_limit' => $request->time_limit,
            'created_by' => auth()->id(),
            'is_published' => false,
            'publish_at' => $request->publish_at ?: null,
            'due_at' => $request->due_at ?: null,
        ]);

        AuditLogger::log('create_exam', 'Created exam: ' . $exam->title);

        return redirect()->route('lecturer.exams.questions.index', $exam)->with('success', 'Exam created! Now add questions.');
        
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'time_limit' => 'required|integer|min:1',
            'publish_at' => 'nullable|date',
            'due_at' => 'nullable|date|after:publish_at',
        ]);
    }

    public function show(Exam $exam)
    {
        $exam->load('subject', 'questions.options');
        return view('lecturer.exams.show', compact('exam'));
    }

    public function edit(Exam $exam)
    {
        $subjects = Subject::where('created_by', auth()->id())->get();
        return view('lecturer.exams.edit', compact('exam', 'subjects'));
    }

    public function update(Request $request, Exam $exam)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'time_limit' => 'required|integer|min:1',
        ]);

        $exam->update([
            'title' => $request->title,
            'subject_id' => $request->subject_id,
            'time_limit' => $request->time_limit,
            'publish_at' => $request->publish_at ?: null,
            'due_at' => $request->due_at ?: null,
        ]);

        AuditLogger::log('update_exam', 'Updated exam: ' . $exam->title);

        return redirect()->route('lecturer.exams.index')->with('success', 'Exam updated successfully!');

        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'time_limit' => 'required|integer|min:1',
            'publish_at' => 'nullable|date',
            'due_at' => 'nullable|date|after:publish_at',
        ]);
    }

    public function destroy(Exam $exam)
    {
        AuditLogger::log('delete_exam', 'Deleted exam: ' . $exam->title);
        $exam->delete();
        return redirect()->route('lecturer.exams.index')->with('success', 'Exam deleted successfully!');
    }

    public function publish(Exam $exam)
    {
        if ($exam->questions()->count() === 0) {
            return back()->with('error', 'Cannot publish exam with no questions!');
        }

        $exam->update(['is_published' => true]);
        AuditLogger::log('publish_exam', 'Published exam: ' . $exam->title);

        return back()->with('success', 'Exam published successfully!');
    }

    public function unpublish(Exam $exam)
    {
        $exam->update(['is_published' => false]);
        AuditLogger::log('unpublish_exam', 'Unpublished exam: ' . $exam->title);

        return back()->with('success', 'Exam unpublished successfully!');
    }
}