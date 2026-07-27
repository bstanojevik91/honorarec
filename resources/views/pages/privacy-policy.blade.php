@extends('layouts.app')

@section('content')
    @php
        $controllerName = config('privacy.controller_name');
        $controllerDescription = config('privacy.controller_description');
        $controllerCountry = config('privacy.controller_country');
        $contactEmail = config('privacy.contact_email');
        $policyVersion = config('privacy.policy_version');
        $lastUpdated = config('privacy.last_updated');
        $policySections = [
            [
                'title' => 'Вовед',
                'body' => [
                    'Honorarec.mk ја почитува приватноста на кандидатите, работодавачите и посетителите на платформата. Оваа Политика за приватност објаснува кои лични податоци ги обработуваме, за кои цели ги користиме, со кого може да бидат споделени, колку долго се потребни и кои права ги имаат корисниците.',
                ],
            ],
            [
                'title' => 'Контролор на личните податоци',
                'body' => [
                    'По регистрацијата на правното лице, информациите за контролорот во оваа Политика ќе бидат соодветно ажурирани.',
                ],
            ],
            [
                'title' => 'Кои лични податоци ги собираме',
                'body' => [
                    'При аплицирање на оглас може да ги обработуваме личните податоци што кандидатот сам ги внесува или прикачува преку формата на платформата.',
                    'Кандидатите треба да избегнуваат внесување непотребни чувствителни лични податоци во пораката или во CV-то.',
                ],
                'list' => [
                    'име и презиме;',
                    'телефонски број;',
                    'град или место;',
                    'порака до работодавачот;',
                    'приложено CV, доколку кандидатот избере да го прикачи;',
                    'огласот на кој кандидатот аплицирал;',
                    'датум и време на апликацијата;',
                    'основни технички и безбедносни податоци потребни за функционирање и заштита на платформата.',
                ],
            ],
            [
                'title' => 'Цели на обработката',
                'list' => [
                    'поднесување на апликацијата до избраниот работодавач;',
                    'овозможување работодавачот да ја прегледа апликацијата;',
                    'овозможување работодавачот да стапи во контакт со кандидатот;',
                    'приказ на апликацијата во соодветниот employer панел;',
                    'испраќање известување за нова апликација до работодавачот;',
                    'обезбедување техничка поддршка;',
                    'спречување дупликат апликации, спам, измама и злоупотреба;',
                    'одржување на безбедноста и доверливоста на Honorarec.mk;',
                    'одговор на барања поврзани со приватноста;',
                    'исполнување применливи законски обврски кога тоа е потребно.',
                ],
            ],
            [
                'title' => 'Доставување на апликацијата до работодавачот',
                'body' => [
                    'Кога кандидатот аплицира на конкретен оглас, внесените податоци и, доколку е приложено, CV-то се доставуваат до компанијата која го објавила тој оглас. Податоците не се доставуваат автоматски до сите компании на платформата.',
                    'Откако работодавачот ќе ја прими апликацијата, тој е одговорен за понатамошното користење, заштита и чување на податоците во согласност со своите законски обврски и правила за приватност.',
                ],
            ],
            [
                'title' => 'Даватели на технички услуги',
                'body' => [
                    'Ограничен дел од личните податоци може да биде обработуван од технички даватели на услуги кои се потребни за функционирање на платформата, како што се даватели на хостинг и инфраструктура, услуги за испорака на е-пошта, безбедносни услуги, резервни копии кога се користат и техничко одржување.',
                    'Honorarec.mk не ги продава личните податоци на кандидатите.',
                ],
            ],
            [
                'title' => 'Чување на личните податоци',
                'body' => [
                    'Податоците од апликацијата се чуваат само додека се потребни за спроведување на конкретната постапка за избор, функционирање на профилот на работодавачот, решавање на евентуални барања и исполнување на применливите законски обврски. Податоците треба да бидат избришани или анонимизирани кога повеќе не се потребни за целите за кои биле собрани.',
                    'Кандидатот може да побара бришење или информација за својата апликација преку контакт на '.$contactEmail.'.',
                ],
            ],
            [
                'title' => 'Безбедност на податоците',
                'body' => [
                    'Honorarec.mk применува разумни технички и организациски мерки за заштита на личните податоци, вклучувајќи ограничен пристап до кориснички сметки, автентикација на employer сметки, server-side валидација, CSRF заштита, заштита од дупликат апликации и контролиран пристап до employer панелите.',
                    'И покрај тоа, ниту еден систем поврзан со интернет не може да се смета за целосно безбеден.',
                ],
            ],
            [
                'title' => 'Права на корисниците',
                'body' => [
                    'Корисниците може да побараат, кога е применливо, потврда дали се обработуваат нивни лични податоци, пристап до тие податоци, исправка на неточни или нецелосни податоци, бришење, ограничување на одредена обработка, приговор на одредена обработка и информација за примателот на нивната апликација.',
                    'Барањата поврзани со приватност може да се испратат на '.$contactEmail.'.',
                ],
            ],
            [
                'title' => 'Приговор до надлежен орган',
                'body' => [
                    'Ако сметате дека личните податоци се обработуваат спротивно на применливите прописи за заштита на личните податоци, може да се обратите или да поднесете претставка до Агенцијата за заштита на личните податоци на Република Северна Македонија.',
                ],
            ],
            [
                'title' => 'Колачиња и технички податоци',
                'body' => [
                    'Honorarec.mk користи технички механизми неопходни за правилно функционирање и заштита на платформата, како што се сесиски колачиња, CSRF заштита, колачиња за најава кај employer сметки и серверски логови потребни за безбедност и работа на системот.',
                    'Платформата може да користи и аналитички алатки за основно мерење на посетеноста и користењето на страниците, со цел подобрување на функционалностите и корисничкото искуство.',
                ],
            ],
            [
                'title' => 'Измени на Политиката',
                'body' => [
                    'Оваа Политика може повремено да биде ажурирана поради промени во функционалностите на платформата, начинот на обработка или применливите прописи. Ажурираната верзија ќе биде објавена на оваа страница со нов датум и број на верзија.',
                ],
            ],
            [
                'title' => 'Контакт',
                'body' => [
                    'За прашања, барања за пристап, исправка или бришење на личните податоци, контактирајте нè на:',
                ],
            ],
        ];
    @endphp

    <div class="relative isolate overflow-hidden bg-slate-950">
        <div class="absolute inset-0">
            <div class="h-full w-full bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.18),_transparent_32%),linear-gradient(180deg,_rgba(2,6,23,0.78),_rgba(2,6,23,0.92))]"></div>
        </div>

        @include('partials.header')

        <section class="relative mx-auto max-w-7xl px-4 pb-14 pt-28 sm:px-6 sm:pb-18 sm:pt-32 lg:px-8 lg:pb-24 lg:pt-44">
            <div class="mx-auto max-w-4xl text-center text-white">
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-emerald-300">Приватност</p>
                <h1 class="mt-3 break-words text-[2.15rem] font-extrabold tracking-tight sm:mt-4 sm:text-5xl">Политика за приватност</h1>
                <p class="mx-auto mt-3 max-w-3xl break-words text-base leading-7 text-slate-200 sm:mt-4 sm:text-xl">
                    Информации за тоа како Honorarec.mk ги собира, користи, доставува и чува личните податоци.
                </p>

                <div class="mt-6 flex flex-col items-center justify-center gap-3 text-sm text-slate-200 sm:flex-row sm:flex-wrap">
                    <span class="rounded-full border border-white/10 bg-white/5 px-4 py-2">Последно ажурирање: {{ $lastUpdated }}</span>
                    <span class="rounded-full border border-white/10 bg-white/5 px-4 py-2">Верзија: {{ $policyVersion }}</span>
                </div>
            </div>
        </section>
    </div>

    <main class="bg-stone-50">
        <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 sm:py-14 lg:px-8 lg:py-20">
            <div class="mx-auto max-w-4xl space-y-6 break-words">
                <div class="rounded-[1.45rem] border border-slate-200 bg-white p-5 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.18)] sm:rounded-[1.7rem] sm:p-8">
                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-[1.2rem] border border-slate-200 bg-slate-50 px-4 py-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Контролор</p>
                            <p class="mt-2 text-sm font-bold text-slate-900">{{ $controllerName }}</p>
                        </div>
                        <div class="rounded-[1.2rem] border border-slate-200 bg-slate-50 px-4 py-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Статус</p>
                            <p class="mt-2 text-sm font-medium text-slate-700">{{ $controllerDescription }}</p>
                        </div>
                        <div class="rounded-[1.2rem] border border-slate-200 bg-slate-50 px-4 py-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Држава</p>
                            <p class="mt-2 text-sm font-medium text-slate-700">{{ $controllerCountry }}</p>
                        </div>
                        <div class="rounded-[1.2rem] border border-slate-200 bg-slate-50 px-4 py-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Е-пошта</p>
                            <a href="mailto:{{ $contactEmail }}" class="mt-2 inline-flex break-all text-sm font-semibold text-emerald-700 underline decoration-emerald-300 underline-offset-4 transition hover:text-emerald-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500">
                                {{ $contactEmail }}
                            </a>
                        </div>
                    </div>
                </div>

                @foreach ($policySections as $index => $section)
                    <section class="rounded-[1.45rem] border border-slate-200 bg-white p-5 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.18)] sm:rounded-[1.7rem] sm:p-8">
                        <div class="max-w-3xl">
                            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-600">Секција {{ $index + 1 }}</p>
                            <h2 class="mt-3 text-2xl font-bold tracking-tight text-slate-900 sm:text-[2rem]">{{ $section['title'] }}</h2>
                        </div>

                        @if ($section['title'] === 'Контролор на личните податоци')
                            <div class="mt-6 grid gap-4 md:grid-cols-2">
                                <div class="rounded-[1.2rem] border border-slate-200 bg-slate-50 px-5 py-4">
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Име</p>
                                    <p class="mt-2 text-base font-bold text-slate-900">{{ $controllerName }}</p>
                                </div>
                                <div class="rounded-[1.2rem] border border-slate-200 bg-slate-50 px-5 py-4">
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Опис</p>
                                    <p class="mt-2 text-base text-slate-700">{{ $controllerDescription }}</p>
                                </div>
                                <div class="rounded-[1.2rem] border border-slate-200 bg-slate-50 px-5 py-4">
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Држава</p>
                                    <p class="mt-2 text-base text-slate-700">{{ $controllerCountry }}</p>
                                </div>
                                <div class="rounded-[1.2rem] border border-slate-200 bg-slate-50 px-5 py-4">
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Контакт за приватност</p>
                                    <a href="mailto:{{ $contactEmail }}" class="mt-2 inline-flex break-all text-base font-semibold text-emerald-700 underline decoration-emerald-300 underline-offset-4 transition hover:text-emerald-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500">
                                        {{ $contactEmail }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="mt-6 max-w-3xl space-y-4 text-sm leading-7 text-slate-600 sm:text-base sm:leading-8">
                            @foreach ($section['body'] ?? [] as $paragraph)
                                @if ($section['title'] === 'Контакт' && $loop->last)
                                    <div class="rounded-[1.2rem] border border-emerald-100 bg-emerald-50 px-5 py-4">
                                        <p>{{ $paragraph }}</p>
                                        <a href="mailto:{{ $contactEmail }}" class="mt-3 inline-flex break-all text-base font-semibold text-emerald-800 underline decoration-emerald-300 underline-offset-4 transition hover:text-emerald-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500">
                                            {{ $contactEmail }}
                                        </a>
                                    </div>
                                @else
                                    <p>{{ $paragraph }}</p>
                                @endif
                            @endforeach
                        </div>

                        @if (! empty($section['list']))
                            <ul class="mt-6 max-w-3xl space-y-3 text-sm leading-7 text-slate-600 sm:text-base sm:leading-8">
                                @foreach ($section['list'] as $item)
                                    <li class="flex items-start gap-3">
                                        <span class="mt-2 h-2.5 w-2.5 shrink-0 rounded-full bg-emerald-500"></span>
                                        <span>{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </section>
                @endforeach
            </div>
        </section>
    </main>

    @include('partials.footer')
@endsection
