@extends('layouts.app')

@section('content')
    @php
        $shareUrl = urlencode(request()->fullUrl());
        $facebookShareUrl = 'https://www.facebook.com/sharer/sharer.php?u='.$shareUrl;
        $linkedinShareUrl = 'https://www.linkedin.com/sharing/share-offsite/?url='.$shareUrl;
    @endphp

    <div class="relative isolate overflow-hidden bg-slate-950">
        <div class="absolute inset-0">
            <div class="h-full w-full bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.18),_transparent_34%),linear-gradient(180deg,_rgba(2,6,23,0.8),_rgba(2,6,23,0.94))]"></div>
        </div>

        @include('partials.header')

        <section class="relative mx-auto max-w-7xl px-4 pb-20 pt-36 sm:px-6 lg:px-8 lg:pb-24 lg:pt-44">
            <div class="max-w-4xl text-white">
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-emerald-300">{{ $job['company'] }}</p>
                <h1 class="mt-4 text-4xl font-extrabold tracking-tight sm:text-5xl">{{ $job['title'] }}</h1>
                <div class="mt-6 flex flex-wrap items-center gap-3 text-sm text-slate-200">
                    <span class="rounded-full bg-orange-100 px-3 py-1 font-semibold text-orange-700">{{ $job['badge'] }}</span>
                    <span>{{ $job['category'] }}</span>
                    <span class="h-1 w-1 rounded-full bg-slate-400"></span>
                    <span>{{ $job['location'] }}</span>
                </div>
                <div class="mt-8">
                    <a href="#apply-form" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-7 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
                        Аплицирај за огласот
                    </a>
                </div>

                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <span class="text-sm font-medium text-slate-300">Сподели оглас:</span>
                    <a href="{{ $facebookShareUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/10">
                        <svg viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4" aria-hidden="true">
                            <path d="M13.5 21v-7h2.4l.4-3h-2.8V9.3c0-.9.3-1.5 1.6-1.5H16V5.1c-.3 0-1.1-.1-2.1-.1-2.1 0-3.5 1.3-3.5 3.8V11H8v3h2.3v7h3.2z" />
                        </svg>
                        Facebook
                    </a>
                    <a href="{{ $linkedinShareUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/10">
                        <svg viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4" aria-hidden="true">
                            <path d="M6.94 8.5A1.56 1.56 0 105.38 6.94 1.56 1.56 0 006.94 8.5zM5.56 10h2.77v8.44H5.56zm4.5 0h2.65v1.15h.04a2.9 2.9 0 012.61-1.43c2.79 0 3.3 1.84 3.3 4.22V18.4h-2.77v-3.96c0-.94-.02-2.15-1.31-2.15s-1.51 1.02-1.51 2.08v4.03h-2.77z" />
                        </svg>
                        LinkedIn
                    </a>
                </div>
            </div>
        </section>
    </div>

    <main class="bg-stone-50">
        <section class="mx-auto grid max-w-6xl gap-8 px-4 py-16 sm:px-6 sm:py-20 lg:grid-cols-[1.15fr_0.85fr] lg:px-8">
            <div class="rounded-[1.6rem] border border-slate-200 bg-white p-8 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.28)]">
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-600">Преглед на оглас</p>
                <p class="mt-5 text-base leading-8 text-slate-600">
                    {{ $job['description'] ?? 'Оглас за краткорочен или сезонски ангажман. Погледнете ги основните информации и аплицирајте директно преку формата.' }}
                </p>

                <div class="mt-8 grid gap-4 rounded-[1.25rem] bg-stone-50 p-6 sm:grid-cols-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Компанија</p>
                        <p class="mt-2 text-base font-semibold text-slate-900">{{ $job['company'] }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Категорија</p>
                        <p class="mt-2 text-base font-semibold text-slate-900">{{ $job['category'] ?: 'Не е наведено' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Локација</p>
                        <p class="mt-2 text-base font-semibold text-slate-900">{{ $job['location'] ?: 'Не е наведено' }}</p>
                    </div>
                    <div class="sm:col-span-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Дневница</p>
                        <p class="mt-2 text-base font-semibold text-slate-900">
                            @if(!empty($job['daily_pay']))
                                {{ number_format((float) $job['daily_pay'], 0, ',', '.') }} денари
                            @else
                                По договор
                            @endif
                        </p>
                    </div>
                </div>

                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('jobs.index') }}" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-7 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
                        Назад кон сите огласи
                    </a>
                    <a href="{{ route('faq') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-7 py-3 text-sm font-semibold text-slate-700 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">
                        Погледни ЧПП
                    </a>
                </div>
            </div>

            <div id="apply-form" class="rounded-[1.6rem] border border-slate-200 bg-white p-8 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.28)]">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-600">Апликација</p>
                    <h2 class="mt-3 text-2xl font-bold tracking-tight text-slate-900">Аплицирај за оваа позиција</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        Пополнете ги основните податоци и испратете порака до работодавачот.
                    </p>
                </div>

                @if (session('application_status'))
                    <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                        {{ session('application_status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if ($applicationEnabled)
                    <form method="POST" action="{{ route('jobs.apply', $job['slug']) }}" enctype="multipart/form-data" class="mt-6 space-y-5">
                        @csrf
                        <div>
                            <label for="full_name" class="mb-2 block text-sm font-semibold text-slate-700">Име и презиме</label>
                            <input id="full_name" name="full_name" type="text" value="{{ old('full_name') }}" required class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="phone" class="mb-2 block text-sm font-semibold text-slate-700">Телефон</label>
                                <input id="phone" name="phone" type="text" value="{{ old('phone') }}" required class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">
                            </div>
                            <div>
                                <label for="city" class="mb-2 block text-sm font-semibold text-slate-700">Град</label>
                                <input id="city" name="city" type="text" value="{{ old('city') }}" required class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">
                            </div>
                        </div>

                        <div>
                            <label for="message" class="mb-2 block text-sm font-semibold text-slate-700">Порака</label>
                            <textarea id="message" name="message" rows="5" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">{{ old('message') }}</textarea>
                        </div>

                        <div>
                            <label for="cv" class="mb-2 block text-sm font-semibold text-slate-700">CV / Биографија</label>
                            <input id="cv" name="cv" type="file" accept=".pdf,.doc,.docx" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">
                            <p class="mt-2 text-xs text-slate-500">Дозволени формати: PDF, DOC, DOCX. Максимум 5MB.</p>
                        </div>

                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-600 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
                            Испрати апликација
                        </button>
                    </form>
                @else
                    <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-4 text-sm leading-7 text-amber-800">
                        Формата за аплицирање ќе биде активна кога овој оглас ќе биде поврзан со базата и достапен за прием на апликации.
                    </div>
                @endif
            </div>
        </section>
    </main>

    @include('partials.footer')
@endsection
