<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - @yield('title', 'Portal')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 font-sans">

<div class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-white border-r border-slate-200 flex flex-col transition-all duration-300 ease-in-out">

        <!-- Logo -->
        <div class="h-16 flex items-center px-6 border-b border-slate-200">
            <span class="text-lg font-semibold text-slate-700 tracking-tight">📋 ExamPortal</span>
        </div>

        <!-- Nav Links -->
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            @if(auth()->user()->isLecturer())
                <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                    🏠 Dashboard
                </x-nav-link>
                <x-nav-link href="{{ route('lecturer.classes.index') }}" :active="request()->routeIs('lecturer.classes.*')">
                    🏫 Classes
                </x-nav-link>
                <x-nav-link href="{{ route('lecturer.subjects.index') }}" :active="request()->routeIs('lecturer.subjects.*')">
                    📚 Subjects
                </x-nav-link>
                <x-nav-link href="{{ route('lecturer.students.index') }}" :active="request()->routeIs('lecturer.students.*')">
                    👨‍🎓 Students
                </x-nav-link>
                <x-nav-link href="{{ route('lecturer.exams.index') }}" :active="request()->routeIs('lecturer.exams.*')">
                    📝 Exams
                </x-nav-link>
                <x-nav-link href="{{ route('lecturer.grading.index') }}" :active="request()->routeIs('lecturer.grading.*')">
                    ✏️ Grading
                </x-nav-link>
            @else
                <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                    🏠 Dashboard
                </x-nav-link>
                <x-nav-link href="{{ route('student.exams.index') }}" :active="request()->routeIs('student.exams.*')">
                    📝 My Exams
                </x-nav-link>
                <x-nav-link href="{{ route('student.history') }}" :active="request()->routeIs('student.history')">
                    📋 Exam History
                </x-nav-link>
            @endif
        </nav>

        <!-- User Info -->
        <div class="border-t border-slate-200 px-4 py-4">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-sm font-medium text-slate-600">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-slate-700 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400 capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left text-sm text-slate-500 hover:text-red-500 transition-colors">
                    → Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Top Bar -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center px-6 gap-4">
            <button onclick="toggleSidebar()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <h1 class="text-base font-medium text-slate-700">@yield('title', 'Dashboard')</h1>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-6">

            {{-- Success/Error messages --}}
            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-600 rounded-lg text-sm">
                    ✕ {{ session('error') }}
                </div>
            @endif

            @if(session('info'))
                <div class="mb-4 px-4 py-3 bg-blue-50 border border-blue-200 text-blue-600 rounded-lg text-sm">
                    ℹ {{ session('info') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('w-64');
        sidebar.classList.toggle('w-0');
        sidebar.classList.toggle('overflow-hidden');
    }
</script>

</body>
</html>