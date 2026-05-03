<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Option;
use App\Models\Question;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        $lecturer = User::where('email', 'lecturer@expo.com')->first();
        $math = Subject::where('name', 'Mathematics')->first();
        $programming = Subject::where('name', 'Programming')->first();

        // Exam 1 — Published, MCQ only
        $exam1 = Exam::create([
            'title' => 'Mathematics Midterm',
            'subject_id' => $math->id,
            'created_by' => $lecturer->id,
            'time_limit' => 30,
            'is_published' => true,
        ]);

        $q1 = Question::create([
            'exam_id' => $exam1->id,
            'question_text' => 'What is 15 + 27?',
            'type' => 'multiple_choice',
            'marks' => 2,
        ]);
        Option::create(['question_id' => $q1->id, 'option_text' => '40', 'is_correct' => false]);
        Option::create(['question_id' => $q1->id, 'option_text' => '42', 'is_correct' => true]);
        Option::create(['question_id' => $q1->id, 'option_text' => '44', 'is_correct' => false]);
        Option::create(['question_id' => $q1->id, 'option_text' => '38', 'is_correct' => false]);

        $q2 = Question::create([
            'exam_id' => $exam1->id,
            'question_text' => 'What is the square root of 144?',
            'type' => 'multiple_choice',
            'marks' => 2,
        ]);
        Option::create(['question_id' => $q2->id, 'option_text' => '10', 'is_correct' => false]);
        Option::create(['question_id' => $q2->id, 'option_text' => '11', 'is_correct' => false]);
        Option::create(['question_id' => $q2->id, 'option_text' => '12', 'is_correct' => true]);
        Option::create(['question_id' => $q2->id, 'option_text' => '14', 'is_correct' => false]);

        $q3 = Question::create([
            'exam_id' => $exam1->id,
            'question_text' => 'Which of the following is a prime number?',
            'type' => 'multiple_choice',
            'marks' => 2,
        ]);
        Option::create(['question_id' => $q3->id, 'option_text' => '9', 'is_correct' => false]);
        Option::create(['question_id' => $q3->id, 'option_text' => '15', 'is_correct' => false]);
        Option::create(['question_id' => $q3->id, 'option_text' => '17', 'is_correct' => true]);
        Option::create(['question_id' => $q3->id, 'option_text' => '21', 'is_correct' => false]);

        // Exam 2 — Published, Mix MCQ + Open Text
        $exam2 = Exam::create([
            'title' => 'Programming Fundamentals',
            'subject_id' => $programming->id,
            'created_by' => $lecturer->id,
            'time_limit' => 45,
            'is_published' => true,
        ]);

        $q4 = Question::create([
            'exam_id' => $exam2->id,
            'question_text' => 'Which keyword is used to declare a variable in JavaScript?',
            'type' => 'multiple_choice',
            'marks' => 2,
        ]);
        Option::create(['question_id' => $q4->id, 'option_text' => 'var', 'is_correct' => false]);
        Option::create(['question_id' => $q4->id, 'option_text' => 'let', 'is_correct' => false]);
        Option::create(['question_id' => $q4->id, 'option_text' => 'dim', 'is_correct' => false]);
        Option::create(['question_id' => $q4->id, 'option_text' => 'var or let', 'is_correct' => true]);

        $q5 = Question::create([
            'exam_id' => $exam2->id,
            'question_text' => 'What does OOP stand for?',
            'type' => 'multiple_choice',
            'marks' => 2,
        ]);
        Option::create(['question_id' => $q5->id, 'option_text' => 'Object Oriented Programming', 'is_correct' => true]);
        Option::create(['question_id' => $q5->id, 'option_text' => 'Object Oriented Process', 'is_correct' => false]);
        Option::create(['question_id' => $q5->id, 'option_text' => 'Ordered Object Programming', 'is_correct' => false]);
        Option::create(['question_id' => $q5->id, 'option_text' => 'None of the above', 'is_correct' => false]);

        Question::create([
            'exam_id' => $exam2->id,
            'question_text' => 'Explain the difference between a compiled language and an interpreted language. Give one example for each.',
            'type' => 'open_text',
            'marks' => 5,
        ]);

        Question::create([
            'exam_id' => $exam2->id,
            'question_text' => 'What is recursion? Write a brief explanation and describe one use case.',
            'type' => 'open_text',
            'marks' => 5,
        ]);

        // Exam 3 — Scheduled (future)
        Exam::create([
            'title' => 'Mathematics Final Exam',
            'subject_id' => $math->id,
            'created_by' => $lecturer->id,
            'time_limit' => 60,
            'is_published' => false,
            'publish_at' => now()->addDays(2),
            'due_at' => now()->addDays(3),
        ]);

        // Exam 4 — Expired
        Exam::create([
            'title' => 'Programming Quiz 1',
            'subject_id' => $programming->id,
            'created_by' => $lecturer->id,
            'time_limit' => 15,
            'is_published' => true,
            'due_at' => now()->subDays(1),
        ]);
    }
}