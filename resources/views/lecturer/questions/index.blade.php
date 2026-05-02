@extends('layouts.app')

@section('title', 'Questions')

@section('content')
<div class="space-y-4">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('lecturer.exams.index') }}" class="text-xs text-slate-400 hover:text-slate-600">← Back to Exams</a>
            <h2 class="text-base font-semibold text-slate-700 mt-1">{{ $exam->title }} — Questions</h2>
        </div>
        <a href="{{ route('lecturer.exams.questions.create', $exam) }}"
           class="text-xs px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-700 transition-colors">
            + Add Question
        </a>
    </div>

    <!-- Questions List -->
    <div class="space-y-3">
        @forelse($questions as $index => $question)
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs px-2 py-0.5 rounded-full
                            {{ $question->type === 'multiple_choice' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600' }}">
                            {{ $question->type === 'multiple_choice' ? 'Multiple Choice' : 'Open Text' }}
                        </span>
                        <span class="text-xs text-slate-400">{{ $question->marks }} mark(s)</span>
                    </div>
                    <p class="text-sm text-slate-700">{{ $index + 1 }}. {{ $question->question_text }}</p>

                    @if($question->type === 'multiple_choice')
                    <div class="mt-3 space-y-1">
                        @foreach($question->options as $option)
                        <div class="flex items-center gap-2">
                            <span class="w-4 h-4 rounded-full border flex items-center justify-center
                                {{ $option->is_correct ? 'border-emerald-400 bg-emerald-50' : 'border-slate-200' }}">
                                @if($option->is_correct)
                                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                @endif
                            </span>
                            <span class="text-xs text-slate-600 {{ $option->is_correct ? 'font-medium text-emerald-700' : '' }}">
                                {{ $option->option_text }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('lecturer.exams.questions.edit', [$exam, $question]) }}"
                       class="text-xs text-slate-400 hover:text-slate-600 transition-colors">Edit</a>
                    <form method="POST" action="{{ route('lecturer.exams.questions.destroy', [$exam, $question]) }}"
                          onsubmit="return confirm('Delete this question?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-400 hover:text-red-600 transition-colors">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl border border-slate-200 px-6 py-12 text-center">
            <p class="text-slate-400 text-sm">No questions yet.</p>
            <a href="{{ route('lecturer.exams.questions.create', $exam) }}"
               class="inline-block mt-3 text-xs px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-700 transition-colors">
                + Add First Question
            </a>
        </div>
        @endforelse
    </div>
</div>
@endsection