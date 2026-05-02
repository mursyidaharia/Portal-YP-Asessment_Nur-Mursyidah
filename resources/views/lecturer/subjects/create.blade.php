@extends('layouts.app')

@section('title', 'Create Subject')

@section('content')
<div class="max-w-md space-y-4">

    <a href="{{ route('lecturer.subjects.index') }}" class="text-xs text-slate-400 hover:text-slate-600">← Back to Subjects</a>

    <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-5">
        <h2 class="text-base font-semibold text-slate-700">Create New Subject</h2>

        <form method="POST" action="{{ route('lecturer.subjects.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Subject Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                       placeholder="e.g. Mathematics" required />
                @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="px-4 py-2 bg-slate-800 text-white text-sm rounded-lg hover:bg-slate-700 transition-colors">
                    Create Subject
                </button>
            </div>
        </form>
    </div>
</div>
@endsection