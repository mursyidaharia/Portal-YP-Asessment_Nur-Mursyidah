@extends('layouts.app')

@section('title', 'Subjects')

@section('content')
<div class="space-y-4">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <h2 class="text-base font-semibold text-slate-700">All Subjects</h2>
        <a href="{{ route('lecturer.subjects.create') }}"
           class="text-xs px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-700 transition-colors">
            + New Subject
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50">
                    <th class="text-left px-6 py-3 text-xs font-medium text-slate-400 uppercase tracking-wide">Subject Name</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-slate-400 uppercase tracking-wide">Classes</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-slate-400 uppercase tracking-wide">Exams</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($subjects as $subject)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-slate-700">{{ $subject->name }}</td>
                    <td class="px-6 py-4 text-slate-500">
                        {{ $subject->classes->pluck('name')->join(', ') ?: '-' }}
                    </td>
                    <td class="px-6 py-4 text-slate-500">{{ $subject->exams_count }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('lecturer.subjects.edit', $subject) }}"
                               class="text-xs text-slate-400 hover:text-slate-600 transition-colors">Edit</a>
                            <form method="POST" action="{{ route('lecturer.subjects.destroy', $subject) }}"
                                  onsubmit="return confirm('Delete this subject?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-600 transition-colors">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-slate-400">No subjects found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection