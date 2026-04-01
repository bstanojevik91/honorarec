@extends('layouts.app')

@section('content')
    <div class="relative isolate overflow-hidden bg-slate-950">
        <div class="absolute inset-0">
            <img
                src="{{ $hero['image'] }}"
                alt="Хонорарец херој слика"
                class="h-full w-full object-cover"
            >
            <div class="absolute inset-0 bg-slate-950/75"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-slate-950/35 via-slate-950/72 to-stone-50"></div>
        </div>

        @include('partials.header')

        <section class="relative mx-auto max-w-7xl px-4 pb-24 pt-36 sm:px-6 sm:pb-28 lg:px-8 lg:pb-32 lg:pt-44">
            <div class="max-w-3xl text-white">
                <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-[4.1rem] lg:leading-[1.02]">{{ $hero['title'] }}</h1>
                <p class="mt-4 text-lg font-medium text-slate-200 sm:text-xl">{{ $hero['subtitle'] }}</p>
            </div>

            <form method="GET" action="{{ route('jobs.index') }}" class="mt-10 max-w-6xl rounded-[1.75rem] border border-black/5 bg-white/95 p-3 shadow-[0_30px_80px_-30px_rgba(2,6,23,0.65)] backdrop-blur xl:p-4">
                <div class="grid gap-3 lg:grid-cols-[1.55fr_0.9fr_0.9fr_auto]">
                    <label class="block">
                        <span class="sr-only">Клучен збор</span>
                        <input
                            name="q"
                            type="text"
                            placeholder="Клучен збор / назив на работа"
                            class="h-14 w-full rounded-[1.15rem] border border-slate-200 bg-white px-4 text-sm font-medium text-slate-900 outline-none transition placeholder:font-normal placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                        >
                    </label>
                    <label class="block">
                        <span class="sr-only">Град</span>
                        <input
                            name="city"
                            type="text"
                            placeholder="Град"
                            class="h-14 w-full rounded-[1.15rem] border border-slate-200 bg-white px-4 text-sm font-medium text-slate-900 outline-none transition placeholder:font-normal placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                        >
                    </label>
                    <label class="block">
                        <span class="sr-only">Категорија</span>
                        <select name="category" class="h-14 w-full rounded-[1.15rem] border border-slate-200 bg-white px-4 text-sm font-medium text-slate-500 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            <option value="" selected>Категорија</option>
                            @foreach ($searchCategories as $category)
                                <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </label>
                    <button
                        type="submit"
                        class="inline-flex h-14 items-center justify-center rounded-[1.15rem] bg-emerald-600 px-8 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500"
                    >
                        Пребарувај
                    </button>
                </div>
            </form>
        </section>
    </div>

    <main>
        <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-[2rem]">Последно додадени огласи:</h2>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-3">
                @foreach ($jobs as $job)
                    @include('partials.job-card', ['job' => $job])
                @endforeach
            </div>

            <div class="mt-9 text-center">
                <a href="{{ route('jobs.index') }}" class="inline-flex min-w-44 items-center justify-center rounded-full bg-emerald-600 px-7 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
                    Повеќе огласи
                </a>
            </div>
        </section>

        <section class="bg-white py-16 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-[2rem]">Категории</h2>
                </div>

                <div class="mt-8 grid gap-6 md:grid-cols-2">
                    @foreach ($categories as $category)
                        @include('partials.category-card', ['category' => $category])
                    @endforeach
                </div>

                <div class="mt-9 text-center">
                    <a href="{{ route('jobs.index') }}" class="inline-flex min-w-44 items-center justify-center rounded-full bg-emerald-600 px-7 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
                        Сите Категории
                    </a>
                </div>
            </div>
        </section>

        <section class="bg-slate-950 py-16 text-white sm:py-20">
            <div class="mx-auto grid max-w-7xl gap-12 px-4 sm:px-6 lg:grid-cols-[1fr_1fr] lg:items-center lg:px-8">
                <div>
                    <h2 class="max-w-xl text-3xl font-bold tracking-tight sm:text-[2.45rem] sm:leading-tight">{{ $promo['title'] }}</h2>
                    <div class="mt-8 space-y-4">
                        @foreach ($promo['points'] as $point)
                            <div class="flex items-center gap-3 text-base text-slate-200">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500/15 text-emerald-300">
                                    <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 010 1.42l-7.2 7.2a1 1 0 01-1.415 0l-3-3a1 1 0 111.414-1.42l2.293 2.294 6.493-6.494a1 1 0 011.415 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                {{ $point }}
                            </div>
                        @endforeach
                    </div>

                    <a href="{{ route('jobs.index') }}" class="mt-10 inline-flex min-w-40 items-center justify-center rounded-full bg-emerald-600 px-7 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-950/30 transition hover:bg-emerald-500">
                        Види огласи
                    </a>
                </div>

                <div class="relative mx-auto w-full max-w-xl lg:mr-0">
                    <div class="overflow-hidden rounded-[2rem] border border-white/10 shadow-[0_28px_80px_-32px_rgba(0,0,0,0.75)]">
                        <img src="{{ $promo['primary_image'] }}" alt="Работна средина" class="h-[24rem] w-full object-cover sm:h-[26rem]">
                    </div>
                    <div class="absolute -bottom-6 -left-4 hidden w-56 overflow-hidden rounded-[1.5rem] border-4 border-slate-950 shadow-[0_24px_50px_-24px_rgba(0,0,0,0.9)] sm:block md:w-64">
                        <img src="{{ $promo['secondary_image'] }}" alt="Тимска работа" class="h-40 w-full object-cover md:h-44">
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-stone-50 py-16 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-[2rem]">Нашите задоволни корисници 😍</h2>
                    <p class="mt-3 text-base text-slate-600">Прочитајте дел од рецензиите!</p>
                </div>

                <div class="mt-8 grid gap-6 lg:grid-cols-3">
                    @foreach ($testimonials as $testimonial)
                        @include('partials.testimonial-card', ['testimonial' => $testimonial])
                    @endforeach
                </div>
            </div>
        </section>

        <section class="bg-white py-16 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-[2rem]">Нашиот блог</h2>
                    <p class="mt-3 text-base text-slate-600">Прочитајте некои интересни статии кои може да ви помогнат.</p>
                </div>

                <div class="mt-8 grid gap-6 lg:grid-cols-3">
                    @foreach ($posts as $post)
                        @include('partials.blog-card', ['post' => $post])
                    @endforeach
                </div>
            </div>
        </section>
    </main>

    @include('partials.footer')
@endsection
