@extends('layouts.app')

@section('title', 'My Exams')

@section('content')
<div class="space-y-4">

    <h2 class="text-base font-semibold text-slate-700">Available Exams</h2>

    <!-- Search & Filter -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <form method="GET" action="{{ route('student.exams.index') }}" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search exams..."
                class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300" />
            <select name="status" class="border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300">
                <option value="">All</option>
                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-sm rounded-lg hover:bg-slate-700 transition-colors">Search</button>
            @if(request('search') || request('status'))
                <a href="{{ route('student.exams.index') }}" class="px-4 py-2 border border-slate-200 text-slate-600 text-sm rounded-lg hover:bg-slate-50 transition-colors">Clear</a>
            @endif
        </form>
    </div>

    <div class="space-y-3">
        @forelse($exams as $exam)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-slate-700">{{ $exam->title }}</h3>
                    <p class="text-xs text-slate-400 mt-1">{{ $exam->subject->name }} · {{ $exam->time_limit }} mins</p>
                    @if($exam->due_at)
                    <p class="text-xs mt-1 {{ $exam->isExpired() ? 'text-red-400' : 'text-slate-400' }}">
                        Due: {{ $exam->due_at->format('d M Y, h:i A') }}
                    </p>
                    @endif
                </div>
                <div>
                    @if($exam->isExpired())
                        <span class="text-xs px-3 py-1.5 bg-red-50 text-red-400 rounded-lg">Expired</span>
                    @elseif($exam->attempt)
                        @if($exam->attempt->status === 'in_progress')
                            <a href="{{ route('student.exams.attempt', $exam) }}"
                               class="text-xs px-3 py-1.5 bg-amber-500 text-white rounded-lg hover:bg-amber-400 transition-colors">
                                Continue
                            </a>
                        @else
                            <span class="text-xs px-3 py-1.5 bg-slate-100 text-slate-400 rounded-lg">Completed</span>
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
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm px-6 py-12 text-center">
            <p class="text-slate-400 text-sm">No exams available at the moment.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection