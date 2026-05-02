@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <p class="text-xs text-slate-400 uppercase tracking-wide">Total Students</p>
            <p class="text-3xl font-semibold text-slate-700 mt-1">{{ $totalStudents }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <p class="text-xs text-slate-400 uppercase tracking-wide">Total Classes</p>
            <p class="text-3xl font-semibold text-slate-700 mt-1">{{ $totalClasses }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <p class="text-xs text-slate-400 uppercase tracking-wide">Total Subjects</p>
            <p class="text-3xl font-semibold text-slate-700 mt-1">{{ $totalSubjects }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <p class="text-xs text-slate-400 uppercase tracking-wide">My Exams</p>
            <p class="text-3xl font-semibold text-slate-700 mt-1">{{ $totalExams }}</p>
        </div>
    </div>

    <!-- Recent Attempts -->
    <div class="bg-white rounded-xl border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-sm font-semibold text-slate-700">Recent Exam Attempts</h2>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($recentAttempts as $attempt)
            <div class="px-6 py-4 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-700">{{ $attempt->user->name }}</p>
                    <p class="text-xs text-slate-400">{{ $attempt->exam->title }}</p>
                </div>
                <div class="text-right">
                    <span class="text-xs px-2 py-1 rounded-full
                        {{ $attempt->status === 'submitted' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                        {{ ucfirst($attempt->status) }}
                    </span>
                    <p class="text-xs text-slate-400 mt-1">{{ $attempt->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-sm text-slate-400">
                No exam attempts yet.
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection