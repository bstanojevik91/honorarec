@extends('layouts.app')

@section('content')
    <div class="relative isolate overflow-hidden bg-slate-950">
        <div class="absolute inset-0">
            <div class="h-full w-full bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.18),_transparent_32%),linear-gradient(180deg,_rgba(2,6,23,0.78),_rgba(2,6,23,0.92))]"></div>
        </div>

        @include('partials.header')

        <section class="relative mx-auto max-w-7xl px-4 pb-20 pt-36 sm:px-6 lg:px-8 lg:pb-24 lg:pt-44">
            <div class="max-w-3xl text-white">
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-emerald-300">Поддршка</p>
                <h1 class="mt-4 text-4xl font-extrabold tracking-tight sm:text-5xl">Често поставувани прашања</h1>
                <p class="mt-4 text-lg text-slate-200 sm:text-xl">
                    Одговори на најчестите прашања поврзани со огласите, аплицирањето и користењето на Honorarec.mk.
                </p>
            </div>
        </section>
    </div>

    <main class="bg-stone-50">
        <section class="mx-auto max-w-5xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
            <div class="space-y-4">
                @foreach ($faqs as $faq)
                    <details class="group rounded-[1.5rem] border border-slate-200 bg-white p-6 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.28)]">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-6 text-left text-lg font-bold text-slate-900">
                            <span>{{ $faq['question'] }}</span>
                            <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-700 transition group-open:rotate-45">
                                <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5" aria-hidden="true">
                                    <path d="M10 4a1 1 0 011 1v4h4a1 1 0 110 2h-4v4a1 1 0 11-2 0v-4H5a1 1 0 110-2h4V5a1 1 0 011-1z" />
                                </svg>
                            </span>
                        </summary>
                        <p class="mt-4 max-w-3xl pr-10 text-sm leading-7 text-slate-600">
                            {{ $faq['answer'] }}
                        </p>
                    </details>
                @endforeach
            </div>
        </section>
    </main>

    @include('partials.footer')
@endsection
