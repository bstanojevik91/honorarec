@extends('layouts.app')

@section('content')
    <div class="relative isolate overflow-hidden bg-slate-950">
        <div class="absolute inset-0">
            <div class="h-full w-full bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.18),_transparent_32%),linear-gradient(180deg,_rgba(2,6,23,0.78),_rgba(2,6,23,0.92))]"></div>
        </div>

        @include('partials.header')

        <section class="relative mx-auto max-w-7xl px-4 pb-14 pt-28 sm:px-6 sm:pb-18 sm:pt-32 lg:px-8 lg:pb-24 lg:pt-44">
            <div class="mx-auto max-w-3xl text-center text-white">
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-emerald-300">Поддршка</p>
                <h1 class="mt-3 text-[2.15rem] font-extrabold tracking-tight sm:mt-4 sm:text-5xl">Често поставувани прашања</h1>
                <p class="mt-3 text-base leading-7 text-slate-200 sm:mt-4 sm:text-xl">
                    Одговори на најчестите прашања поврзани со огласите, аплицирањето и користењето на Honorarec.mk.
                </p>
            </div>
        </section>
    </div>

    <main class="bg-stone-50">
        <section class="mx-auto max-w-5xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8 lg:py-20">
            <div class="space-y-3 sm:space-y-4">
                @foreach ($faqs as $faq)
                    <details class="group rounded-[1.35rem] border border-slate-200 bg-white p-5 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)] sm:rounded-[1.5rem] sm:p-6">
                        <summary class="flex cursor-pointer list-none items-start justify-between gap-4 text-left text-base font-bold leading-7 text-slate-900 sm:items-center sm:gap-6 sm:text-lg">
                            <span>{{ $faq['question'] }}</span>
                            <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-700 transition group-open:rotate-45">
                                <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5" aria-hidden="true">
                                    <path d="M10 4a1 1 0 011 1v4h4a1 1 0 110 2h-4v4a1 1 0 11-2 0v-4H5a1 1 0 110-2h4V5a1 1 0 011-1z" />
                                </svg>
                            </span>
                        </summary>
                        <p class="mt-4 max-w-3xl text-sm leading-7 text-slate-600 sm:pr-10">
                            {{ $faq['answer'] }}
                        </p>
                    </details>
                @endforeach
            </div>
        </section>
    </main>

    @include('partials.footer')
@endsection
