<article class="flex flex-col items-start gap-4 rounded-[1.45rem] border border-slate-200 bg-stone-50 p-5 shadow-[0_18px_40px_-34px_rgba(15,23,42,0.24)] transition duration-300 hover:shadow-[0_24px_46px_-30px_rgba(15,23,42,0.24)] sm:flex-row sm:items-center sm:gap-5 sm:rounded-[1.6rem] sm:p-6 md:hover:-translate-y-1">
    <div class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-[1rem] bg-emerald-100 text-emerald-700 sm:h-16 sm:w-16 sm:rounded-[1.15rem]">
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
        <h3 class="text-xl font-bold text-slate-900 sm:text-[1.35rem]">{{ $category['name'] }}</h3>
        <p class="mt-1.5 text-sm text-slate-500">{{ $category['count'] }}</p>
    </div>
</article>
