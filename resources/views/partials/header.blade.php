<header class="absolute inset-x-0 top-0 z-20">
    <div class="mx-auto max-w-7xl px-4 py-3.5 sm:px-6 sm:py-5 lg:px-8">
        <div class="relative z-30 md:hidden">
            <div class="grid grid-cols-[2.9rem_1fr_2.9rem] items-center gap-3">
                <details class="relative">
                    <summary class="flex h-[2.9rem] w-[2.9rem] cursor-pointer list-none items-center justify-center rounded-[1rem] border border-white/12 bg-slate-950/50 text-white shadow-[0_18px_40px_-24px_rgba(0,0,0,0.8)] ring-1 ring-white/8 backdrop-blur-md transition hover:bg-white/10 active:scale-[0.98]">
                        <span class="sr-only">Отвори мени</span>
                        <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm1 4a1 1 0 100 2h12a1 1 0 100-2H4z" clip-rule="evenodd" />
                        </svg>
                    </summary>

                    <div class="absolute left-0 top-full mt-3 w-[min(19.5rem,calc(100vw-2rem))] rounded-[1.35rem] border border-white/10 bg-slate-950/94 p-2.5 text-sm font-medium text-slate-100 shadow-[0_28px_55px_-28px_rgba(0,0,0,0.88)] backdrop-blur-xl">
                        <div class="grid gap-1.5">
                            <a href="{{ route('jobs.index') }}" class="rounded-[1rem] px-4 py-3 transition hover:bg-white/10">Сите огласи</a>
                            <a href="{{ route('faq') }}" class="rounded-[1rem] px-4 py-3 transition hover:bg-white/10">ЧПП</a>
                            <a href="{{ route('faq') }}" class="rounded-[1rem] bg-white/10 px-4 py-3 transition hover:bg-white/15">Објави оглас / Контакт</a>
                            <a href="{{ route('employer.register') }}" class="rounded-[1rem] px-4 py-3 transition hover:bg-white/10">Регистрација за компании</a>
                            <a href="{{ route('employer.login') }}" class="rounded-[1rem] bg-emerald-600 px-4 py-3 text-center text-white transition hover:bg-emerald-500">Најава за компании</a>
                        </div>
                    </div>
                </details>

                <a href="{{ route('home') }}" class="inline-flex min-w-0 items-center justify-center gap-2.5 text-white">
                    <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-[0.95rem] border border-white/12 bg-white/10 text-base font-bold shadow-[0_16px_35px_-22px_rgba(0,0,0,0.75)] backdrop-blur-md">
                        H
                    </span>
                    <span class="truncate text-[1.02rem] font-semibold tracking-[0.01em]">Хонорарец</span>
                </a>

                <a href="{{ route('employer.login') }}" class="inline-flex h-[2.9rem] w-[2.9rem] items-center justify-center justify-self-end rounded-[1rem] border border-white/12 bg-emerald-600 text-white shadow-[0_20px_35px_-20px_rgba(5,150,105,0.9)] ring-1 ring-white/8 transition hover:bg-emerald-500 active:scale-[0.98]" aria-label="Најава за компании">
                    <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                        <path fill-rule="evenodd" d="M5 8a5 5 0 1110 0v1h.75A2.25 2.25 0 0118 11.25v4.5A2.25 2.25 0 0115.75 18h-11.5A2.25 2.25 0 012 15.75v-4.5A2.25 2.25 0 014.25 9H5V8zm8 1V8a3 3 0 10-6 0v1h6z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="hidden items-center justify-between md:flex">
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
        </div>
    </div>
</header>
