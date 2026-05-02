<?php

namespace App\Http\Controllers;

use App\Helpers\AuditLogger;
use App\Models\Answer;
use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;

class ExamAttemptController extends Controller
{
    public function start(Exam $exam)
    {
        $user = auth()->user();

        // Check if student already attempted
        $existingAttempt = ExamAttempt::where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->first();

        if ($existingAttempt) {
            if ($existingAttempt->status === 'in_progress') {
                return redirect()->route('student.exams.attempt', $exam);
            }
            return back()->with('error', 'You have already attempted this exam.');
        }

        // Create new attempt
        $attempt = ExamAttempt::create([
            'user_id' => $user->id,
            'exam_id' => $exam->id,
            'started_at' => now(),
            'status' => 'in_progress',
        ]);

        AuditLogger::log('start_exam', 'Started exam: ' . $exam->title);

        return redirect()->route('student.exams.attempt', $exam);
    }

    public function show(Exam $exam)
    {
        $user = auth()->user();

        $attempt = ExamAttempt::where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->firstOrFail();

        if ($attempt->status === 'submitted') {
            return redirect()->route('student.history')->with('info', 'You have already submitted this exam.');
        }

        // Check if time is up
        $elapsed = now()->diffInMinutes($attempt->started_at);
        if ($elapsed >= $exam->time_limit) {
            return $this->autoSubmit($attempt, $exam);
        }

        $questions = $exam->questions()->inRandomOrder()->get()->map(function ($question) use ($attempt) {
            $question->load('options');
            $question->existing_answer = Answer::where('attempt_id', $attempt->id)
                ->where('question_id', $question->id)
                ->first();
            return $question;
        });

        $endTime = $attempt->started_at->addMinutes($exam->time_limit)->timestamp;

        return view('student.exam', compact('exam', 'attempt', 'questions', 'endTime'));
    }

    public function saveAnswer(Request $request, Exam $exam)
    {
        $user = auth()->user();

        $attempt = ExamAttempt::where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->where('status', 'in_progress')
            ->firstOrFail();

        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'selected_option_id' => 'nullable|exists:options,id',
            'answer_text' => 'nullable|string',
        ]);

        Answer::updateOrCreate(
            [
                'attempt_id' => $attempt->id,
                'question_id' => $request->question_id,
            ],
            [
                'selected_option_id' => $request->selected_option_id,
                'answer_text' => $request->answer_text,
            ]
        );

        return response()->json(['success' => true]);
    }

    public function review(Exam $exam)
    {
        $user = auth()->user();

        $attempt = ExamAttempt::where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->firstOrFail();

        if ($attempt->status === 'submitted') {
            return redirect()->route('student.history')->with('info', 'You have already submitted this exam.');
        }

        // Check if time is up
        $elapsed = now()->diffInMinutes($attempt->started_at);
        if ($elapsed >= $exam->time_limit) {
            return $this->autoSubmit($attempt, $exam);
        }

        $questions = $exam->questions()->with('options')->get()->map(function ($question) use ($attempt) {
            $question->existing_answer = Answer::where('attempt_id', $attempt->id)
                ->where('question_id', $question->id)
                ->first();
            return $question;
        });

        $endTime = $attempt->started_at->addMinutes($exam->time_limit)->timestamp;

        return view('student.review', compact('exam', 'attempt', 'questions', 'endTime'));
    }

    public function submit(Request $request, Exam $exam)
    {
        $user = auth()->user();

        $attempt = ExamAttempt::where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->where('status', 'in_progress')
            ->firstOrFail();

        $this->calculateAndSubmit($attempt, $exam);

        AuditLogger::log('submit_exam', 'Submitted exam: ' . $exam->title);

        return redirect()->route('student.history')->with('success', 'Exam submitted successfully!');
    }

    private function autoSubmit($attempt, $exam)
    {
        $this->calculateAndSubmit($attempt, $exam);
        AuditLogger::log('auto_submit_exam', 'Auto-submitted exam: ' . $exam->title);
        return redirect()->route('student.history')->with('info', 'Time is up! Exam has been auto-submitted.');
    }

    private function calculateAndSubmit($attempt, $exam)
    {
        $totalScore = 0;

        foreach ($exam->questions as $question) {
            $answer = Answer::where('attempt_id', $attempt->id)
                ->where('question_id', $question->id)
                ->first();

            if ($question->type === 'multiple_choice' && $answer && $answer->selected_option_id) {
                $correctOption = $question->options()->where('is_correct', true)->first();
                if ($correctOption && $answer->selected_option_id == $correctOption->id) {
                    $answer->update(['marks_awarded' => $question->marks]);
                    $totalScore += $question->marks;
                } else {
                    $answer->update(['marks_awarded' => 0]);
                }
            }
        }

        $attempt->update([
            'status' => 'submitted',
            'submitted_at' => now(),
            'total_score' => $totalScore,
        ]);
    }
}