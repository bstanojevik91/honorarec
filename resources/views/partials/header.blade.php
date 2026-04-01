<header class="absolute inset-x-0 top-0 z-20">
    <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 text-white">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/15 bg-white/10 text-lg font-bold shadow-lg shadow-slate-950/20 backdrop-blur">
                    H
                </span>
                <span class="text-lg font-semibold tracking-wide">Хонорарец</span>
            </a>

            <nav class="hidden items-center gap-1 rounded-full border border-white/10 bg-slate-950/40 px-2 py-2 text-sm font-medium text-slate-100 shadow-[0_18px_40px_-20px_rgba(0,0,0,0.75)] backdrop-blur md:flex">
                <a href="{{ route('jobs.index') }}" class="rounded-full px-4 py-2.5 transition hover:bg-white/10">Сите огласи</a>
                <a href="{{ route('faq') }}" class="rounded-full px-4 py-2.5 transition hover:bg-white/10">ЧПП</a>
                <a href="{{ route('faq') }}" class="rounded-full bg-white/10 px-4 py-2.5 transition hover:bg-white/15">Објави оглас / Контакт</a>
                <a href="{{ route('employer.register') }}" class="rounded-full px-4 py-2.5 transition hover:bg-white/10">Регистрација за компании</a>
                <a href="{{ route('employer.login') }}" class="rounded-full border border-white/10 bg-emerald-600 px-4 py-2.5 text-white transition hover:bg-emerald-500">Најава за компании</a>
            </nav>
        </div>

        <nav class="mt-4 grid gap-2 rounded-[1.35rem] border border-white/10 bg-slate-950/40 p-2 text-sm font-medium text-slate-100 shadow-[0_18px_40px_-20px_rgba(0,0,0,0.75)] backdrop-blur md:hidden">
            <a href="{{ route('jobs.index') }}" class="rounded-xl px-4 py-3 transition hover:bg-white/10">Сите огласи</a>
            <a href="{{ route('faq') }}" class="rounded-xl px-4 py-3 transition hover:bg-white/10">ЧПП</a>
            <a href="{{ route('faq') }}" class="rounded-xl bg-white/10 px-4 py-3 transition hover:bg-white/15">Објави оглас / Контакт</a>
            <a href="{{ route('employer.register') }}" class="rounded-xl px-4 py-3 transition hover:bg-white/10">Регистрација за компании</a>
            <a href="{{ route('employer.login') }}" class="rounded-xl bg-emerald-600 px-4 py-3 text-white transition hover:bg-emerald-500">Најава за компании</a>
        </nav>
    </div>
</header>
