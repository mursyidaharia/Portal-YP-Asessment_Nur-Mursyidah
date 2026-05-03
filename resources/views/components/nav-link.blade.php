@props(['active' => false])

<a {{ $attributes }}
   class="{{ $active
        ? 'flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-semibold bg-indigo-600 text-white'
        : 'flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-200 hover:bg-slate-700 hover:text-white transition-colors'
   }}">
    {{ $slot }}
</a>