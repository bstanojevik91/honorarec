<header class="absolute inset-x-0 top-0 z-20">
    <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 sm:py-5 lg:px-8">
        <div class="flex items-center justify-between gap-3">
            <a href="{{ route('home') }}" class="inline-flex min-w-0 items-center gap-2.5 text-white sm:gap-3">
                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-white/15 bg-white/10 text-base font-bold shadow-lg shadow-slate-950/20 backdrop-blur sm:h-11 sm:w-11 sm:text-lg">
                    H
                </span>
                <span class="truncate text-base font-semibold tracking-wide sm:text-lg">Хонорарец</span>
            </a>

            <nav class="hidden items-center gap-1 rounded-full border border-white/10 bg-slate-950/40 px-2 py-2 text-sm font-medium text-slate-100 shadow-[0_18px_40px_-20px_rgba(0,0,0,0.75)] backdrop-blur md:flex">
                <a href="{{ route('jobs.index') }}" class="rounded-full px-4 py-2.5 transition hover:bg-white/10">Сите огласи</a>
                <a href="{{ route('faq') }}" class="rounded-full px-4 py-2.5 transition hover:bg-white/10">ЧПП</a>
                <a href="{{ route('faq') }}" class="rounded-full bg-white/10 px-4 py-2.5 transition hover:bg-white/15">Објави оглас / Контакт</a>
                <a href="{{ route('employer.register') }}" class="rounded-full px-4 py-2.5 transition hover:bg-white/10">Регистрација за компании</a>
                <a href="{{ route('employer.login') }}" class="rounded-full border border-white/10 bg-emerald-600 px-4 py-2.5 text-white transition hover:bg-emerald-500">Најава за компании</a>
            </nav>

            <details class="relative md:hidden">
                <summary class="flex h-11 w-11 cursor-pointer list-none items-center justify-center rounded-full border border-white/15 bg-slate-950/45 text-white shadow-lg shadow-slate-950/30 backdrop-blur transition hover:bg-white/10">
                    <span class="sr-only">Отвори мени</span>
                    <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm1 4a1 1 0 100 2h12a1 1 0 100-2H4z" clip-rule="evenodd" />
                    </svg>
                </summary>

                <div class="absolute right-0 mt-3 w-[min(21rem,calc(100vw-2rem))] rounded-[1.4rem] border border-white/10 bg-slate-950/92 p-2 text-sm font-medium text-slate-100 shadow-[0_24px_50px_-28px_rgba(0,0,0,0.85)] backdrop-blur">
                    <div class="grid gap-1.5">
                        <a href="{{ route('jobs.index') }}" class="rounded-xl px-4 py-3 transition hover:bg-white/10">Сите огласи</a>
                        <a href="{{ route('faq') }}" class="rounded-xl px-4 py-3 transition hover:bg-white/10">ЧПП</a>
                        <a href="{{ route('faq') }}" class="rounded-xl bg-white/10 px-4 py-3 transition hover:bg-white/15">Објави оглас / Контакт</a>
                        <a href="{{ route('employer.register') }}" class="rounded-xl px-4 py-3 transition hover:bg-white/10">Регистрација за компании</a>
                        <a href="{{ route('employer.login') }}" class="rounded-xl bg-emerald-600 px-4 py-3 text-center text-white transition hover:bg-emerald-500">Најава за компании</a>
                    </div>
                </div>
            </details>
        </div>
    </div>
</header>
