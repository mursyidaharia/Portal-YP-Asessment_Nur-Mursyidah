<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function exams()
    {
        $user = auth()->user();

        $exams = Exam::whereHas('subject.classes', function ($q) use ($user) {
            $q->whereHas('students', function ($q2) use ($user) {
                $q2->where('users.id', $user->id);
            });
        })
        ->where(function($q) {
            $q->where('is_published', true)
            ->orWhere(function($q2) {
                $q2->whereNotNull('publish_at')
                    ->where('publish_at', '<=', now());
            });
        })
        
        ->with('subject')
        ->get()
        ->map(function ($exam) use ($user) {
            $exam->attempt = ExamAttempt::where('user_id', $user->id)
                ->where('exam_id', $exam->id)
                ->first();
            return $exam;
        });

        return view('student.exams', compact('exams'));
    }

    public function history()
    {
        $user = auth()->user();

        $attempts = ExamAttempt::where('user_id', $user->id)
            ->where('status', 'submitted')
            ->with('exam.subject')
            ->latest()
            ->get();

        return view('student.history', compact('attempts'));
    }

    public function result(ExamAttempt $attempt)
    {
        // Make sure student can only see their own results
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if result is released
        if (!$attempt->is_released) {
            return back()->with('error', 'Result has not been released yet.');
        }

        $attempt->load('exam.questions.options', 'answers.selectedOption', 'answers.question');

        return view('student.result', compact('attempt'));
    }
}