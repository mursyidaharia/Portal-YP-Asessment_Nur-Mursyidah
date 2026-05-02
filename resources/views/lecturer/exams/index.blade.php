@extends('layouts.app')

@section('title', 'Exams')

@section('content')
<div class="space-y-4">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <h2 class="text-base font-semibold text-slate-700">My Exams</h2>
        <a href="{{ route('lecturer.exams.create') }}"
           class="text-xs px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-700 transition-colors">
            + New Exam
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50">
                    <th class="text-left px-6 py-3 text-xs font-medium text-slate-400 uppercase tracking-wide">Title</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-slate-400 uppercase tracking-wide">Subject</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-slate-400 uppercase tracking-wide">Time Limit</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-slate-400 uppercase tracking-wide">Questions</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-slate-400 uppercase tracking-wide">Status</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($exams as $exam)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-slate-700">{{ $exam->title }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $exam->subject->name }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $exam->time_limit }} mins</td>
                    <td class="px-6 py-4 text-slate-500">{{ $exam->questions_count }}</td>
                    <td class="px-6 py-4">
                        <span class="text-xs px-2 py-1 rounded-full
                            {{ $exam->is_published ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                            {{ $exam->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('lecturer.exams.questions.index', $exam) }}"
                               class="text-xs text-slate-400 hover:text-slate-600 transition-colors">Questions</a>
                            <a href="{{ route('lecturer.exams.edit', $exam) }}"
                               class="text-xs text-slate-400 hover:text-slate-600 transition-colors">Edit</a>
                            @if($exam->is_published)
                                <form method="POST" action="{{ route('lecturer.exams.unpublish', $exam) }}">
                                    @csrf
                                    <button type="submit" class="text-xs text-amber-400 hover:text-amber-600 transition-colors">Unpublish</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('lecturer.exams.publish', $exam) }}">
                                    @csrf
                                    <button type="submit" class="text-xs text-emerald-400 hover:text-emerald-600 transition-colors">Publish</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('lecturer.exams.destroy', $exam) }}"
                                  onsubmit="return confirm('Delete this exam?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-600 transition-colors">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-slate-400">No exams found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection