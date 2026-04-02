@extends('layouts.app')

@section('content')
    <div class="relative isolate overflow-hidden bg-slate-950">
        <div class="absolute inset-0">
            <img src="{{ $hero['image'] }}" alt="Hero background" class="h-full w-full object-cover object-center">
            <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(3,7,18,0.42)_0%,rgba(7,10,24,0.62)_18%,rgba(10,14,29,0.72)_38%,rgba(5,8,20,0.9)_68%,rgba(2,6,23,0.98)_100%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(16,185,129,0.16),transparent_30%),radial-gradient(circle_at_80%_20%,rgba(14,116,144,0.2),transparent_24%)]"></div>
            <div class="absolute inset-0 opacity-[0.08]" style="background-image: linear-gradient(rgba(248,250,252,0.2) 1px, transparent 1px), linear-gradient(90deg, rgba(248,250,252,0.2) 1px, transparent 1px); background-size: 72px 72px;"></div>
        </div>

        @include('partials.header')

        <section class="relative mx-auto max-w-7xl px-4 pb-10 pt-20 sm:px-6 sm:pb-14 sm:pt-28 lg:px-8 lg:pb-24 lg:pt-40">
            <div class="overflow-hidden rounded-[1.9rem] bg-slate-950/10 shadow-[0_32px_90px_-46px_rgba(15,23,42,0.82)] sm:rounded-[2.25rem]">
                <div class="relative">
                    <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(2,6,23,0.16)_0%,rgba(2,6,23,0.34)_28%,rgba(2,6,23,0.54)_66%,rgba(2,6,23,0.68)_100%)] lg:bg-[linear-gradient(90deg,rgba(2,6,23,0.7)_0%,rgba(2,6,23,0.42)_44%,rgba(2,6,23,0.2)_100%)]"></div>

                    <div class="relative px-5 pb-16 pt-16 text-center text-white sm:px-8 sm:pb-20 sm:pt-20 lg:px-12 lg:pb-24 lg:pt-20 lg:text-left">
                        <div class="mx-auto max-w-xl lg:mx-0 lg:max-w-3xl">
                            <span class="inline-flex items-center rounded-full border border-white/12 bg-white/10 px-3.5 py-2 text-[0.68rem] font-semibold uppercase tracking-[0.28em] text-white/90 backdrop-blur-md">
                                Брзо пребарување на работа
                            </span>

                            <h1 class="mt-4 text-[1.86rem] font-extrabold leading-[1.04] tracking-[-0.03em] text-white sm:text-[2.15rem] lg:max-w-[11ch] lg:text-[4rem] lg:leading-[1.02]">{{ $hero['title'] }}</h1>

                            <p class="mx-auto mt-3 max-w-[29rem] text-[0.92rem] font-medium leading-6 text-slate-100/90 sm:mt-4 sm:text-[0.98rem] sm:leading-7 lg:mx-0 lg:max-w-xl lg:text-lg">
                                {{ $hero['subtitle'] }} со поедноставно мобилно пребарување, подобра прегледност и побрз пат до огласите што ти одговараат.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <form id="home-search-card" method="GET" action="{{ route('jobs.index') }}" class="relative z-10 mx-auto -mt-10 max-w-5xl rounded-[1.7rem] bg-white p-4 shadow-[0_32px_80px_-34px_rgba(15,23,42,0.34)] sm:-mt-12 sm:rounded-[2rem] sm:p-5 lg:-mt-18 lg:p-6">
                <div class="space-y-5">
                    <div class="text-center lg:text-left">
                        <p class="text-[0.72rem] font-semibold uppercase tracking-[0.26em] text-emerald-600">Почни веднаш</p>
                        <h2 class="mt-2 text-xl font-bold tracking-tight text-slate-900 sm:text-[1.7rem]">Најди оглас за неколку секунди</h2>
                    </div>

                    <div class="grid gap-4 lg:grid-cols-[1.55fr_0.9fr_0.9fr_auto]">
                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Клучен збор</span>
                            <input
                                name="q"
                                type="text"
                                value="{{ request('q') }}"
                                placeholder="Пр. промотер, магационер..."
                                class="h-[3.7rem] w-full rounded-[1.15rem] border border-slate-200 bg-slate-50 px-4 text-sm font-medium text-slate-900 outline-none transition placeholder:font-normal placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/14"
                            >
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Локација</span>
                            <input
                                name="city"
                                type="text"
                                value="{{ request('city') }}"
                                placeholder="Пр. Скопје"
                                class="h-[3.7rem] w-full rounded-[1.15rem] border border-slate-200 bg-slate-50 px-4 text-sm font-medium text-slate-900 outline-none transition placeholder:font-normal placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/14"
                            >
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Категорија</span>
                            <span class="relative block">
                                <select name="category" class="h-[3.7rem] w-full appearance-none rounded-[1.15rem] border border-slate-200 bg-slate-50 px-4 pr-11 text-sm font-medium text-slate-600 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/14">
                                    <option value="" @selected(request('category') === '')>Избери категорија</option>
                                    @foreach ($searchCategories as $category)
                                        <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                                    @endforeach
                                </select>
                                <svg viewBox="0 0 20 20" fill="currentColor" class="pointer-events-none absolute right-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </label>

                        <div class="flex items-end">
                            <button
                                type="submit"
                                class="inline-flex h-[3.85rem] w-full items-center justify-center rounded-[1.2rem] bg-emerald-600 px-8 text-sm font-semibold text-white shadow-[0_22px_40px_-18px_rgba(5,150,105,0.82)] transition hover:bg-emerald-500 active:translate-y-px lg:min-w-[10rem]"
                            >
                                Пребарувај
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>

    <main>
        <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8 lg:py-20">
            <div class="text-center lg:text-left">
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-[2rem]">Последно додадени огласи:</h2>
            </div>

            <div class="mt-6 grid gap-5 lg:grid-cols-3 sm:mt-8 sm:gap-6">
                @foreach ($jobs as $job)
                    @include('partials.job-card', ['job' => $job])
                @endforeach
            </div>

            <div class="mt-8 text-center sm:mt-9">
                <a href="{{ route('jobs.index') }}" class="inline-flex w-full items-center justify-center rounded-full bg-emerald-600 px-7 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500 sm:min-w-44 sm:w-auto">
                    Повеќе огласи
                </a>
            </div>
        </section>

        <section class="bg-white py-12 sm:py-16 lg:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-[2rem]">Категории</h2>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-2 sm:mt-8 sm:gap-6">
                    @foreach ($categories as $category)
                        @include('partials.category-card', ['category' => $category])
                    @endforeach
                </div>

                <div class="mt-8 text-center sm:mt-9">
                    <a href="{{ route('jobs.index') }}" class="inline-flex w-full items-center justify-center rounded-full bg-emerald-600 px-7 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500 sm:min-w-44 sm:w-auto">
                        Сите Категории
                    </a>
                </div>
            </div>
        </section>

        <section class="bg-slate-950 py-12 text-white sm:py-16 lg:py-20">
            <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[1fr_1fr] lg:items-center lg:px-8 lg:gap-12">
                <div class="text-center lg:text-left">
                    <h2 class="mx-auto max-w-xl text-2xl font-bold tracking-tight sm:text-[2.45rem] sm:leading-tight lg:mx-0">{{ $promo['title'] }}</h2>
                    <div class="mt-6 space-y-3 sm:mt-8 sm:space-y-4">
                        @foreach ($promo['points'] as $point)
                            <div class="flex items-start justify-center gap-3 text-sm text-slate-200 sm:items-center sm:text-base lg:justify-start">
                                <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-500/15 text-emerald-300">
                                    <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 010 1.42l-7.2 7.2a1 1 0 01-1.415 0l-3-3a1 1 0 111.414-1.42l2.293 2.294 6.493-6.494a1 1 0 011.415 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                {{ $point }}
                            </div>
                        @endforeach
                    </div>

                    <a href="{{ route('jobs.index') }}" class="mt-8 inline-flex w-full items-center justify-center rounded-full bg-emerald-600 px-7 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-950/30 transition hover:bg-emerald-500 sm:mt-10 sm:min-w-40 sm:w-auto">
                        Види огласи
                    </a>
                </div>

                <div class="relative mx-auto w-full max-w-xl lg:mr-0">
                    <div class="overflow-hidden rounded-[2rem] border border-white/10 shadow-[0_28px_80px_-32px_rgba(0,0,0,0.75)]">
                        <img src="{{ $promo['primary_image'] }}" alt="Работна средина" class="h-[18rem] w-full object-cover sm:h-[22rem] lg:h-[26rem]">
                    </div>
                    <div class="absolute -bottom-4 left-3 hidden w-40 overflow-hidden rounded-[1.25rem] border-4 border-slate-950 shadow-[0_24px_50px_-24px_rgba(0,0,0,0.9)] sm:block md:-bottom-6 md:-left-4 md:w-64 md:rounded-[1.5rem]">
                        <img src="{{ $promo['secondary_image'] }}" alt="Тимска работа" class="h-40 w-full object-cover md:h-44">
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-stone-50 py-12 sm:py-16 lg:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-[2rem]">Нашите задоволни корисници 😍</h2>
                    <p class="mt-2 text-sm text-slate-600 sm:mt-3 sm:text-base">Прочитајте дел од рецензиите!</p>
                </div>

                <div class="mt-6 grid gap-4 lg:grid-cols-3 sm:mt-8 sm:gap-6">
                    @foreach ($testimonials as $testimonial)
                        @include('partials.testimonial-card', ['testimonial' => $testimonial])
                    @endforeach
                </div>
            </div>
        </section>

        <section class="bg-white py-12 sm:py-16 lg:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-[2rem]">Нашиот блог</h2>
                    <p class="mt-2 text-sm text-slate-600 sm:mt-3 sm:text-base">Прочитајте некои интересни статии кои може да ви помогнат.</p>
                </div>

                <div class="mt-6 grid gap-5 lg:grid-cols-3 sm:mt-8 sm:gap-6">
                    @foreach ($posts as $post)
                        @include('partials.blog-card', ['post' => $post])
                    @endforeach
                </div>
            </div>
        </section>
    </main>

    @include('partials.footer')
@endsection
