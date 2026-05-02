@extends('layouts.app')

@section('title', 'Exam History')

@section('content')
<div class="space-y-4">

    <h2 class="text-base font-semibold text-slate-700">Exam History</h2>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50">
                    <th class="text-left px-6 py-3 text-xs font-medium text-slate-400 uppercase tracking-wide">Exam</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-slate-400 uppercase tracking-wide">Subject</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-slate-400 uppercase tracking-wide">Submitted</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-slate-400 uppercase tracking-wide">Score</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($attempts as $attempt)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-slate-700">{{ $attempt->exam->title }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $attempt->exam->subject->name }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $attempt->submitted_at?->format('d M Y, h:i A') }}</td>
                    <td class="px-6 py-4">
                        @if($attempt->is_released)
                            <span class="font-medium text-slate-700">{{ $attempt->total_score }}</span>
                        @else
                            <span class="text-xs px-2 py-1 rounded-full bg-amber-50 text-amber-600">Pending</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($attempt->is_released)
                            <a href="{{ route('student.results.show', $attempt) }}"
                               class="text-xs text-slate-400 hover:text-slate-600 transition-colors">View Result</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-400">No exam history yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection