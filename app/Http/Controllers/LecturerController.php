<?php

namespace App\Http\Controllers;

use App\Helpers\AuditLogger;
use App\Models\Classes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LecturerController extends Controller
{
    public function students()
    {
        $students = User::where('role', 'student')->with('classes')->latest()->get();
        return view('lecturer.students.index', compact('students'));
    }
}