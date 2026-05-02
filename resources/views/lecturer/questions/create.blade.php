@extends('layouts.app')

@section('title', 'Add Question')

@section('content')
<div class="max-w-xl space-y-4">

    <a href="{{ route('lecturer.exams.questions.index', $exam) }}" class="text-xs text-slate-400 hover:text-slate-600">← Back to Questions</a>

    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-5">
        <h2 class="text-base font-semibold text-slate-700">Add New Question</h2>

        <form method="POST" action="{{ route('lecturer.exams.questions.store', $exam) }}" class="space-y-4" id="questionForm">
            @csrf

            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Question Type</label>
                <select name="type" id="questionType"
                        class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                        onchange="toggleOptions(this.value)" required>
                    <option value="">Select type...</option>
                    <option value="multiple_choice" {{ old('type') == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                    <option value="open_text" {{ old('type') == 'open_text' ? 'selected' : '' }}>Open Text</option>
                </select>
                @error('type')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Question</label>
                <textarea name="question_text" rows="3"
                          class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                          placeholder="Enter your question here..." required>{{ old('question_text') }}</textarea>
                @error('question_text')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Marks</label>
                <input type="number" name="marks" value="{{ old('marks', 1) }}" min="1"
                       class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                       required />
                @error('marks')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Multiple Choice Options -->
            <div id="optionsSection" class="space-y-3 hidden">
                <label class="block text-xs font-medium text-slate-600">Answer Options</label>
                <p class="text-xs text-slate-400">Select the radio button next to the correct answer.</p>

                <div id="optionsList" class="space-y-2">
                    @if(old('options'))
                        @foreach(old('options') as $i => $opt)
                        <div class="flex items-center gap-2">
                            <input type="radio" name="correct_option" value="{{ $i }}"
                                   {{ old('correct_option') == $i ? 'checked' : '' }} required />
                            <input type="text" name="options[]" value="{{ $opt }}"
                                   class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                                   placeholder="Option {{ $i + 1 }}" required />
                        </div>
                        @endforeach
                    @else
                        <div class="flex items-center gap-2">
                            <input type="radio" name="correct_option" value="0" />
                            <input type="text" name="options[]"
                                   class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                                   placeholder="Option 1" />
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="radio" name="correct_option" value="1" />
                            <input type="text" name="options[]"
                                   class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                                   placeholder="Option 2" />
                        </div>
                    @endif
                </div>

                <button type="button" onclick="addOption()"
                        class="text-xs text-slate-500 hover:text-slate-700 transition-colors">
                    + Add Option
                </button>
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="px-4 py-2 bg-slate-800 text-white text-sm rounded-lg hover:bg-slate-700 transition-colors">
                    Save Question
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let optionCount = {{ old('options') ? count(old('options')) : 2 }};

    function toggleOptions(type) {
        const section = document.getElementById('optionsSection');
        if (type === 'multiple_choice') {
            section.classList.remove('hidden');
        } else {
            section.classList.add('hidden');
        }
    }

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

    // Restore type on page load if validation failed
    const savedType = "{{ old('type') }}";
    if (savedType) toggleOptions(savedType);
</script>
@endsection