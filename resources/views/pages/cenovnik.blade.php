@extends('layouts.app')

@section('content')
    @php
        $packages = [
            [
                'name' => 'Basic',
                'price' => '2.590 ден.',
                'duration' => 'за еден оглас',
                'badge' => null,
                'highlighted' => false,
                'badgeClass' => '',
                'benefits' => [
                    '1 оглас активен 30 дена',
                    'Директни апликации од кандидати',
                    'Можност за директен телефонски контакт',
                    'Основна статистика за огласот',
                ],
            ],
            [
                'name' => 'Honorarec+',
                'price' => '5.990 ден.',
                'duration' => 'за период од 3 месеци',
                'badge' => 'НАЈПОПУЛАРЕН',
                'highlighted' => true,
                'badgeClass' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                'benefits' => [
                    '3 огласи во период од 3 месеци',
                    'Истакнување на огласите',
                    'Промоција преку социјалните мрежи на Honorarec',
                    'Статистика за апликации и телефонски повици',
                    'Подобра видливост пред релевантни кандидати',
                ],
            ],
            [
                'name' => 'Honorarec Partner',
                'price' => '15.990 ден.',
                'duration' => 'годишно',
                'badge' => 'PREMIUM',
                'highlighted' => false,
                'badgeClass' => 'bg-amber-50 text-amber-700 border-amber-200',
                'benefits' => [
                    '6 огласи во период од 12 месеци',
                    'Приоритетно истакнување на огласите',
                    'Промоција преку социјалните мрежи на Honorarec',
                    'Напредна статистика за огласите',
                    'Пристап до услуга за поврзување со релевантни кандидати',
                    'Приоритетна поддршка',
                ],
            ],
        ];
    @endphp

    <div id="honorarec-pricing-page" class="bg-stone-50">
        <div class="relative isolate overflow-hidden bg-slate-950">
            <div class="absolute inset-0">
                <div class="h-full w-full bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.18),_transparent_32%),linear-gradient(180deg,_rgba(2,6,23,0.78),_rgba(2,6,23,0.92))]"></div>
            </div>

            @include('partials.header')

            <section class="relative mx-auto max-w-7xl px-4 pb-14 pt-28 sm:px-6 sm:pb-18 sm:pt-32 lg:px-8 lg:pb-24 lg:pt-44">
                <div class="mx-auto max-w-4xl text-center text-white">
                    <p class="text-sm font-semibold uppercase tracking-[0.28em] text-emerald-300">За работодавачи</p>
                    <h1 class="mt-3 text-[2.15rem] font-extrabold tracking-tight sm:mt-4 sm:text-5xl">Ценовни пакети за работодавачи</h1>
                    <p class="mx-auto mt-3 max-w-3xl text-base leading-7 text-slate-200 sm:mt-4 sm:text-xl">
                        Изберете пакет според бројот на огласи и поддршката што ѝ е потребна на вашата компанија.
                    </p>
                </div>
            </section>
        </div>

        <main>
            <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 sm:py-14 lg:px-8 lg:py-20">
                <div class="grid gap-5 lg:grid-cols-3 lg:items-stretch">
                    @foreach ($packages as $package)
                        <article @class([
                            'flex h-full flex-col rounded-[1.6rem] border bg-white p-6 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.18)] sm:p-7',
                            'border-slate-200' => ! $package['highlighted'],
                            'border-emerald-200 ring-1 ring-emerald-100 shadow-[0_28px_52px_-32px_rgba(5,150,105,0.32)]' => $package['highlighted'],
                        ])>
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h2 class="text-2xl font-bold tracking-tight text-slate-900">{{ $package['name'] }}</h2>
                                    <p class="mt-3 text-4xl font-extrabold tracking-tight text-slate-900">{{ $package['price'] }}</p>
                                    <p class="mt-2 text-sm font-medium text-slate-500">{{ $package['duration'] }}</p>
                                </div>

                                @if ($package['badge'])
                                    <span class="inline-flex shrink-0 rounded-full border px-3 py-1 text-[0.68rem] font-bold uppercase tracking-[0.22em] {{ $package['badgeClass'] }}">
                                        {{ $package['badge'] }}
                                    </span>
                                @endif
                            </div>

                            <ul class="mt-6 space-y-3 text-sm leading-7 text-slate-600 sm:text-[0.95rem]">
                                @foreach ($package['benefits'] as $benefit)
                                    <li class="flex items-start gap-3">
                                        <span class="mt-1 inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">
                                            <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 010 1.42l-7.2 7.2a1 1 0 01-1.415 0l-3-3a1 1 0 111.414-1.42l2.293 2.294 6.493-6.494a1 1 0 011.415 0z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                        <span>{{ $benefit }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="mt-8 pt-2">
                                <a
                                    href="#pricing-contact"
                                    class="inline-flex w-full items-center justify-center rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-[0_18px_32px_-16px_rgba(5,150,105,0.6)] transition hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500"
                                >
                                    Избери пакет
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <section id="pricing-contact" class="mt-8 rounded-[1.8rem] border border-slate-200 bg-white p-6 text-center shadow-[0_20px_45px_-34px_rgba(15,23,42,0.18)] sm:mt-10 sm:p-8 lg:p-10">
                    <p class="text-[0.72rem] font-semibold uppercase tracking-[0.26em] text-emerald-600">Контакт</p>
                    <h2 class="mt-2 text-2xl font-bold tracking-tight text-slate-900 sm:text-[1.9rem]">
                        Доколку сте заинтересирани за некој од пакетите, контактирајте нè на 070 214 325.
                    </h2>
                    <a
                        href="tel:+38970214325"
                        class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-slate-900 px-6 py-3.5 text-base font-semibold text-white shadow-[0_18px_34px_-18px_rgba(15,23,42,0.45)] transition hover:bg-slate-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 sm:w-auto sm:min-w-[15rem]"
                    >
                        Јавете се: 070 214 325
                    </a>
                    <p class="mt-4 text-sm leading-7 text-slate-500">
                        Пакетите во моментов се активираат по директен контакт.
                    </p>
                </section>
            </section>
        </main>

        @include('partials.footer')
    </div>
@endsection
