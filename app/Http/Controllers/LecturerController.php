<?php

namespace App\Http\Controllers;

use App\Helpers\AuditLogger;
use App\Models\Classes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LecturerController extends Controller
{
    public function students(Request $request)
    {
        $query = User::where('role', 'student')->with('classes');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $sort = $request->sort ?? 'created_at';
        $direction = $request->direction ?? 'desc';

        if (in_array($sort, ['name', 'email', 'created_at'])) {
            $query->orderBy($sort, $direction);
        }

        $students = $query->get();
        return view('lecturer.students.index', compact('students'));
    }
}