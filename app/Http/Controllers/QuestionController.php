<?php

namespace App\Http\Controllers;

use App\Helpers\AuditLogger;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Exam $exam)
    {
        $questions = $exam->questions()->with('options')->get();
        return view('lecturer.questions.index', compact('exam', 'questions'));
    }

    public function create(Exam $exam)
    {
        return view('lecturer.questions.create', compact('exam'));
    }

    public function store(Request $request, Exam $exam)
    {
        $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:multiple_choice,open_text',
            'marks' => 'required|integer|min:1',
            'options' => 'required_if:type,multiple_choice|nullable|array|min:2',
            'options.*' => 'required_if:type,multiple_choice|nullable|string',
            'correct_option' => 'required_if:type,multiple_choice|nullable|integer',
        ]);

        $question = Question::create([
            'exam_id' => $exam->id,
            'question_text' => $request->question_text,
            'type' => $request->type,
            'marks' => $request->marks,
        ]);

        if ($request->type === 'multiple_choice') {
            foreach ($request->options as $index => $optionText) {
                $question->options()->create([
                    'option_text' => $optionText,
                    'is_correct' => $index == $request->correct_option,
                ]);
            }
        }

        AuditLogger::log('create_question', 'Created question for exam: ' . $exam->title);

        return redirect()->route('lecturer.exams.questions.index', $exam)->with('success', 'Question added successfully!');
    }

    public function edit(Exam $exam, Question $question)
    {
        $question->load('options');
        return view('lecturer.questions.edit', compact('exam', 'question'));
    }

    public function update(Request $request, Exam $exam, Question $question)
    {
        $request->validate([
            'question_text' => 'required|string',
            'marks' => 'required|integer|min:1',
            'options' => 'required_if:type,multiple_choice|array|min:2',
            'options.*' => 'required_if:type,multiple_choice|string',
            'correct_option' => 'required_if:type,multiple_choice|integer',
        ]);

        $question->update([
            'question_text' => $request->question_text,
            'marks' => $request->marks,
        ]);

        if ($question->type === 'multiple_choice' && $request->options) {
            $question->options()->delete();
            foreach ($request->options as $index => $optionText) {
                $question->options()->create([
                    'option_text' => $optionText,
                    'is_correct' => $index == $request->correct_option,
                ]);
            }
        }

        AuditLogger::log('update_question', 'Updated question for exam: ' . $exam->title);

        return redirect()->route('lecturer.exams.questions.index', $exam)->with('success', 'Question updated successfully!');
    }

    public function destroy(Exam $exam, Question $question)
    {
        AuditLogger::log('delete_question', 'Deleted question for exam: ' . $exam->title);
        $question->delete();
        return redirect()->route('lecturer.exams.questions.index', $exam)->with('success', 'Question deleted successfully!');
    }
}