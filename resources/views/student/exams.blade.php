@extends('layouts.app')

@section('title', 'My Exams')

@section('content')
<div class="space-y-4">

    <h2 class="text-base font-semibold text-slate-700">Available Exams</h2>

    <div class="space-y-3">
        @forelse($exams as $exam)
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-slate-700">{{ $exam->title }}</h3>
                    <p class="text-xs text-slate-400 mt-1">{{ $exam->subject->name }} · {{ $exam->time_limit }} mins</p>
                </div>
                <div>
                    @if($exam->attempt)
                        @if($exam->attempt->status === 'in_progress')
                            <a href="{{ route('student.exams.attempt', $exam) }}"
                               class="text-xs px-3 py-1.5 bg-amber-500 text-white rounded-lg hover:bg-amber-400 transition-colors">
                                Continue
                            </a>
                        @else
                            <span class="text-xs px-3 py-1.5 bg-slate-100 text-slate-400 rounded-lg">
                                Completed
                            </span>
                        @endif
                    @else
                        <form method="POST" action="{{ route('student.exams.start', $exam) }}">
                            @csrf
                            <button type="submit"
                                    onclick="return confirm('Start exam? Timer will begin immediately.')"
                                    class="text-xs px-3 py-1.5 bg-slate-800 text-white rounded-lg hover:bg-slate-700 transition-colors">
                                Start Exam
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl border border-slate-200 px-6 py-12 text-center">
            <p class="text-slate-400 text-sm">No exams available at the moment.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection