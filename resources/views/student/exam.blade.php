@extends('layouts.app')

@section('title', 'Exam')

@section('content')
<div class="max-w-2xl space-y-4">

    <!-- Header with Timer -->
    <div class="bg-white rounded-xl border border-slate-200 p-4 flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-slate-700">{{ $exam->title }}</h2>
            <p class="text-xs text-slate-400">{{ $exam->subject->name }}</p>
        </div>
        <div class="text-right">
            <p class="text-xs text-slate-400">Time Remaining</p>
            <p id="timer" class="text-lg font-semibold text-slate-700"></p>
        </div>
    </div>

    <!-- Questions -->
    <div class="space-y-4">
        @foreach($questions as $index => $question)
        <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-3" id="question-{{ $question->id }}">
            <div class="flex items-center gap-2">
                <span class="text-xs px-2 py-0.5 rounded-full
                    {{ $question->type === 'multiple_choice' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600' }}">
                    {{ $question->type === 'multiple_choice' ? 'Multiple Choice' : 'Open Text' }}
                </span>
                <span class="text-xs text-slate-400">{{ $question->marks }} mark(s)</span>
            </div>

            <p class="text-sm text-slate-700">{{ $index + 1 }}. {{ $question->question_text }}</p>

            @if($question->type === 'multiple_choice')
            <div class="space-y-2">
                @foreach($question->options as $option)
                <label class="flex items-center gap-3 p-3 rounded-lg border border-slate-100 hover:bg-slate-50 cursor-pointer transition-colors">
                    <input type="radio"
                           name="answer_{{ $question->id }}"
                           value="{{ $option->id }}"
                           {{ $question->existing_answer?->selected_option_id == $option->id ? 'checked' : '' }}
                           onchange="saveAnswer({{ $question->id }}, {{ $option->id }}, null)"
                           class="text-slate-600" />
                    <span class="text-sm text-slate-600">{{ $option->option_text }}</span>
                </label>
                @endforeach
            </div>
            @else
            <textarea
                rows="4"
                placeholder="Type your answer here..."
                onchange="saveAnswer({{ $question->id }}, null, this.value)"
                class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300 resize-none"
            >{{ $question->existing_answer?->answer_text }}</textarea>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Review & Submit -->
    <div class="flex justify-end">
        <a href="{{ route('student.exams.review', $exam) }}"
           class="px-5 py-2 bg-slate-800 text-white text-sm rounded-lg hover:bg-slate-700 transition-colors">
            Review & Submit →
        </a>
    </div>
</div>

<form id="autoSubmitForm" method="POST" action="{{ route('student.exams.submit', $exam) }}" style="display:none">
    @csrf
</form>

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
            autoSubmit();
            return;
        }

        timeRemaining--;
        setTimeout(updateTimer, 1000);
    }

    function autoSubmit() {
        document.getElementById('autoSubmitForm').submit();
    }

    function saveAnswer(questionId, optionId, answerText) {
        fetch('{{ route('student.exams.answer', $exam) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                question_id: questionId,
                selected_option_id: optionId,
                answer_text: answerText
            })
        });
    }

    // Prevent copy paste
    document.addEventListener('copy', e => e.preventDefault());
    document.addEventListener('cut', e => e.preventDefault());
    document.addEventListener('paste', e => e.preventDefault());
    document.addEventListener('contextmenu', e => e.preventDefault());

    updateTimer();
</script>
@endsection