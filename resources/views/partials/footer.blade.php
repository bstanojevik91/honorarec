<footer class="bg-slate-950 text-white">
    <div class="border-b border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-8 text-center sm:px-6 sm:py-10 lg:px-8">
            <p class="text-2xl font-bold tracking-tight sm:text-3xl">Honorarec.mk</p>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center gap-8 text-center lg:flex-row lg:items-center lg:justify-between lg:text-left">
            <div class="flex flex-col items-center gap-3 lg:items-start">
                <img src="{{ asset('images/honorarec-logo.png') }}" alt="Хонорарец" class="h-12 w-auto max-w-[14rem] object-contain">
                <p class="text-sm text-slate-400">© {{ now()->year }} Сите права се задржани.</p>
            </div>

            <div class="grid w-full max-w-md grid-cols-2 gap-3 sm:max-w-xl sm:grid-cols-{{ count($footerStats) > 2 ? '4' : count($footerStats) }}">
                @foreach ($footerStats as $stat)
                    <div class="rounded-[1.2rem] border border-white/10 bg-white/5 px-4 py-3 text-center">
                        <p class="text-xl font-bold text-emerald-300 sm:text-2xl">{{ $stat['value'] }}</p>
                        <p class="mt-1 text-[11px] uppercase tracking-[0.18em] text-slate-400 sm:text-xs">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="flex items-center gap-3">
                <a href="#" class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/10 bg-white/5 text-slate-200 transition hover:bg-white/10" aria-label="Facebook">
                    <svg viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5" aria-hidden="true">
                        <path d="M13.5 21v-7h2.4l.4-3h-2.8V9.3c0-.9.3-1.5 1.6-1.5H16V5.1c-.3 0-1.1-.1-2.1-.1-2.1 0-3.5 1.3-3.5 3.8V11H8v3h2.3v7h3.2z" />
                    </svg>
                </a>
                <a href="#" class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/10 bg-white/5 text-slate-200 transition hover:bg-white/10" aria-label="Instagram">
                    <svg viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5" aria-hidden="true">
                        <path d="M7 3h10a4 4 0 014 4v10a4 4 0 01-4 4H7a4 4 0 01-4-4V7a4 4 0 014-4zm0 2a2 2 0 00-2 2v10c0 1.1.9 2 2 2h10a2 2 0 002-2V7a2 2 0 00-2-2H7zm10.5 1.5a1 1 0 110 2 1 1 0 010-2zM12 7a5 5 0 110 10 5 5 0 010-10zm0 2a3 3 0 100 6 3 3 0 000-6z" />
                    </svg>
                </a>
                <a href="#" class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/10 bg-white/5 text-slate-200 transition hover:bg-white/10" aria-label="LinkedIn">
                    <svg viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5" aria-hidden="true">
                        <path d="M6.94 8.5A1.56 1.56 0 105.38 6.94 1.56 1.56 0 006.94 8.5zM5.56 10h2.77v8.44H5.56zm4.5 0h2.65v1.15h.04a2.9 2.9 0 012.61-1.43c2.79 0 3.3 1.84 3.3 4.22V18.4h-2.77v-3.96c0-.94-.02-2.15-1.31-2.15s-1.51 1.02-1.51 2.08v4.03h-2.77z" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-2 gap-x-4 gap-y-3 border-t border-white/10 pt-6 text-center text-sm text-slate-400 sm:flex sm:flex-wrap sm:items-center sm:justify-center sm:gap-6">
            <a href="{{ route('home') }}" class="transition hover:text-white">Почетна</a>
            <a href="{{ route('jobs.index') }}" class="transition hover:text-white">Сите огласи</a>
            <a href="{{ route('blog.index') }}" class="transition hover:text-white">Блог</a>
            <a href="{{ route('faq') }}" class="transition hover:text-white">ЧПП</a>
            <a href="{{ route('faq') }}" class="transition hover:text-white">Контакт</a>
            <a href="{{ route('employer.register') }}" class="transition hover:text-white">Регистрација за компании</a>
            <a href="{{ route('employer.login') }}" class="col-span-2 font-medium text-emerald-300 transition hover:text-white sm:col-auto">Најава за компании</a>
        </div>
    </div>
</footer>
