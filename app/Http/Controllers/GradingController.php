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
            ->with(['subject', 'questions', 'attempts'])
            ->latest()
            ->get();

        return view('lecturer.grading.index', compact('exams'));
    }
    
    public function show(ExamAttempt $attempt)
    {
        $attempt->load('user', 'exam.questions.options', 'answers.selectedOption', 'answers.question');

        // Get all attempts for this exam for navigation
        $allAttempts = ExamAttempt::where('exam_id', $attempt->exam_id)
            ->where('status', 'submitted')
            ->with('user')
            ->get();

        $currentIndex = $allAttempts->search(fn($a) => $a->id === $attempt->id);
        $prevAttempt = $currentIndex > 0 ? $allAttempts[$currentIndex - 1] : null;
        $nextAttempt = $currentIndex < $allAttempts->count() - 1 ? $allAttempts[$currentIndex + 1] : null;

        return view('lecturer.grading.show', compact('attempt', 'allAttempts', 'prevAttempt', 'nextAttempt'));
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

        return redirect()->route('lecturer.grading.show', $attempt)->with('success', 'Result released to student!');
    }

    public function bulkRelease(Exam $exam)
    {
        $attempts = ExamAttempt::where('exam_id', $exam->id)
            ->where('status', 'submitted')
            ->get();

        // Check if all open text questions have been graded
        foreach ($attempts as $attempt) {
            $openTextAnswers = $attempt->answers()
                ->whereHas('question', fn($q) => $q->where('type', 'open_text'))
                ->whereNull('marks_awarded')
                ->count();

            if ($openTextAnswers > 0) {
                return back()->with('error', 'Some open text answers have not been graded yet. Please grade all answers before releasing.');
            }
        }

        foreach ($attempts as $attempt) {
            $attempt->update(['is_released' => true]);
        }

        AuditLogger::log('bulk_release', 'Bulk released results for exam: ' . $exam->title);

        return back()->with('success', 'All results released successfully!');
    }
}