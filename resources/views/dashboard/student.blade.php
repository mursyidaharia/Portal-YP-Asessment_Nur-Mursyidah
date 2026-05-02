@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Welcome -->
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-700">Welcome back, {{ auth()->user()->name }}! 👋</h2>
        <p class="text-sm text-slate-400 mt-1">Here's an overview of your exams.</p>
    </div>

    <!-- My Classes -->
    <div class="bg-white rounded-xl border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-sm font-semibold text-slate-700">My Classes</h2>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($myClasses as $class)
            <div class="px-6 py-4">
                <p class="text-sm font-medium text-slate-700">{{ $class->name }}</p>
                <p class="text-xs text-slate-400 mt-1">
                    Subjects: {{ $class->subjects->pluck('name')->join(', ') ?: 'None assigned' }}
                </p>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-sm text-slate-400">
                You are not assigned to any class yet.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Available Exams -->
    <div class="bg-white rounded-xl border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-sm font-semibold text-slate-700">Available Exams</h2>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($availableExams as $exam)
            <div class="px-6 py-4 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-700">{{ $exam->title }}</p>
                    <p class="text-xs text-slate-400">{{ $exam->subject->name }} · {{ $exam->time_limit }} mins</p>
                </div>
                <a href="{{ route('student.exams.index') }}"
                   class="text-xs px-3 py-1.5 bg-slate-800 text-white rounded-lg hover:bg-slate-700 transition-colors">
                    View
                </a>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-sm text-slate-400">
                No exams available at the moment.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Attempts -->
    <div class="bg-white rounded-xl border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-sm font-semibold text-slate-700">Recent Attempts</h2>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($myAttempts as $attempt)
            <div class="px-6 py-4 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-700">{{ $attempt->exam->title }}</p>
                    <p class="text-xs text-slate-400">{{ $attempt->submitted_at?->format('d M Y, h:i A') }}</p>
                </div>
                <div class="text-right">
                    @if($attempt->is_released)
                        <p class="text-sm font-semibold text-slate-700">{{ $attempt->total_score }}</p>
                        <p class="text-xs text-slate-400">Score</p>
                    @else
                        <span class="text-xs px-2 py-1 rounded-full bg-amber-50 text-amber-600">Pending</span>
                    @endif
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