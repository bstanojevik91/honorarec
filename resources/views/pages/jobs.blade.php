@extends('layouts.app')

@section('content')
    <div class="relative isolate overflow-hidden bg-slate-950">
        <div class="absolute inset-0">
            <div class="h-full w-full bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.18),_transparent_34%),linear-gradient(180deg,_rgba(2,6,23,0.8),_rgba(2,6,23,0.94))]"></div>
        </div>

        @include('partials.header')

        <section class="relative mx-auto max-w-7xl px-4 pb-20 pt-36 sm:px-6 lg:px-8 lg:pb-24 lg:pt-44">
            <div class="max-w-3xl text-white">
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-emerald-300">Огласи</p>
                <h1 class="mt-4 text-4xl font-extrabold tracking-tight sm:text-5xl">Сите огласи</h1>
                <p class="mt-4 text-lg text-slate-200 sm:text-xl">
                    Прегледај ги достапните огласи и пронајди ангажман што одговара на твоето време, локација и интереси.
                </p>
            </div>
        </section>
    </div>

    <main class="bg-stone-50">
        <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
            <div class="rounded-[1.6rem] border border-slate-200 bg-white p-5 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.18)] sm:p-6">
                <form method="GET" action="{{ route('jobs.index') }}" class="space-y-5">
                    <div class="grid gap-4 lg:grid-cols-[1.45fr_1fr_1fr_1fr_auto]">
                    <div>
                        <label for="q" class="mb-2 block text-sm font-semibold text-slate-700">Клучен збор / назив на работа</label>
                        <input id="q" name="q" type="text" value="{{ $filters['q'] }}" placeholder="Пр. промотер, магационер..." class="h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                    </div>
                    <div>
                        <label for="city" class="mb-2 block text-sm font-semibold text-slate-700">Град</label>
                        <input id="city" name="city" type="text" value="{{ $filters['city'] }}" placeholder="Пр. Скопје" class="h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                    </div>
                    <div>
                        <label for="category" class="mb-2 block text-sm font-semibold text-slate-700">Категорија</label>
                        <select id="category" name="category" class="h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            <option value="">Сите категории</option>
                            @foreach ($availableCategories as $category)
                                <option value="{{ $category }}" @selected($filters['category'] === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="engagement_type" class="mb-2 block text-sm font-semibold text-slate-700">Вид на работен ангажман</label>
                        <select id="engagement_type" name="engagement_type" class="h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            <option value="">Сите типови</option>
                            @foreach ($engagementTypes as $type)
                                <option value="{{ $type }}" @selected($filters['engagement_type'] === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-3">
                        <button type="submit" class="inline-flex h-12 items-center justify-center rounded-2xl bg-emerald-600 px-6 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
                            Пребарувај
                        </button>
                        <a href="{{ route('jobs.index') }}" class="inline-flex h-12 items-center justify-center rounded-2xl border border-slate-200 px-5 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                            Исчисти
                        </a>
                    </div>
                    </div>

                    @if (count($availableTags) > 0)
                        <div>
                            <p class="mb-3 text-sm font-semibold text-slate-700">Тагови</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($availableTags as $tag)
                                    <label class="inline-flex cursor-pointer items-center">
                                        <input type="checkbox" name="tags[]" value="{{ $tag }}" class="peer sr-only" @checked(in_array($tag, $filters['tags'], true))>
                                        <span class="inline-flex rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition peer-checked:border-emerald-200 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 hover:border-slate-300 hover:bg-slate-50">
                                            {{ $tag }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </form>
            </div>

            <div class="mb-8 mt-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-[2rem]">Достапни работни ангажмани</h2>
                    <p class="mt-2 text-sm text-slate-600">Пронајдени се {{ count($jobs) }} огласи според избраните филтри.</p>
                </div>
            </div>

            @if (count($jobs) > 0)
                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($jobs as $job)
                        @include('partials.job-card', [
                            'job' => $job,
                            'showAction' => true,
                        ])
                    @endforeach
                </div>
            @else
                <div class="rounded-[1.6rem] border border-slate-200 bg-white px-6 py-12 text-center shadow-[0_20px_45px_-34px_rgba(15,23,42,0.18)]">
                    <h3 class="text-xl font-bold text-slate-900">Нема огласи за овие критериуми</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Пробај со друг клучен збор, друг град или избери поширока категорија.</p>
                    <a href="{{ route('jobs.index') }}" class="mt-6 inline-flex items-center justify-center rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
                        Прикажи ги сите огласи
                    </a>
                </div>
            @endif
        </section>
    </main>

    @include('partials.footer')
@endsection
