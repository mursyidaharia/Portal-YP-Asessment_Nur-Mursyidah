@extends('layouts.app')

@section('title', 'Exam Result')

@section('content')
<div class="max-w-2xl space-y-4">

    <a href="{{ route('student.history') }}" class="text-xs text-slate-400 hover:text-slate-600">← Back to History</a>

    <!-- Score Card -->
    <div class="bg-white rounded-xl border border-slate-200 p-6 text-center space-y-2">
        <h2 class="text-base font-semibold text-slate-700">{{ $attempt->exam->title }}</h2>
        <p class="text-xs text-slate-400">{{ $attempt->exam->subject->name }}</p>
        <div class="py-4">
            <p class="text-5xl font-bold text-slate-800">{{ $attempt->total_score }}</p>
            <p class="text-xs text-slate-400 mt-1">
                out of {{ $attempt->exam->questions->sum('marks') }} marks
            </p>
        </div>
        <p class="text-xs text-slate-400">Submitted: {{ $attempt->submitted_at?->format('d M Y, h:i A') }}</p>
    </div>

    <!-- Answer Breakdown -->
    <div class="space-y-3">
        <h3 class="text-sm font-semibold text-slate-700">Answer Breakdown</h3>
        @foreach($attempt->exam->questions as $index => $question)
        @php $answer = $attempt->answers->firstWhere('question_id', $question->id); @endphp
        <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-xs px-2 py-0.5 rounded-full
                    {{ $question->type === 'multiple_choice' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600' }}">
                    {{ $question->type === 'multiple_choice' ? 'Multiple Choice' : 'Open Text' }}
                </span>
                <span class="text-xs font-medium
                    {{ ($answer?->marks_awarded ?? 0) > 0 ? 'text-emerald-600' : 'text-slate-400' }}">
                    {{ $answer?->marks_awarded ?? 0 }} / {{ $question->marks }} marks
                </span>
            </div>

            <p class="text-sm text-slate-700">{{ $index + 1 }}. {{ $question->question_text }}</p>

            @if($question->type === 'multiple_choice')
                <p class="text-xs text-slate-500">Your answer:
                    <span class="font-medium {{ ($answer?->marks_awarded ?? 0) > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                        {{ $answer?->selectedOption?->option_text ?? 'No answer' }}
                    </span>
                </p>
                <p class="text-xs text-slate-500">Correct answer:
                    <span class="font-medium text-emerald-600">
                        {{ $question->options->firstWhere('is_correct', true)?->option_text }}
                    </span>
                </p>
            @else
                <div class="bg-slate-50 rounded-lg p-3">
                    <p class="text-xs text-slate-400 mb-1">Your answer:</p>
                    <p class="text-sm text-slate-700">{{ $answer?->answer_text ?? 'No answer provided.' }}</p>
                </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endsection