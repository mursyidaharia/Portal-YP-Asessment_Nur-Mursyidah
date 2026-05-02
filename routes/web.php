<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ExamAttemptController;
use App\Http\Controllers\GradingController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes (Breeze)
require __DIR__.'/auth.php';

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (Breeze default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Lecturer only routes
    Route::middleware(['auth', 'role:lecturer'])->prefix('lecturer')->name('lecturer.')->group(function () {

        // Class Management
        Route::resource('classes', ClassController::class);

        // Subject Management
        Route::resource('subjects', SubjectController::class);

        // Student Management
        Route::get('students', [LecturerController::class, 'students'])->name('students.index');

        // Exam Management
        Route::resource('exams', ExamController::class);
        Route::post('exams/{exam}/publish', [ExamController::class, 'publish'])->name('exams.publish');
        Route::post('exams/{exam}/unpublish', [ExamController::class, 'unpublish'])->name('exams.unpublish');

        // Question Management
        Route::get('exams/{exam}/questions', [QuestionController::class, 'index'])->name('exams.questions.index');
        Route::get('exams/{exam}/questions/create', [QuestionController::class, 'create'])->name('exams.questions.create');
        Route::post('exams/{exam}/questions', [QuestionController::class, 'store'])->name('exams.questions.store');
        Route::get('exams/{exam}/questions/{question}/edit', [QuestionController::class, 'edit'])->name('exams.questions.edit');
        Route::put('exams/{exam}/questions/{question}', [QuestionController::class, 'update'])->name('exams.questions.update');
        Route::delete('exams/{exam}/questions/{question}', [QuestionController::class, 'destroy'])->name('exams.questions.destroy');

        // Grading
        Route::get('grading', [GradingController::class, 'index'])->name('grading.index');
        Route::get('grading/{attempt}', [GradingController::class, 'show'])->name('grading.show');
        Route::post('grading/{attempt}', [GradingController::class, 'grade'])->name('grading.grade');
        Route::post('grading/{attempt}/release', [GradingController::class, 'release'])->name('grading.release');
        Route::post('grading/{exam}/bulk-release', [GradingController::class, 'bulkRelease'])->name('grading.bulk-release');
    });

    // Student only routes
    Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {

        // Available exams
        Route::get('exams', [StudentController::class, 'exams'])->name('exams.index');

        // Exam attempt
        Route::post('exams/{exam}/start', [ExamAttemptController::class, 'start'])->name('exams.start');
        Route::get('exams/{exam}/attempt', [ExamAttemptController::class, 'show'])->name('exams.attempt');
        Route::post('exams/{exam}/answer', [ExamAttemptController::class, 'saveAnswer'])->name('exams.answer');
        Route::get('exams/{exam}/review', [ExamAttemptController::class, 'review'])->name('exams.review');
        Route::post('exams/{exam}/submit', [ExamAttemptController::class, 'submit'])->name('exams.submit');

        // Exam history & results
        Route::get('history', [StudentController::class, 'history'])->name('history');
        Route::get('results/{attempt}', [StudentController::class, 'result'])->name('results.show');
    });
});