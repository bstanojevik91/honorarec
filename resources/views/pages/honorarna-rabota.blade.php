@extends('layouts.app')

@section('content')
    @php
        $jobTypes = collect($jobTypes ?? []);
        $latestJobs = collect($latestJobs ?? []);
    @endphp

    <div class="relative isolate overflow-hidden bg-slate-950">
        <div class="absolute inset-0">
            <div class="h-full w-full bg-[radial-gradient(circle_at_top_left,_rgba(16,185,129,0.2),_transparent_30%),radial-gradient(circle_at_top_right,_rgba(251,146,60,0.14),_transparent_28%),linear-gradient(180deg,_rgba(2,6,23,0.78),_rgba(2,6,23,0.96))]"></div>
        </div>

        @include('partials.header')

        <section class="relative mx-auto max-w-7xl px-4 pb-12 pt-28 sm:px-6 sm:pb-16 sm:pt-32 lg:px-8 lg:pb-20 lg:pt-44">
            <div class="max-w-5xl">
                <nav aria-label="Breadcrumb" class="mb-6">
                    <ol class="flex flex-wrap items-center gap-2 text-sm text-slate-300">
                        <li>
                            <a href="{{ route('home') }}" class="transition hover:text-white">Почетна</a>
                        </li>
                        <li class="text-slate-500">></li>
                        <li class="font-semibold text-white">Хонорарна работа</li>
                    </ol>
                </nav>

                <div class="grid gap-6 lg:grid-cols-[minmax(0,1.2fr)_minmax(19rem,0.8fr)] lg:items-end lg:gap-8">
                    <div class="text-white">
                        <span class="inline-flex rounded-full border border-white/10 bg-white/8 px-3.5 py-1.5 text-xs font-semibold uppercase tracking-[0.24em] text-emerald-300">
                            SEO Landing Page
                        </span>
                        <h1 class="mt-5 max-w-4xl text-[2.3rem] font-extrabold tracking-tight sm:text-5xl lg:text-[3.7rem] lg:leading-[1.02]">
                            Хонорарна работа во Македонија
                        </h1>
                        <p class="mt-5 max-w-3xl text-base leading-8 text-slate-200 sm:text-lg">
                            На Honorarec.mk можеш да пронајдеш хонорарна работа, работа на дневница, part-time, сезонски и флексибилни работни ангажмани низ Македонија.
                        </p>

                        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
                            <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-7 py-3.5 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
                                Продолжи кон Honorarec.mk
                            </a>
                            <a href="{{ route('jobs.index') }}" class="inline-flex items-center justify-center rounded-full border border-white/12 bg-white/8 px-7 py-3.5 text-sm font-semibold text-white transition hover:bg-white/12">
                                Види активни огласи
                            </a>
                        </div>
                    </div>

                    <aside class="rounded-[1.6rem] border border-white/10 bg-white/8 p-5 text-white shadow-[0_24px_48px_-28px_rgba(15,23,42,0.8)] backdrop-blur sm:p-6">
                        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-300">Брз преглед</p>
                        <div class="mt-5 grid gap-3">
                            <div class="rounded-[1.2rem] border border-white/10 bg-slate-950/35 px-4 py-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Пребарувања</p>
                                <p class="mt-2 text-sm leading-7 text-slate-100">хонорарна работа, honorarna rabota, работа на дневница, rabota na dnevnica</p>
                            </div>
                            <div class="rounded-[1.2rem] border border-white/10 bg-slate-950/35 px-4 py-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Типови на ангажман</p>
                                <p class="mt-2 text-sm leading-7 text-slate-100">Part-time работа, сезонска работа и флексибилни работни ангажмани со реални огласи.</p>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    </div>

    <main class="bg-stone-50">
        <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 sm:py-12 lg:px-8 lg:py-16">
            <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.18)] sm:rounded-[1.8rem] sm:p-8">
                <div class="max-w-3xl">
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-600">Видови работа</p>
                    <h2 class="mt-3 text-2xl font-bold tracking-tight text-slate-900 sm:text-[2rem]">Каков тип на работа можеш да најдеш?</h2>
                    <p class="mt-4 text-sm leading-7 text-slate-600 sm:text-base sm:leading-8">
                        Оваа страница е наменета како брз влез до огласите за хонорарна работа во Македонија. Наместо долга навигација, можеш веднаш да продолжиш кон активните огласи или да се ориентираш според типот на ангажман што го бараш.
                    </p>
                </div>

                <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                    @foreach ($jobTypes as $jobType)
                        <article class="rounded-[1.35rem] border border-slate-200 bg-slate-50/70 p-5 shadow-[0_18px_36px_-34px_rgba(15,23,42,0.3)] transition hover:border-emerald-200 hover:bg-white">
                            <div class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                                <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 2a1 1 0 01.993.883L11 3v1h2a2 2 0 011.995 1.85L15 6v8a2 2 0 01-1.85 1.995L13 16H7a2 2 0 01-1.995-1.85L5 14V6a2 2 0 011.85-1.995L7 4h2V3a1 1 0 011-1zm3 6H7v6h6V8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-bold text-slate-900">{{ $jobType['title'] }}</h3>
                            <p class="mt-3 text-sm leading-7 text-slate-600">{{ $jobType['text'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 pb-10 sm:px-6 sm:pb-12 lg:px-8 lg:pb-16">
            <div class="grid gap-6 lg:grid-cols-[minmax(0,1.15fr)_minmax(19rem,0.85fr)]">
                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.18)] sm:rounded-[1.8rem] sm:p-8">
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-600">За работодавачи</p>
                    <h2 class="mt-3 text-2xl font-bold tracking-tight text-slate-900 sm:text-[2rem]">Објави оглас и добиј релевантни кандидати</h2>
                    <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-600 sm:text-base sm:leading-8">
                        Објави оглас и добиј листа на кандидати кои можеш лесно да ги прегледаш и одбереш. Honorarec.mk е создаден за брз контакт помеѓу компании и луѓе кои бараат хонорарна, part-time или сезонска работа.
                    </p>
                    <a href="{{ route('post-a-job') }}" class="mt-7 inline-flex items-center justify-center rounded-full bg-emerald-600 px-7 py-3.5 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
                        Објави оглас
                    </a>
                </div>

                <div class="rounded-[1.5rem] border border-slate-200 bg-slate-900 p-5 text-white shadow-[0_24px_50px_-34px_rgba(15,23,42,0.45)] sm:rounded-[1.8rem] sm:p-8">
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-300">Зошто оваа страница</p>
                    <ul class="mt-5 space-y-4 text-sm leading-7 text-slate-200">
                        <li class="flex gap-3">
                            <span class="mt-2 h-2.5 w-2.5 shrink-0 rounded-full bg-emerald-400"></span>
                            <span>Го таргетира пребарувањето за хонорарна работа и работа на дневница без да ја дуплира почетната страница.</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="mt-2 h-2.5 w-2.5 shrink-0 rounded-full bg-orange-400"></span>
                            <span>Ги води посетителите директно кон главната страница и кон активните огласи што веќе постојат во базата.</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="mt-2 h-2.5 w-2.5 shrink-0 rounded-full bg-sky-400"></span>
                            <span>Создава јасен SEO влез за honorarna rabota, rabota na dnevnica, part-time работа и сезонска работа.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 pb-10 sm:px-6 sm:pb-12 lg:px-8 lg:pb-16">
            <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.18)] sm:rounded-[1.8rem] sm:p-8">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-600">Најнови огласи</p>
                        <h2 class="mt-3 text-2xl font-bold tracking-tight text-slate-900 sm:text-[2rem]">Последни 6 активни огласи</h2>
                    </div>
                    <a href="{{ route('jobs.index') }}" class="inline-flex items-center text-sm font-semibold text-emerald-700 transition hover:text-emerald-600">
                        Види ги сите огласи
                    </a>
                </div>

                @if ($latestJobs->isNotEmpty())
                    <div class="mt-8 grid gap-4 lg:grid-cols-2">
                        @foreach ($latestJobs as $job)
                            @php
                                $salaryLabel = !empty($job['daily_pay'])
                                    ? number_format((float) $job['daily_pay'], 0, ',', '.') . ' денари'
                                    : 'По договор';
                            @endphp
                            <article class="rounded-[1.35rem] border border-slate-200 bg-slate-50/70 p-5 shadow-[0_18px_36px_-34px_rgba(15,23,42,0.24)] transition hover:border-emerald-200 hover:bg-white">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="inline-flex rounded-full bg-orange-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-orange-700">
                                                {{ $job['badge'] }}
                                            </span>
                                            @if (!empty($job['engagement_type']))
                                                <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-semibold text-emerald-700">
                                                    {{ $job['engagement_type'] }}
                                                </span>
                                            @endif
                                        </div>

                                        <h3 class="mt-4 text-lg font-bold leading-snug text-slate-900">
                                            <a href="{{ route('jobs.show', $job['slug']) }}" class="transition hover:text-emerald-700">
                                                {{ $job['title'] }}
                                            </a>
                                        </h3>

                                        <div class="mt-3 flex flex-wrap items-center gap-x-3 gap-y-2 text-sm text-slate-500">
                                            <span>{{ $job['company'] }}</span>
                                            <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                                            <span>{{ $job['category'] ?: 'Општа категорија' }}</span>
                                            <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                                            <span>{{ $job['location'] ?: 'Македонија' }}</span>
                                        </div>
                                    </div>

                                    <div class="shrink-0 rounded-[1.15rem] bg-white px-4 py-3 text-left shadow-[0_14px_28px_-24px_rgba(15,23,42,0.24)] sm:min-w-[9rem]">
                                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-400">Дневница</p>
                                        <p class="mt-2 text-sm font-bold text-slate-900">{{ $salaryLabel }}</p>
                                    </div>
                                </div>

                                <div class="mt-5 flex items-center justify-between gap-4 border-t border-slate-200 pt-4">
                                    <p class="text-xs font-medium uppercase tracking-[0.16em] text-slate-400">Активен оглас</p>
                                    <a href="{{ route('jobs.show', $job['slug']) }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">
                                        Отвори оглас
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="mt-8 rounded-[1.35rem] border border-slate-200 bg-slate-50 px-5 py-8 text-center sm:px-6">
                        <h3 class="text-xl font-bold text-slate-900">Моментално нема активни огласи</h3>
                        <p class="mt-3 text-sm leading-7 text-slate-600">Провери повторно наскоро или продолжи кон главната страница за повеќе информации.</p>
                        <div class="mt-6 flex flex-col justify-center gap-3 sm:flex-row">
                            <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
                                Кон почетна
                            </a>
                            <a href="{{ route('jobs.index') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-white">
                                Кон огласи
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 pb-14 sm:px-6 sm:pb-16 lg:px-8 lg:pb-20">
            <div class="rounded-[1.35rem] border border-slate-200 bg-white px-5 py-6 text-center shadow-[0_20px_45px_-34px_rgba(15,23,42,0.16)] sm:px-6">
                <p class="text-sm text-slate-600">
                    Имаш прашања?
                    <a href="https://honorarec.mk/chpp" class="font-semibold text-emerald-700 transition hover:text-emerald-600">
                        Посети ја нашата ЧПП страница.
                    </a>
                </p>
            </div>
        </section>
    </main>

    @include('partials.footer')
@endsection
