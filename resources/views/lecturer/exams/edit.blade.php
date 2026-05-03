@extends('layouts.app')

@section('title', 'Edit Exam')

@section('content')
<div class="max-w-md space-y-4">

    <a href="{{ route('lecturer.exams.index') }}" class="text-xs text-slate-400 hover:text-slate-600">← Back to Exams</a>

    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-5">
        <h2 class="text-base font-semibold text-slate-700">Edit Exam</h2>

        <form method="POST" action="{{ route('lecturer.exams.update', $exam) }}" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Exam Title</label>
                <input type="text" name="title" value="{{ old('title', $exam->title) }}"
                       class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                       required />
                @error('title')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Subject</label>
                <select name="subject_id"
                        class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                        required>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ $exam->subject_id == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
                @error('subject_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Time Limit (minutes)</label>
                <input type="number" name="time_limit" value="{{ old('time_limit', $exam->time_limit) }}"
                       class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                       min="1" required />
                @error('time_limit')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Scheduled Publish -->
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Scheduled Publish Date & Time <span class="text-slate-400">(optional)</span></label>
                <input type="datetime-local" name="publish_at"
                    value="{{ old('publish_at', $exam->publish_at?->format('Y-m-d\TH:i')) }}"
                    class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300" />
                <p class="text-xs text-slate-400 mt-1">Leave empty to publish manually.</p>
                @error('publish_at')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Due Date -->
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Due Date & Time <span class="text-slate-400">(optional)</span></label>
                <input type="datetime-local" name="due_at"
                    value="{{ old('due_at', $exam->due_at?->format('Y-m-d\TH:i')) }}"
                    class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300" />
                <p class="text-xs text-slate-400 mt-1">Students cannot attempt after this date.</p>
                @error('due_at')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="px-4 py-2 bg-slate-800 text-white text-sm rounded-lg hover:bg-slate-700 transition-colors">
                    Update Exam
                </button>
            </div>
        </form>
    </div>
</div>
@endsection