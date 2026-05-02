@extends('layouts.app')

@section('title', 'Grade Attempt')

@section('content')
<div class="max-w-2xl space-y-4">

    <a href="{{ route('lecturer.grading.index') }}" class="text-xs text-slate-400 hover:text-slate-600">← Back to Grading</a>

    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-2">
        <h2 class="text-base font-semibold text-slate-700">{{ $attempt->exam->title }}</h2>
        <p class="text-xs text-slate-400">Student: <span class="text-slate-600">{{ $attempt->user->name }}</span></p>
        <p class="text-xs text-slate-400">Submitted: <span class="text-slate-600">{{ $attempt->submitted_at?->format('d M Y, h:i A') }}</span></p>
        <p class="text-xs text-slate-400">Total Score: <span class="text-slate-600 font-medium">{{ $attempt->total_score ?? '0' }}</span></p>
    </div>

    <form method="POST" action="{{ route('lecturer.grading.grade', $attempt) }}" class="space-y-4">
        @csrf

        @foreach($attempt->exam->questions as $index => $question)
        @php
            $answer = $attempt->answers->firstWhere('question_id', $question->id);
        @endphp

        <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-3">
            <div class="flex items-center gap-2">
                <span class="text-xs px-2 py-0.5 rounded-full
                    {{ $question->type === 'multiple_choice' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600' }}">
                    {{ $question->type === 'multiple_choice' ? 'Multiple Choice' : 'Open Text' }}
                </span>
                <span class="text-xs text-slate-400">Max: {{ $question->marks }} mark(s)</span>
            </div>

            <p class="text-sm text-slate-700">{{ $index + 1 }}. {{ $question->question_text }}</p>

            @if($question->type === 'multiple_choice')
                <div class="text-xs text-slate-500">
                    Student answered:
                    <span class="font-medium text-slate-700">
                        {{ $answer?->selectedOption?->option_text ?? 'No answer' }}
                    </span>
                </div>
                <div class="text-xs text-slate-500">
                    Correct answer:
                    <span class="font-medium text-emerald-600">
                        {{ $question->options->firstWhere('is_correct', true)?->option_text ?? '-' }}
                    </span>
                </div>
                <input type="hidden" name="grades[{{ $answer?->id }}]" value="{{ $answer?->marks_awarded ?? 0 }}" />
            @else
                <div class="bg-slate-50 rounded-lg p-3">
                    <p class="text-xs text-slate-400 mb-1">Student's answer:</p>
                    <p class="text-sm text-slate-700">{{ $answer?->answer_text ?? 'No answer provided.' }}</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        Marks Awarded (max: {{ $question->marks }})
                    </label>
                    <input type="number" name="grades[{{ $answer?->id }}]"
                           value="{{ $answer?->marks_awarded ?? 0 }}"
                           min="0" max="{{ $question->marks }}" step="0.5"
                           class="w-24 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300" />
                </div>
            @endif
        </div>
        @endforeach

        <button type="submit"
                class="px-4 py-2 bg-slate-800 text-white text-sm rounded-lg hover:bg-slate-700 transition-colors">
            Save Grades
        </button>
    </form>

    {{-- Release Result - separate form outside save grades form --}}
    <div class="pb-6">
        @if(!$attempt->is_released)
        <form method="POST" action="{{ route('lecturer.grading.release', $attempt) }}">
            @csrf
            <button type="submit"
                    onclick="return confirm('Release result to student?')"
                    class="px-4 py-2 bg-emerald-600 text-white text-sm rounded-lg hover:bg-emerald-500 transition-colors">
                Release Result
            </button>
        </form>
        @else
        <span class="px-4 py-2 bg-emerald-50 text-emerald-600 text-sm rounded-lg">✓ Result Released</span>
        @endif
    </div>

</div>
@endsection