@extends('layouts.app')

@section('title', 'Grading')

@section('content')
<div class="space-y-4">

    <h2 class="text-base font-semibold text-slate-700">Grading</h2>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50">
                    <th class="text-left px-6 py-3 text-xs font-medium text-slate-400 uppercase tracking-wide">Exam</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-slate-400 uppercase tracking-wide">Subject</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-slate-400 uppercase tracking-wide">Attempts</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($exams as $exam)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-slate-700">{{ $exam->title }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $exam->subject->name }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $exam->attempts_count }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('lecturer.grading.index') }}?exam={{ $exam->id }}"
                            class="text-xs text-slate-400 hover:text-slate-600 transition-colors">View Attempts</a>
                            @php
                                $allMcq = $exam->questions->every(fn($q) => $q->type === 'multiple_choice');
                                $hasAttempts = $exam->attempts_count > 0;
                            @endphp
                            @if($allMcq && $hasAttempts)
                            <form method="POST" action="{{ route('lecturer.grading.bulk-release', $exam) }}">
                                @csrf
                                <button type="submit"
                                        onclick="return confirm('Release all results for this exam?')"
                                        class="text-xs text-emerald-500 hover:text-emerald-700 transition-colors">
                                    Release All
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-slate-400">No exams with attempts yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(request('exam'))
    @php $selectedExam = $exams->find(request('exam')); @endphp
    @if($selectedExam)
    <div class="space-y-3">
        <h3 class="text-sm font-semibold text-slate-700">Attempts for: {{ $selectedExam->title }}</h3>
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50">
                        <th class="text-left px-6 py-3 text-xs font-medium text-slate-400 uppercase tracking-wide">Student</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-slate-400 uppercase tracking-wide">Submitted</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-slate-400 uppercase tracking-wide">Score</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-slate-400 uppercase tracking-wide">Released</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($selectedExam->attempts()->with('user')->where('status', 'submitted')->get() as $attempt)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-700">{{ $attempt->user->name }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $attempt->submitted_at?->format('d M Y, h:i A') }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $attempt->total_score ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs px-2 py-1 rounded-full
                                {{ $attempt->is_released ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                {{ $attempt->is_released ? 'Released' : 'Pending' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('lecturer.grading.show', $attempt) }}"
                               class="text-xs text-slate-400 hover:text-slate-600 transition-colors">Grade</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400">No attempts yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
    @endif

</div>
@endsection