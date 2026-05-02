@extends('layouts.app')

@section('title', 'Edit Question')

@section('content')
<div class="max-w-xl space-y-4">

    <a href="{{ route('lecturer.exams.questions.index', $exam) }}" class="text-xs text-slate-400 hover:text-slate-600">← Back to Questions</a>

    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-5">
        <h2 class="text-base font-semibold text-slate-700">Edit Question</h2>

        <form method="POST" action="{{ route('lecturer.exams.questions.update', [$exam, $question]) }}" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Question Type</label>
                <input type="text"
                       value="{{ $question->type === 'multiple_choice' ? 'Multiple Choice' : 'Open Text' }}"
                       class="w-full border border-slate-100 rounded-lg px-3 py-2 text-sm bg-slate-50 text-slate-400 cursor-not-allowed"
                       disabled />
                <p class="text-xs text-slate-400 mt-1">Question type cannot be changed after creation.</p>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Question</label>
                <textarea name="question_text" rows="3"
                          class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                          required>{{ old('question_text', $question->question_text) }}</textarea>
                @error('question_text')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Marks</label>
                <input type="number" name="marks" value="{{ old('marks', $question->marks) }}" min="1"
                       class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                       required />
                @error('marks')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            @if($question->type === 'multiple_choice')
            <div class="space-y-3">
                <label class="block text-xs font-medium text-slate-600">Answer Options</label>
                <p class="text-xs text-slate-400">Select the radio button next to the correct answer.</p>

                <div id="optionsList" class="space-y-2">
                    @foreach($question->options as $i => $option)
                    <div class="flex items-center gap-2">
                        <input type="radio" name="correct_option" value="{{ $i }}"
                               {{ $option->is_correct ? 'checked' : '' }} required />
                        <input type="text" name="options[]" value="{{ old('options.'.$i, $option->option_text) }}"
                               class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                               required />
                    </div>
                    @endforeach
                </div>

                <button type="button" onclick="addOption()"
                        class="text-xs text-slate-500 hover:text-slate-700 transition-colors">
                    + Add Option
                </button>
            </div>
            @endif

            <div class="pt-2">
                <button type="submit"
                        class="px-4 py-2 bg-slate-800 text-white text-sm rounded-lg hover:bg-slate-700 transition-colors">
                    Update Question
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let optionCount = {{ $question->options->count() }};

    function addOption() {
        const list = document.getElementById('optionsList');
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2';
        div.innerHTML = `
            <input type="radio" name="correct_option" value="${optionCount}" />
            <input type="text" name="options[]"
                   class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                   placeholder="Option ${optionCount + 1}" />
            <button type="button" onclick="this.parentElement.remove()"
                    class="text-xs text-red-400 hover:text-red-600">✕</button>
        `;
        list.appendChild(div);
        optionCount++;
    }
</script>
@endsection