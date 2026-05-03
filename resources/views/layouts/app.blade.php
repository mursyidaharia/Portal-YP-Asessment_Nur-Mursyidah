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
    <aside id="sidebar" class="w-64 bg-slate-900 flex flex-col transition-all duration-300 ease-in-out overflow-hidden shrink-0">

        <!-- Logo -->
        <div class="h-16 flex items-center px-6 border-b border-slate-700">
            <span class="text-xl font-bold text-white tracking-tight">Ex<span class="text-indigo-400">Po</span></span>
        </div>

        <!-- Nav Links -->
        <nav class="flex-1 px-3 py-5 space-y-0.5 overflow-y-auto">
            @if(auth()->user()->isLecturer())
                <p class="text-xs text-slate-500 uppercase tracking-widest px-3 pb-2">Main</p>
                <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                    Dashboard
                </x-nav-link>

                <p class="text-xs text-slate-500 uppercase tracking-widest px-3 pb-2 pt-4">Management</p>
                <x-nav-link href="{{ route('lecturer.classes.index') }}" :active="request()->routeIs('lecturer.classes.*')">
                    Classes
                </x-nav-link>
                <x-nav-link href="{{ route('lecturer.subjects.index') }}" :active="request()->routeIs('lecturer.subjects.*')">
                    Subjects
                </x-nav-link>
                <x-nav-link href="{{ route('lecturer.students.index') }}" :active="request()->routeIs('lecturer.students.*')">
                    Students
                </x-nav-link>

                <p class="text-xs text-slate-500 uppercase tracking-widest px-3 pb-2 pt-4">Exams</p>
                <x-nav-link href="{{ route('lecturer.exams.index') }}" :active="request()->routeIs('lecturer.exams.*')">
                    Exams
                </x-nav-link>
                <x-nav-link href="{{ route('lecturer.grading.index') }}" :active="request()->routeIs('lecturer.grading.*')">
                    Grading
                </x-nav-link>
            @else
                <p class="text-xs text-slate-500 uppercase tracking-widest px-3 pb-2">Main</p>
                <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                    Dashboard
                </x-nav-link>

                <p class="text-xs text-slate-500 uppercase tracking-widest px-3 pb-2 pt-4">Exams</p>
                <x-nav-link href="{{ route('student.exams.index') }}" :active="request()->routeIs('student.exams.*')">
                    My Exams
                </x-nav-link>
                <x-nav-link href="{{ route('student.history') }}" :active="request()->routeIs('student.history')">
                    Exam History
                </x-nav-link>
            @endif
        </nav>

        <!-- User Info -->
        <div class="border-t border-slate-700 px-4 py-4">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-sm font-semibold text-white">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400 capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left text-xs text-slate-400 hover:text-red-400 transition-colors">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Top Bar -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center px-6 gap-4 shrink-0">
            <button onclick="toggleSidebar()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <h1 class="text-base font-medium text-slate-700">@yield('title', 'Dashboard')</h1>

            <!-- Right side -->
            <div class="ml-auto flex items-center gap-3">
                <span class="text-xs px-2.5 py-1 rounded-full font-medium
                    {{ auth()->user()->isLecturer() ? 'bg-indigo-50 text-indigo-600' : 'bg-emerald-50 text-emerald-600' }}">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-6">

            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm flex items-center gap-2">
                    <span class="font-medium">✓</span> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-600 rounded-lg text-sm flex items-center gap-2">
                    <span class="font-medium">✕</span> {{ session('error') }}
                </div>
            @endif

            @if(session('info'))
                <div class="mb-4 px-4 py-3 bg-blue-50 border border-blue-200 text-blue-700 rounded-lg text-sm flex items-center gap-2">
                    <span class="font-medium">i</span> {{ session('info') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script>
    let sidebarOpen = true;

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        if (sidebarOpen) {
            sidebar.style.width = '0px';
        } else {
            sidebar.style.width = '256px';
        }
        sidebarOpen = !sidebarOpen;
    }
</script>

</body>
</html>