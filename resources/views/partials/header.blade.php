@php
    $companyAccessRoute = auth()->check()
        ? (auth()->user()->is_admin ? route('admin.dashboard') : route('employer.dashboard'))
        : route('employer.login');
    $companyAccessLabel = auth()->check()
        ? (auth()->user()->is_admin ? 'Админ панел' : 'Employer панел')
        : 'Најава за компании';
    $companyRegisterRoute = auth()->check()
        ? $companyAccessRoute
        : route('employer.register');
    $companyRegisterLabel = auth()->check() ? $companyAccessLabel : 'Регистрација за компании';
@endphp

<header class="absolute inset-x-0 top-0 z-20">
    <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 sm:py-5 lg:px-8">
        <div class="relative z-30 md:hidden">
            <div class="grid grid-cols-[2.9rem_1fr_2.9rem] items-center gap-3 px-0.5">
                <details class="relative">
                    <summary class="flex h-[2.9rem] w-[2.9rem] cursor-pointer list-none items-center justify-center rounded-[1rem] border border-white/12 bg-slate-950/50 text-white shadow-[0_18px_40px_-24px_rgba(0,0,0,0.8)] ring-1 ring-white/8 backdrop-blur-md transition hover:bg-white/10 active:scale-[0.98]">
                        <span class="sr-only">Отвори мени</span>
                        <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm1 4a1 1 0 100 2h12a1 1 0 100-2H4z" clip-rule="evenodd" />
                        </svg>
                    </summary>

                    <div class="absolute left-0 top-full z-40 mt-3 w-[min(17.5rem,calc(100vw-2rem))] overflow-hidden rounded-[1.3rem] border border-white/8 bg-slate-950 p-2.5 text-sm font-medium text-slate-100 shadow-[0_26px_46px_-24px_rgba(2,6,23,0.92)]">
                        <div class="grid gap-1">
                            <a href="{{ route('jobs.index') }}" class="rounded-[0.95rem] px-3.5 py-3 leading-5 text-slate-100 transition hover:bg-white/6">Сите огласи</a>
                            <a href="{{ route('faq') }}" class="rounded-[0.95rem] px-3.5 py-3 leading-5 text-slate-100 transition hover:bg-white/6">ЧПП</a>
                            <a href="{{ route('faq') }}" class="rounded-[0.95rem] px-3.5 py-3 leading-5 text-slate-100 transition hover:bg-white/6">Објави оглас / Контакт</a>
                            <a href="{{ $companyRegisterRoute }}" class="rounded-[0.95rem] px-3.5 py-3 leading-5 text-slate-200 transition hover:bg-white/6">{{ $companyRegisterLabel }}</a>
                            <div class="mt-1 border-t border-white/8 pt-2">
                                <a href="{{ $companyAccessRoute }}" class="inline-flex w-full items-center justify-center rounded-[0.95rem] bg-emerald-600 px-4 py-3 text-center text-sm font-semibold text-white shadow-[0_14px_24px_-18px_rgba(5,150,105,0.9)] transition hover:bg-emerald-500 active:scale-[0.99]">{{ $companyAccessLabel }}</a>
                            </div>
                        </div>
                    </div>
                </details>

                <a href="{{ route('home') }}" class="inline-flex min-w-0 items-center justify-center self-center px-2 text-white">
                    <img src="{{ asset('images/honorarec-logo.png') }}" alt="Хонорарец" class="h-12 w-auto max-w-[12.5rem] object-contain">
                </a>

                <a href="{{ $companyAccessRoute }}" class="inline-flex h-[2.9rem] w-[2.9rem] items-center justify-center justify-self-end rounded-[1rem] border border-white/12 bg-emerald-600 text-white shadow-[0_20px_35px_-20px_rgba(5,150,105,0.9)] ring-1 ring-white/8 transition hover:bg-emerald-500 active:scale-[0.98]" aria-label="{{ $companyAccessLabel }}">
                    <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                        <path fill-rule="evenodd" d="M5 8a5 5 0 1110 0v1h.75A2.25 2.25 0 0118 11.25v4.5A2.25 2.25 0 0115.75 18h-11.5A2.25 2.25 0 012 15.75v-4.5A2.25 2.25 0 014.25 9H5V8zm8 1V8a3 3 0 10-6 0v1h6z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="hidden items-center justify-between md:flex">
            <a href="{{ route('home') }}" class="inline-flex min-w-0 items-center text-white">
                <img src="{{ asset('images/honorarec-logo.png') }}" alt="Хонорарец" class="h-10.5 w-auto max-w-[15.75rem] object-contain lg:h-[3rem]">
            </a>

            <nav class="hidden items-center gap-1 rounded-full border border-white/10 bg-slate-950/40 px-2 py-2 text-sm font-medium text-slate-100 shadow-[0_18px_40px_-20px_rgba(0,0,0,0.75)] backdrop-blur md:flex">
                <a href="{{ route('jobs.index') }}" class="rounded-full px-4 py-2.5 transition hover:bg-white/10">Сите огласи</a>
                <a href="{{ route('faq') }}" class="rounded-full px-4 py-2.5 transition hover:bg-white/10">ЧПП</a>
                <a href="{{ route('faq') }}" class="rounded-full bg-white/10 px-4 py-2.5 transition hover:bg-white/15">Објави оглас / Контакт</a>
                <a href="{{ $companyRegisterRoute }}" class="rounded-full px-4 py-2.5 transition hover:bg-white/10">{{ $companyRegisterLabel }}</a>
                <a href="{{ $companyAccessRoute }}" class="rounded-full border border-white/10 bg-emerald-600 px-4 py-2.5 text-white transition hover:bg-emerald-500">{{ $companyAccessLabel }}</a>
            </nav>
        </div>
    </div>
</header>
