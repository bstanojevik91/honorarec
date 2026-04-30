@extends('layouts.app')

@section('content')
    @php
        $salaryLabel = array_key_exists('daily_pay', $job) && $job['daily_pay'] !== null && $job['daily_pay'] !== ''
            ? number_format((float) $job['daily_pay'], 0, ',', '.') . ' денари'
            : 'По договор';

        $shareUrl = urlencode(request()->fullUrl());
        $facebookShareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' . $shareUrl;
        $linkedinShareUrl = 'https://www.linkedin.com/sharing/share-offsite/?url=' . $shareUrl;
        $companyName = trim((string) ($job['company'] ?? 'Компанија'));
        $logoUrl = trim((string) ($job['logo'] ?? ''));
        $callPhone = $callPhone ?? ($job['call_phone'] ?? null);
        $callTrackingUrl = $callPhone ? route('jobs.track-call', $job['slug']) : null;

        $companyWords = preg_split('/\s+/u', $companyName, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $companyInitials = collect($companyWords)
            ->take(2)
            ->map(fn (string $word): string => mb_strtoupper(mb_substr($word, 0, 1)))
            ->implode('');
        $companyInitials = $companyInitials !== '' ? $companyInitials : 'HR';

        $jobDescription = trim((string) ($job['description'] ?? ''));
        $jobIntro = $jobDescription !== ''
            ? $jobDescription
            : 'Ова е одлична можност за ангажман преку кој можете брзо да започнете со работа и да остварите дополнителен приход со јасни задачи и директен контакт со работодавачот.';

        $responsibilities = [
            'Навремено извршување на секојдневните работни задачи.',
            'Комуникација со тимот и следење на договорените насоки.',
            'Одржување професионален однос кон клиенти, колеги и работна средина.',
        ];

        $requirements = [
            'Одговорност, точност и навремено доаѓање на работа.',
            'Подготвеност за работа во динамична средина.',
            'Добра комуникација и желба за соработка.',
        ];

        $offers = [
            'Исплата според договорената дневница или ангажман.',
            'Можност за брз почеток и флексибилна организација.',
            'Работа со реален работодавач и јасно дефинирани очекувања.',
        ];

        $trustItems = [
            [
                'title' => 'Брза апликација',
                'text' => 'Аплицирајте за неколку минути со основни податоци и кратка порака.',
            ],
            [
                'title' => 'Реални работодавачи',
                'text' => 'Огласите се објавуваат од компании што бараат конкретен ангажман.',
            ],
            [
                'title' => 'Одговор во краток рок',
                'text' => 'За најрелевантните кандидати работодавачот контактира директно.',
            ],
        ];

        $relatedJobs = collect([
            [
                'slug' => 'promoter-za-vikend-aktivnost',
                'logo' => 'https://placehold.co/96x96/eff6ff/166534?text=MK',
                'title' => 'Промотер за викенд активност',
                'badge' => 'Итно',
                'company' => 'Маркет Плус',
                'category' => 'Промоции',
                'location' => 'Скопје',
                'engagement_type' => 'За викенди',
                'tags' => ['Истакнато', 'Викенд', 'Флексибилно'],
            ],
            [
                'slug' => 'magacioner-za-sezonska-rabota',
                'logo' => 'https://placehold.co/96x96/fef2f2/9a3412?text=HR',
                'title' => 'Магационер за сезонска работа',
                'badge' => 'Дневница',
                'company' => 'Логистик Дооел',
                'category' => 'Магацин',
                'location' => 'Битола',
                'engagement_type' => 'Сезонска работа',
                'tags' => ['Магацин', 'Сезонско', 'Платено веднаш'],
            ],
            [
                'slug' => 'terenski-popisuvac',
                'logo' => 'https://placehold.co/96x96/f0fdf4/14532d?text=IT',
                'title' => 'Теренски попишувач',
                'badge' => 'Ново',
                'company' => 'Дата Фокус',
                'category' => 'Администрација',
                'location' => 'Тетово',
                'engagement_type' => 'На дневница',
                'tags' => ['Теренска работа', 'Брз почеток', 'Флексибилно'],
            ],
            [
                'slug' => 'pomosen-rabotnik-vo-ugostitelstvo',
                'logo' => 'https://placehold.co/96x96/ecfccb/3f6212?text=FO',
                'title' => 'Помошен работник во угостителство',
                'badge' => 'Викенд',
                'company' => 'Гастро Лајн',
                'category' => 'Угостителство',
                'location' => 'Охрид',
                'engagement_type' => 'За викенди',
                'tags' => ['Викенд', 'Без искуство', 'Брз почеток'],
            ],
        ])->reject(fn (array $relatedJob): bool => $relatedJob['slug'] === $job['slug'])->take(3)->values();
    @endphp

    <div class="relative isolate overflow-hidden bg-slate-950">
        <div class="absolute inset-0">
            <div class="h-full w-full bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.18),_transparent_34%),linear-gradient(180deg,_rgba(2,6,23,0.8),_rgba(2,6,23,0.94))]"></div>
        </div>

        @include('partials.header')

        <section class="relative mx-auto max-w-7xl px-4 pb-16 pt-36 sm:px-6 lg:px-8 lg:pb-20 lg:pt-44">
            <div class="grid gap-8 lg:grid-cols-[1.15fr_0.85fr] lg:items-end">
                <div class="max-w-4xl text-white">
                    <div class="flex flex-col gap-6 sm:gap-7 lg:flex-row lg:items-start lg:gap-8">
                        <div class="shrink-0">
                            <div class="mx-auto flex h-28 w-28 items-center justify-center rounded-[1.35rem] border border-slate-100/80 bg-white p-4 shadow-[0_16px_35px_-24px_rgba(15,23,42,0.85)] sm:mx-0 sm:h-32 sm:w-32 sm:rounded-[1.5rem] sm:p-5 lg:h-36 lg:w-36 lg:p-6">
                                @if ($logoUrl !== '')
                                    <img src="{{ $logoUrl }}" alt="{{ $companyName }}" class="h-full w-full object-contain" loading="eager" decoding="async">
                                @else
                                    <span class="text-3xl font-extrabold tracking-tight text-emerald-700 sm:text-[2rem]">{{ $companyInitials }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-3 text-sm">
                                <span class="rounded-full bg-orange-100 px-3 py-1 font-semibold text-orange-700">{{ $job['badge'] }}</span>
                                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 font-medium text-slate-100">{{ $job['category'] ?: 'Категорија' }}</span>
                                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 font-medium text-slate-100">{{ $companyName }}</span>
                            </div>

                            <h1 class="mt-5 max-w-4xl text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-[3.7rem] lg:leading-[1.03]">{{ $job['title'] }}</h1>

                            <div class="mt-6 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-slate-200 sm:text-base">
                                <div class="inline-flex items-center gap-2">
                                    <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5 text-emerald-300" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M9.69 18.933a1 1 0 01-1.38-.367C6.104 14.658 4 11.777 4 9a6 6 0 1112 0c0 2.777-2.104 5.658-4.31 9.566a1 1 0 01-1.38.367zM10 11.5A2.5 2.5 0 1010 6.5a2.5 2.5 0 000 5z" clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ $job['location'] ?: 'Локација по договор' }}</span>
                                </div>
                                <div class="inline-flex items-center gap-2">
                                    <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5 text-emerald-300" aria-hidden="true">
                                        <path d="M10 2a1 1 0 01.993.883L11 3v1h2a2 2 0 011.995 1.85L15 6v2a1 1 0 01-1.993.117L13 8V6h-2v4h1a2 2 0 011.995 1.85L14 12v1a3 3 0 01-2.824 2.995L11 16h-.5v1a1 1 0 01-1.993.117L8.5 17v-1H7a2 2 0 01-1.995-1.85L5 14v-2a1 1 0 011.993-.117L7 12v2h4v-4H8a2 2 0 01-1.995-1.85L6 8V7a3 3 0 012.824-2.995L9 4h.5V3a1 1 0 011-1z" />
                                    </svg>
                                    <span>{{ $salaryLabel }}</span>
                                </div>
                            </div>

                            <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:flex-wrap sm:items-center">
                                <a href="#apply-form" class="inline-flex w-full items-center justify-center rounded-full bg-emerald-600 px-8 py-3.5 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500 sm:w-auto">
                                    Аплицирај
                                </a>
                                @if ($callPhone)
                                    <a href="tel:{{ $callPhone }}" data-call-track-url="{{ $callTrackingUrl }}" class="call-track-trigger inline-flex w-full items-center justify-center rounded-full bg-sky-500 px-8 py-3.5 text-sm font-semibold text-white shadow-lg shadow-sky-950/20 transition hover:bg-sky-400 sm:w-auto">
                                        Повикај
                                    </a>
                                @endif
                                <a href="{{ route('jobs.index') }}" class="inline-flex w-full items-center justify-center rounded-full border border-white/10 bg-white/5 px-6 py-3.5 text-sm font-semibold text-white transition hover:bg-white/10 sm:w-auto">
                                    Сите огласи
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
                    </div>
                </div>

                <div class="rounded-[1.9rem] border border-white/10 bg-white/10 p-6 text-white shadow-[0_24px_70px_-36px_rgba(15,23,42,0.9)] backdrop-blur">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-300">Брз преглед</p>
                    <div class="mt-4">
                        <p class="text-sm text-slate-300">Дневница / плата</p>
                        <p class="mt-2 text-3xl font-extrabold tracking-tight">{{ $salaryLabel }}</p>
                    </div>
                    <div class="mt-6 grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Локација</p>
                            <p class="mt-2 text-sm font-semibold text-white">{{ $job['location'] ?: 'По договор' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Компанија</p>
                            <p class="mt-2 text-sm font-semibold text-white">{{ $job['company'] }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Категорија</p>
                            <p class="mt-2 text-sm font-semibold text-white">{{ $job['category'] ?: 'Не е наведено' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <main class="bg-stone-50">
        <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
            <div class="grid gap-8 lg:grid-cols-[minmax(0,1.75fr)_minmax(18rem,0.75fr)] lg:items-start">
                <div class="space-y-12">
                    <div class="rounded-[1.7rem] border border-slate-200 bg-white p-7 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.24)] sm:p-8">
                        <div class="border-b border-slate-100 pb-6">
                            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-600">Преглед на оглас</p>
                            <h2 class="mt-3 text-2xl font-bold tracking-tight text-slate-900">Краток вовед</h2>
                            <p class="mt-4 text-base leading-8 text-slate-600">
                                {{ $jobIntro }}
                            </p>
                        </div>

                        <div class="grid gap-8 pt-6">
                            <section>
                                <h3 class="text-lg font-bold text-slate-900">Одговорности</h3>
                                <ul class="mt-4 space-y-3 text-sm leading-7 text-slate-600 sm:text-base">
                                    @foreach ($responsibilities as $item)
                                        <li class="flex gap-3">
                                            <span class="mt-2 h-2.5 w-2.5 shrink-0 rounded-full bg-emerald-500"></span>
                                            <span>{{ $item }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </section>

                            <section>
                                <h3 class="text-lg font-bold text-slate-900">Потребни услови</h3>
                                <ul class="mt-4 space-y-3 text-sm leading-7 text-slate-600 sm:text-base">
                                    @foreach ($requirements as $item)
                                        <li class="flex gap-3">
                                            <span class="mt-2 h-2.5 w-2.5 shrink-0 rounded-full bg-slate-400"></span>
                                            <span>{{ $item }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </section>

                            <section>
                                <h3 class="text-lg font-bold text-slate-900">Што нудиме</h3>
                                <ul class="mt-4 space-y-3 text-sm leading-7 text-slate-600 sm:text-base">
                                    @foreach ($offers as $item)
                                        <li class="flex gap-3">
                                            <span class="mt-2 h-2.5 w-2.5 shrink-0 rounded-full bg-orange-400"></span>
                                            <span>{{ $item }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </section>
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-3">
                        @foreach ($trustItems as $trustItem)
                            <div class="rounded-[1.5rem] border border-slate-200 bg-white p-6 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.16)]">
                                <p class="text-base font-bold text-slate-900">{{ $trustItem['title'] }}</p>
                                <p class="mt-3 text-sm leading-7 text-slate-600">{{ $trustItem['text'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div id="apply-form" class="rounded-[1.7rem] border border-slate-200 bg-white p-7 shadow-[0_24px_50px_-34px_rgba(15,23,42,0.22)]">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-600">Апликација</p>
                            <h2 class="mt-3 text-2xl font-bold tracking-tight text-slate-900">Аплицирај за оваа позиција</h2>
                            <div class="mt-4 rounded-[1.3rem] bg-slate-50 px-4 py-4">
                                <p class="text-sm leading-7 text-slate-600">
                                    Пополнете ги основните податоци и испратете кратка порака. Формата е едноставна и апликацијата трае само неколку минути.
                                </p>
                                <p class="mt-2 text-sm font-medium text-emerald-700">Ќе бидете контактирани од работодавачот</p>
                            </div>
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
                            <form method="POST" action="{{ route('jobs.apply', $job['slug']) }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                                @csrf
                                <div class="rounded-[1.35rem] border border-slate-200 p-4">
                                    <p class="text-sm font-semibold text-slate-900">Основни податоци</p>
                                    <div class="mt-4 grid gap-5">
                                        <div>
                                            <label for="full_name" class="mb-2 block text-sm font-semibold text-slate-700">Име и презиме</label>
                                            <input id="full_name" name="full_name" type="text" value="{{ old('full_name') }}" required class="block w-full rounded-2xl border-slate-200 px-4 py-3.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">
                                        </div>

                                        <div class="grid gap-5 sm:grid-cols-2">
                                            <div>
                                                <label for="phone" class="mb-2 block text-sm font-semibold text-slate-700">Телефон</label>
                                                <input id="phone" name="phone" type="text" value="{{ old('phone') }}" required class="block w-full rounded-2xl border-emerald-200 bg-emerald-50/50 px-4 py-3.5 text-sm shadow-sm ring-2 ring-emerald-100/70 focus:border-emerald-500 focus:ring-emerald-100">
                                                <p class="mt-2 text-xs font-medium text-emerald-700">Ова е најважниот податок за брз контакт.</p>
                                            </div>
                                            <div>
                                                <label for="city" class="mb-2 block text-sm font-semibold text-slate-700">Град</label>
                                                <input id="city" name="city" type="text" value="{{ old('city') }}" required class="block w-full rounded-2xl border-slate-200 px-4 py-3.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-[1.35rem] border border-slate-200 p-4">
                                    <p class="text-sm font-semibold text-slate-900">Дополнителни информации</p>
                                    <div class="mt-4 space-y-5">
                                        <div>
                                            <label for="message" class="mb-2 block text-sm font-semibold text-slate-700">Порака</label>
                                            <textarea id="message" name="message" rows="5" placeholder="Накратко претставете се и наведете зошто сте заинтересирани за огласот." class="block w-full rounded-2xl border-slate-200 px-4 py-3.5 text-sm shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-emerald-100">{{ old('message') }}</textarea>
                                        </div>

                                        <div>
                                            <label for="cv" class="mb-2 block text-sm font-semibold text-slate-700">CV / Биографија</label>
                                            <input id="cv" name="cv" type="file" accept=".pdf,.doc,.docx" class="block w-full rounded-2xl border-slate-200 px-4 py-3.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">
                                            <p class="mt-2 text-xs text-slate-500">Дозволени формати: PDF, DOC, DOCX. Максимум 5MB.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-[1.35rem] bg-slate-50 px-4 py-4">
                                    <div class="flex items-start gap-3">
                                        <span class="mt-0.5 inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                                            <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.172 7.707 8.879a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900">Пред да испратите</p>
                                            <p class="mt-1 text-xs leading-6 text-slate-600">Проверете дали телефонскиот број е точен. Работодавачот најчесто прво контактира по телефон.</p>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-600 px-6 py-4 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
                                    Испрати апликација
                                </button>
                            </form>
                        @else
                            <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-4 text-sm leading-7 text-amber-800">
                                Формата за аплицирање ќе биде активна кога овој оглас ќе биде поврзан со базата и достапен за прием на апликации.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="lg:sticky lg:top-24">
                    <div class="rounded-[1.7rem] border border-slate-200 bg-white p-7 shadow-[0_24px_50px_-34px_rgba(15,23,42,0.26)]">
                        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-600">Резиме</p>

                        <div class="mt-5 space-y-5">
                            <div class="rounded-[1.25rem] bg-emerald-50 px-5 py-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Дневница / плата</p>
                                <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $salaryLabel }}</p>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                                    <span class="text-sm text-slate-500">Локација</span>
                                    <span class="text-right text-sm font-semibold text-slate-900">{{ $job['location'] ?: 'По договор' }}</span>
                                </div>
                                <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                                    <span class="text-sm text-slate-500">Компанија</span>
                                    <span class="text-right text-sm font-semibold text-slate-900">{{ $job['company'] }}</span>
                                </div>
                                <div class="flex items-start justify-between gap-4">
                                    <span class="text-sm text-slate-500">Категорија</span>
                                    <span class="text-right text-sm font-semibold text-slate-900">{{ $job['category'] ?: 'Не е наведено' }}</span>
                                </div>
                            </div>
                        </div>

                        <a href="#apply-form" class="mt-7 inline-flex w-full items-center justify-center rounded-2xl bg-emerald-600 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
                            Аплицирај
                        </a>
                        @if ($callPhone)
                            <a href="tel:{{ $callPhone }}" data-call-track-url="{{ $callTrackingUrl }}" class="call-track-trigger mt-3 inline-flex w-full items-center justify-center rounded-2xl bg-sky-500 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-sky-950/20 transition hover:bg-sky-400">
                                Повикај
                            </a>
                        @endif

                        <p class="mt-4 text-center text-xs font-medium text-slate-500">Вашите податоци се безбедни</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8 lg:pb-20">
            <div class="mb-8">
                <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-[2rem]">Слични огласи</h2>
                <p class="mt-2 text-sm text-slate-600">Погледнете уште неколку ангажмани што може да ви одговараат.</p>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                @foreach ($relatedJobs as $relatedJob)
                    @include('partials.job-card', ['job' => $relatedJob, 'showAction' => true])
                @endforeach
            </div>
        </section>
    </main>

    @include('partials.footer')

    @if ($callTrackingUrl)
        <script>
            document.querySelectorAll('.call-track-trigger').forEach(function (link) {
                link.addEventListener('click', function () {
                    const trackUrl = this.dataset.callTrackUrl;

                    if (!trackUrl) {
                        return;
                    }

                    const beacon = new Image();
                    beacon.src = trackUrl + (trackUrl.includes('?') ? '&' : '?') + 't=' + Date.now();
                });
            });
        </script>
    @endif
@endsection
