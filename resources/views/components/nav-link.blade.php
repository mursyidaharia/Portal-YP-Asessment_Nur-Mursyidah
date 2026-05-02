@props(['active' => false])

<a {{ $attributes }}
   class="{{ $active
        ? 'flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium bg-slate-100 text-slate-800'
        : 'flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-500 hover:bg-slate-50 hover:text-slate-700 transition-colors'
   }}">
    {{ $slot }}
</a>