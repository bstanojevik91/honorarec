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

        <section class="relative mx-auto max-w-7xl px-4 pb-14 pt-24 sm:px-6 sm:pb-16 sm:pt-28 lg:px-8 lg:pb-24 lg:pt-36">
            <div class="overflow-hidden rounded-[2rem] border border-white/10 bg-slate-950/20 shadow-[0_30px_90px_-35px_rgba(15,23,42,0.85)] backdrop-blur-[2px] sm:rounded-[2.5rem]">
                <div class="relative">
                    <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(9,12,26,0.14)_0%,rgba(9,12,26,0.55)_24%,rgba(7,10,21,0.82)_65%,rgba(2,6,23,0.92)_100%)] lg:bg-[linear-gradient(90deg,rgba(4,6,18,0.8)_0%,rgba(4,6,18,0.62)_42%,rgba(4,6,18,0.44)_72%,rgba(4,6,18,0.38)_100%)]"></div>

                    <div class="relative flex min-h-[29rem] flex-col items-center justify-center px-6 pb-16 pt-24 text-center text-white sm:min-h-[33rem] sm:px-10 sm:pb-20 sm:pt-24 lg:min-h-[35rem] lg:items-start lg:justify-center lg:px-14 lg:pb-24 lg:pt-28 lg:text-left">
                        <span class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-4 py-2 text-[0.7rem] font-semibold uppercase tracking-[0.3em] text-white/90 backdrop-blur">
                            {{ $hero['title'] }}
                        </span>

                        <h1 class="mt-6 max-w-[12ch] text-[2.5rem] font-extrabold leading-[1.02] tracking-tight sm:max-w-[13ch] sm:text-[3.35rem] lg:max-w-[10ch] lg:text-[4.5rem]">
                            {{ $hero['subtitle'] }}
                        </h1>

                        <p class="mt-4 max-w-md text-sm font-medium leading-7 text-slate-200 sm:mt-5 sm:text-base lg:max-w-xl lg:text-lg">
                            Прегледај активни огласи и најди флексибилен ангажман со чисто, брзо пребарување од телефон.
                        </p>

                        <a href="#home-search-card" class="mt-8 inline-flex h-12 w-12 items-center justify-center rounded-full border border-white/15 bg-white/10 text-white shadow-lg shadow-slate-950/30 backdrop-blur transition hover:bg-white/15 lg:mt-10" aria-label="Оди до пребарување">
                            <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                                <path fill-rule="evenodd" d="M10 14.707l5.354-5.353-1.414-1.415L10 11.879 6.06 7.94 4.646 9.354 10 14.707z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <form id="home-search-card" method="GET" action="{{ route('jobs.index') }}" class="relative z-10 mx-auto -mt-12 max-w-5xl rounded-[1.9rem] border border-slate-200/80 bg-white p-5 shadow-[0_28px_60px_-30px_rgba(15,23,42,0.4)] sm:-mt-16 sm:rounded-[2rem] sm:p-6 lg:-mt-20 lg:p-7">
                <div class="space-y-4">
                    <div class="text-center lg:text-left">
                        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-emerald-600">Брзо пребарување</p>
                        <h2 class="mt-2 text-2xl font-bold tracking-tight text-slate-900 sm:text-[2rem]">Пронајди оглас за неколку секунди</h2>
                    </div>

                    <div class="grid gap-3 lg:grid-cols-[1.55fr_0.95fr_0.95fr_auto]">
                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Позиција / клучен збор</span>
                            <input
                                name="q"
                                type="text"
                                value="{{ request('q') }}"
                                placeholder="Пр. промотер, магационер..."
                                class="h-14 w-full rounded-[1.15rem] border border-slate-200 bg-slate-50 px-4 text-sm font-medium text-slate-900 outline-none transition placeholder:font-normal placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-100"
                            >
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Локација</span>
                            <input
                                name="city"
                                type="text"
                                value="{{ request('city') }}"
                                placeholder="Пр. Скопје"
                                class="h-14 w-full rounded-[1.15rem] border border-slate-200 bg-slate-50 px-4 text-sm font-medium text-slate-900 outline-none transition placeholder:font-normal placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-100"
                            >
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Категорија</span>
                            <select name="category" class="h-14 w-full rounded-[1.15rem] border border-slate-200 bg-slate-50 px-4 text-sm font-medium text-slate-600 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-100">
                                <option value="" @selected(request('category') === '')>Избери категорија</option>
                                @foreach ($searchCategories as $category)
                                    <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                                @endforeach
                            </select>
                        </label>

                        <div class="flex items-end">
                            <button
                                type="submit"
                                class="inline-flex h-14 w-full items-center justify-center rounded-[1.15rem] bg-emerald-600 px-8 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500 lg:min-w-[10rem]"
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
