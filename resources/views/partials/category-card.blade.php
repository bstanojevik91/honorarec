<article class="flex items-center gap-5 rounded-[1.6rem] border border-slate-200 bg-stone-50 p-6 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.3)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_28px_50px_-32px_rgba(15,23,42,0.28)]">
    <div class="inline-flex h-16 w-16 shrink-0 items-center justify-center rounded-[1.15rem] bg-emerald-100 text-emerald-700">
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
        <h3 class="text-[1.35rem] font-bold text-slate-900">{{ $category['name'] }}</h3>
        <p class="mt-1.5 text-sm text-slate-500">{{ $category['count'] }}</p>
    </div>
</article>
