<article class="flex items-center gap-4 rounded-[1.35rem] border border-slate-200/85 bg-white p-4 shadow-[0_16px_36px_-30px_rgba(15,23,42,0.18)] transition duration-300 hover:shadow-[0_22px_40px_-30px_rgba(15,23,42,0.22)] sm:rounded-[1.5rem] sm:p-5 md:hover:-translate-y-1">
    <div class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-[0.95rem] bg-emerald-50 text-emerald-700 sm:h-14 sm:w-14 sm:rounded-[1rem]">
        @if ($category['icon'] === 'briefcase')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-8 w-8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9 6V5a3 3 0 013-3h0a3 3 0 013 3v1m5 4H4m16 0v8a2 2 0 01-2 2H6a2 2 0 01-2-2v-8m16 0V8a2 2 0 00-2-2H6a2 2 0 00-2 2v2" />
            </svg>
        @else
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-8 w-8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M3 21h18M5 21V8l7-4 7 4v13M9 9h.01M9 13h.01M9 17h.01M15 9h.01M15 13h.01M15 17h.01" />
            </svg>
        @endif
    </div>

    <div>
        <h3 class="text-base font-bold text-slate-900 sm:text-lg">{{ $category['name'] }}</h3>
        <p class="mt-1 text-xs text-slate-500 sm:text-sm">{{ $category['count'] }}</p>
    </div>
</article>
