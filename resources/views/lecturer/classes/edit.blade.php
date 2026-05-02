@extends('layouts.app')

@section('title', 'Edit Class')

@section('content')
<div class="max-w-xl space-y-4">

    <a href="{{ route('lecturer.classes.index') }}" class="text-xs text-slate-400 hover:text-slate-600">← Back to Classes</a>

    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-5">
        <h2 class="text-base font-semibold text-slate-700">Edit Class</h2>

        <form method="POST" action="{{ route('lecturer.classes.update', $class) }}" class="space-y-4">
            @csrf @method('PUT')

            <!-- Class Name -->
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Class Name</label>
                <input type="text" name="name" value="{{ old('name', $class->name) }}"
                       class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                       required />
                @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Subjects -->
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-2">Assign Subjects</label>
                @forelse($subjects as $subject)
                <label class="flex items-center gap-2 mb-1 cursor-pointer">
                    <input type="checkbox" name="subject_ids[]" value="{{ $subject->id }}"
                           {{ $class->subjects->contains($subject->id) ? 'checked' : '' }}
                           class="rounded border-slate-300" />
                    <span class="text-sm text-slate-600">{{ $subject->name }}</span>
                </label>
                @empty
                <p class="text-xs text-slate-400">No subjects available.</p>
                @endforelse
            </div>

            <!-- Students -->
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-2">Assign Students</label>
                @forelse($students as $student)
                <label class="flex items-center gap-2 mb-1 cursor-pointer">
                    <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                           {{ $class->students->contains($student->id) ? 'checked' : '' }}
                           class="rounded border-slate-300" />
                    <span class="text-sm text-slate-600">{{ $student->name }}</span>
                </label>
                @empty
                <p class="text-xs text-slate-400">No students registered yet.</p>
                @endforelse
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="px-4 py-2 bg-slate-800 text-white text-sm rounded-lg hover:bg-slate-700 transition-colors">
                    Update Class
                </button>
            </div>
        </form>
    </div>
</div>
@endsection