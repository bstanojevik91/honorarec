@extends('layouts.app')

@section('content')
    <div class="relative isolate overflow-hidden bg-slate-950">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(16,185,129,0.18),transparent_36%),radial-gradient(circle_at_78%_18%,rgba(14,116,144,0.22),transparent_28%),linear-gradient(180deg,rgba(2,6,23,0.98)_0%,rgba(3,7,18,0.96)_48%,rgba(2,6,23,1)_100%)]"></div>
            <div class="absolute inset-0 opacity-[0.08]" style="background-image: linear-gradient(rgba(148,163,184,0.18) 1px, transparent 1px), linear-gradient(90deg, rgba(148,163,184,0.18) 1px, transparent 1px); background-size: 72px 72px;"></div>
            <div class="absolute left-1/2 top-12 h-72 w-72 -translate-x-1/2 rounded-full bg-emerald-500/10 blur-3xl"></div>
        </div>

        @include('partials.header')

        <section class="relative mx-auto max-w-7xl px-4 pb-14 pt-28 sm:px-6 sm:pb-20 sm:pt-32 lg:px-8 lg:pb-32 lg:pt-44">
            <div class="max-w-3xl text-white">
                <h1 class="max-w-2xl text-[2.15rem] font-extrabold tracking-tight sm:text-5xl lg:text-[4.1rem] lg:leading-[1.02]">{{ $hero['title'] }}</h1>
                <p class="mt-3 max-w-xl text-base font-medium leading-7 text-slate-300 sm:mt-4 sm:max-w-2xl sm:text-xl">{{ $hero['subtitle'] }}</p>
            </div>

            <form method="GET" action="{{ route('jobs.index') }}" class="mt-8 max-w-6xl rounded-[1.45rem] border border-white/10 bg-white/96 p-3 shadow-[0_34px_90px_-34px_rgba(15,23,42,0.85)] sm:mt-10 sm:rounded-[1.75rem] xl:p-4">
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-[1.55fr_0.9fr_0.9fr_auto]">
                    <label class="block">
                        <span class="sr-only">Клучен збор</span>
                        <input
                            name="q"
                            type="text"
                            placeholder="Клучен збор / назив на работа"
                            class="h-13 w-full rounded-[1rem] border border-slate-200 bg-white px-4 text-sm font-medium text-slate-900 outline-none transition placeholder:font-normal placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 sm:h-14 sm:rounded-[1.15rem] sm:col-span-2 lg:col-span-1"
                        >
                    </label>
                    <label class="block">
                        <span class="sr-only">Град</span>
                        <input
                            name="city"
                            type="text"
                            placeholder="Град"
                            class="h-13 w-full rounded-[1rem] border border-slate-200 bg-white px-4 text-sm font-medium text-slate-900 outline-none transition placeholder:font-normal placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 sm:h-14 sm:rounded-[1.15rem]"
                        >
                    </label>
                    <label class="block">
                        <span class="sr-only">Категорија</span>
                        <select name="category" class="h-13 w-full rounded-[1rem] border border-slate-200 bg-white px-4 text-sm font-medium text-slate-500 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 sm:h-14 sm:rounded-[1.15rem]">
                            <option value="" selected>Категорија</option>
                            @foreach ($searchCategories as $category)
                                <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </label>
                    <button
                        type="submit"
                        class="inline-flex h-13 w-full items-center justify-center rounded-[1rem] bg-emerald-600 px-8 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500 sm:h-14 sm:rounded-[1.15rem] sm:col-span-2 lg:col-span-1"
                    >
                        Пребарувај
                    </button>
                </div>
            </form>
        </section>
    </div>

    <main>
        <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8 lg:py-20">
            <div>
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
                <div class="text-center">
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
                <div>
                    <h2 class="max-w-xl text-2xl font-bold tracking-tight sm:text-[2.45rem] sm:leading-tight">{{ $promo['title'] }}</h2>
                    <div class="mt-6 space-y-3 sm:mt-8 sm:space-y-4">
                        @foreach ($promo['points'] as $point)
                            <div class="flex items-start gap-3 text-sm text-slate-200 sm:items-center sm:text-base">
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
                <div class="text-center">
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
                <div class="text-center">
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
