<?php

namespace App\Http\Controllers;

use App\Helpers\AuditLogger;
use App\Models\Answer;
use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;

class GradingController extends Controller
{
    public function index()
    {
        $exams = Exam::where('created_by', auth()->id())
            ->withCount('attempts')
            ->with('subject')
            ->latest()
            ->get();

        return view('lecturer.grading.index', compact('exams'));
    }

    public function show(ExamAttempt $attempt)
    {
        $attempt->load('user', 'exam.questions.options', 'answers.selectedOption', 'answers.question');

        return view('lecturer.grading.show', compact('attempt'));
    }

    public function grade(Request $request, ExamAttempt $attempt)
    {
        $request->validate([
            'grades' => 'required|array',
            'grades.*' => 'nullable|numeric|min:0',
        ]);

        $totalScore = 0;

        foreach ($request->grades as $answerId => $marks) {
            $answer = Answer::find($answerId);
            if ($answer) {
                $maxMarks = $answer->question->marks;
                $marksAwarded = min($marks ?? 0, $maxMarks);
                $answer->update(['marks_awarded' => $marksAwarded]);
            }
        }

        // Recalculate total score
        $totalScore = $attempt->answers()->sum('marks_awarded');
        $attempt->update(['total_score' => $totalScore]);

        AuditLogger::log('grade_attempt', 'Graded attempt ID: ' . $attempt->id);

        return back()->with('success', 'Grades saved successfully!');
    }

    public function release(ExamAttempt $attempt)
    {
        $attempt->update(['is_released' => true]);

        AuditLogger::log('release_result', 'Released result for attempt ID: ' . $attempt->id);

        return back()->with('success', 'Result released to student!');
    }
}