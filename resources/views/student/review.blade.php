@extends('layouts.app')

@section('title', 'Review Answers')

@section('content')
<div class="max-w-2xl space-y-4">

    <!-- Header with Timer -->
    <div class="bg-white rounded-xl border border-slate-200 p-4 flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-slate-700">Review: {{ $exam->title }}</h2>
            <p class="text-xs text-slate-400">Check your answers before submitting.</p>
        </div>
        <div class="text-right">
            <p class="text-xs text-slate-400">Time Remaining</p>
            <p id="timer" class="text-lg font-semibold text-slate-700"></p>
        </div>
    </div>

    <!-- Questions Review -->
    <div class="space-y-3">
        @foreach($questions as $index => $question)
        @php $answer = $question->existing_answer; @endphp
        <div class="bg-white rounded-xl border p-5 space-y-2
            {{ $answer ? 'border-slate-200' : 'border-amber-200 bg-amber-50' }}">

            <div class="flex items-center justify-between">
                <span class="text-xs px-2 py-0.5 rounded-full
                    {{ $question->type === 'multiple_choice' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600' }}">
                    {{ $question->type === 'multiple_choice' ? 'Multiple Choice' : 'Open Text' }}
                </span>
                @if(!$answer)
                    <span class="text-xs text-amber-500">⚠ Not answered</span>
                @else
                    <span class="text-xs text-emerald-500">✓ Answered</span>
                @endif
            </div>

            <p class="text-sm text-slate-700">{{ $index + 1 }}. {{ $question->question_text }}</p>

            @if($answer)
                @if($question->type === 'multiple_choice')
                    <p class="text-xs text-slate-500">Your answer: <span class="font-medium text-slate-700">{{ $answer->selectedOption?->option_text ?? '-' }}</span></p>
                @else
                    <p class="text-xs text-slate-500">Your answer:</p>
                    <p class="text-sm text-slate-700 bg-slate-50 rounded-lg p-3">{{ $answer->answer_text ?? '-' }}</p>
                @endif
            @endif
        </div>
        @endforeach
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-between">
        <a href="{{ route('student.exams.attempt', $exam) }}"
           class="text-xs text-slate-400 hover:text-slate-600 transition-colors">← Back to Exam</a>

        <form method="POST" action="{{ route('student.exams.submit', $exam) }}">
            @csrf
            <button type="submit"
                    onclick="return confirm('Submit exam? This cannot be undone.')"
                    class="px-5 py-2 bg-emerald-600 text-white text-sm rounded-lg hover:bg-emerald-500 transition-colors">
                Submit Exam
            </button>
        </form>
    </div>
</div>

<script>
    const endTime = {{ $endTime }};
    let timeRemaining = Math.floor(endTime - (Date.now() / 1000));

    function updateTimer() {
        if (timeRemaining < 0) timeRemaining = 0;

        const hours = Math.floor(timeRemaining / 3600);
        const mins = Math.floor((timeRemaining % 3600) / 60);
        const secs = timeRemaining % 60;

        const display = `${String(hours).padStart(2, '0')}:${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
        document.getElementById('timer').textContent = display;

        if (timeRemaining <= 300) {
            document.getElementById('timer').classList.add('text-red-500');
        }

        if (timeRemaining <= 0) {
            document.querySelector('form').submit();
            return;
        }

        timeRemaining--;
        setTimeout(updateTimer, 1000);
    }

    updateTimer();
</script>
@endsection