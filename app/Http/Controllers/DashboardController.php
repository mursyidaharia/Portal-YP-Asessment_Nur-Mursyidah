<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load('examAttempts');

        if ($user->isLecturer()) {
            $totalStudents = User::where('role', 'student')->count();
            $totalClasses = Classes::count();
            $totalSubjects = Subject::count();
            $totalExams = Exam::where('created_by', $user->id)->count();
            $recentAttempts = ExamAttempt::whereHas('exam', function ($q) use ($user) {
                $q->where('created_by', $user->id);
            })->with(['user', 'exam'])->latest()->take(5)->get();

            return view('dashboard.lecturer', compact(
                'totalStudents',
                'totalClasses',
                'totalSubjects',
                'totalExams',
                'recentAttempts'
            ));
        }

        // Student dashboard
        $myClasses = $user->classes()->with('subjects')->get();
        $availableExams = Exam::whereHas('subject.classes', function ($q) use ($user) {
            $q->whereHas('students', function ($q2) use ($user) {
                $q2->where('users.id', $user->id);
            });
        })->where('is_published', true)->get();

        $myAttempts = ExamAttempt::where('user_id', $user->id)
            ->where('status', 'submitted')
            ->with('exam')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.student', compact('myClasses', 'availableExams', 'myAttempts'));
    }
}