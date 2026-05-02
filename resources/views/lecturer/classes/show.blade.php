@extends('layouts.app')

@section('title', 'Class Details')

@section('content')
<div class="max-w-2xl space-y-4">

    <a href="{{ route('lecturer.classes.index') }}" class="text-xs text-slate-400 hover:text-slate-600">← Back to Classes</a>

    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-5">
        <div class="flex items-center justify-between">
            <h2 class="text-base font-semibold text-slate-700">{{ $class->name }}</h2>
            <a href="{{ route('lecturer.classes.edit', $class) }}"
               class="text-xs px-3 py-1.5 border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50 transition-colors">
                Edit
            </a>
        </div>

        <!-- Subjects -->
        <div>
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-2">Subjects</p>
            @forelse($class->subjects as $subject)
                <span class="inline-block text-xs px-2 py-1 bg-slate-100 text-slate-600 rounded-md mr-1 mb-1">{{ $subject->name }}</span>
            @empty
                <p class="text-sm text-slate-400">No subjects assigned.</p>
            @endforelse
        </div>

        <!-- Students -->
        <div>
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-2">Students ({{ $class->students->count() }})</p>
            <div class="divide-y divide-slate-100 border border-slate-100 rounded-lg overflow-hidden">
                @forelse($class->students as $student)
                <div class="px-4 py-3 flex items-center gap-3">
                    <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-xs font-medium text-slate-600">
                        {{ strtoupper(substr($student->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm text-slate-700">{{ $student->name }}</p>
                        <p class="text-xs text-slate-400">{{ $student->email }}</p>
                    </div>
                </div>
                @empty
                <div class="px-4 py-6 text-center text-sm text-slate-400">No students assigned.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection