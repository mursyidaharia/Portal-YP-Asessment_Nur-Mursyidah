@extends('layouts.app')

@section('title', 'Exam Details')

@section('content')
<div class="max-w-2xl space-y-4">

    <a href="{{ route('lecturer.exams.index') }}" class="text-xs text-slate-400 hover:text-slate-600">← Back to Exams</a>

    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-base font-semibold text-slate-700">{{ $exam->title }}</h2>
            <span class="text-xs px-2 py-1 rounded-full
                {{ $exam->is_published ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                {{ $exam->is_published ? 'Published' : 'Draft' }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-xs text-slate-400">Subject</p>
                <p class="text-slate-700 mt-0.5">{{ $exam->subject->name }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Time Limit</p>
                <p class="text-slate-700 mt-0.5">{{ $exam->time_limit }} minutes</p>
            </div>
        </div>

        <div class="flex gap-2 pt-2">
            <a href="{{ route('lecturer.exams.questions.index', $exam) }}"
               class="text-xs px-3 py-1.5 bg-slate-800 text-white rounded-lg hover:bg-slate-700 transition-colors">
                Manage Questions
            </a>
            <a href="{{ route('lecturer.exams.edit', $exam) }}"
               class="text-xs px-3 py-1.5 border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition-colors">
                Edit
            </a>
        </div>
    </div>
</div>
@endsection