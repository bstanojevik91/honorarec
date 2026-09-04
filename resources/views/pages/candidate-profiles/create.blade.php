@extends('layouts.app')

@section('content')
    @php
        $genders = \App\Models\CandidateProfile::genderOptions();
        $radii = \App\Models\CandidateProfile::radiusOptions();
        $drivingStatuses = \App\Models\CandidateProfile::drivingStatusOptions();
        $engagementTypes = \App\Models\CandidateProfile::engagementTypeOptions();
        $workCategories = \App\Models\CandidateProfile::workCategoryOptions();
    @endphp

    @push('styles')
        <style>
            #candidate-profile-form .candidate-honeypot { position: absolute; left: -10000px; width: 1px; height: 1px; overflow: hidden; }
        </style>
    @endpush

    <div class="min-h-screen bg-stone-50">
        <div class="relative isolate overflow-hidden bg-slate-950">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.18),_transparent_32%),linear-gradient(180deg,_rgba(2,6,23,0.78),_rgba(2,6,23,0.92))]"></div>
            @include('partials.header')

            <section class="relative mx-auto max-w-7xl px-4 pb-14 pt-28 sm:px-6 sm:pb-18 sm:pt-32 lg:px-8 lg:pb-24 lg:pt-44">
                <div class="mx-auto max-w-4xl text-center text-white">
                    <p class="text-sm font-semibold uppercase tracking-[0.28em] text-emerald-300">База на кандидати</p>
                    <h1 class="mt-3 text-[2.15rem] font-extrabold tracking-tight sm:mt-4 sm:text-5xl">Биди Хонорарец</h1>
                    <p class="mx-auto mt-3 max-w-3xl text-base leading-7 text-slate-200 sm:mt-4 sm:text-xl">Остави ги твоите податоци и стани дел од базата на кандидати на Honorarec.mk. Кога ќе се појави работна можност што одговара на твојата локација, интереси и достапност, Honorarec.mk или избран работодавач може да те контактира.</p>
                </div>
            </section>
        </div>

        <main class="mx-auto max-w-5xl px-4 py-10 sm:px-6 sm:py-14 lg:px-8 lg:py-20">
            @if (session('candidate_profile_status'))
                <div class="mb-8 rounded-[1.5rem] border border-emerald-200 bg-emerald-50 p-5 text-sm font-medium leading-7 text-emerald-800 sm:p-6">{{ session('candidate_profile_status') }}</div>
            @endif

            <form id="candidate-profile-form" method="POST" action="{{ route('candidate-profiles.store') }}" class="space-y-6">
                @csrf
                <div class="candidate-honeypot" aria-hidden="true">
                    <label for="website">Website</label>
                    <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
                </div>

                @if ($errors->has('form'))
                    <div class="rounded-[1.5rem] border border-red-200 bg-red-50 p-5 text-sm font-medium text-red-800">{{ $errors->first('form') }}</div>
                @endif

                <section class="rounded-[1.6rem] border border-slate-200 bg-white p-5 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.18)] sm:p-8">
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900">Основни податоци</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Полињата означени со * се задолжителни.</p>
                    <div class="mt-6 grid gap-5 sm:grid-cols-2">
                        @foreach (['first_name' => 'Име', 'last_name' => 'Презиме'] as $field => $label)
                            <div>
                                <label for="{{ $field }}" class="mb-2 block text-sm font-semibold text-slate-700">{{ $label }} *</label>
                                <input id="{{ $field }}" name="{{ $field }}" value="{{ old($field) }}" required class="block w-full rounded-2xl border-slate-200 px-4 py-3.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">
                                @error($field)<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        @endforeach
                        <fieldset>
                            <legend class="mb-2 block text-sm font-semibold text-slate-700">Пол *</legend>
                            <div class="grid gap-2">
                                @foreach ($genders as $value => $label)
                                    <label class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700"><input type="radio" name="gender" value="{{ $value }}" @checked(old('gender') === $value) required class="border-slate-300 text-emerald-600 focus:ring-emerald-500">{{ $label }}</label>
                                @endforeach
                            </div>
                            @error('gender')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </fieldset>
                        <div>
                            <label for="date_of_birth" class="mb-2 block text-sm font-semibold text-slate-700">Датум на раѓање *</label>
                            <input id="date_of_birth" name="date_of_birth" type="date" value="{{ old('date_of_birth') }}" required class="block w-full rounded-2xl border-slate-200 px-4 py-3.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">
                            @error('date_of_birth')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="phone" class="mb-2 block text-sm font-semibold text-slate-700">Телефон за контакт *</label>
                            <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required autocomplete="tel" class="block w-full rounded-2xl border-slate-200 px-4 py-3.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">
                            @error('phone')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email *</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="block w-full rounded-2xl border-slate-200 px-4 py-3.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">
                            @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </section>

                <section class="rounded-[1.6rem] border border-slate-200 bg-white p-5 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.18)] sm:p-8">
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900">Локација и превоз</h2>
                    <div class="mt-6 grid gap-5 sm:grid-cols-2">
                        @foreach (['city' => 'Град/општина', 'neighbourhood' => 'Населба'] as $field => $label)
                            <div><label for="{{ $field }}" class="mb-2 block text-sm font-semibold text-slate-700">{{ $label }} *</label><input id="{{ $field }}" name="{{ $field }}" value="{{ old($field) }}" required class="block w-full rounded-2xl border-slate-200 px-4 py-3.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">@error($field)<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        @endforeach
                        <div class="sm:col-span-2"><label for="exact_address" class="mb-2 block text-sm font-semibold text-slate-700">Точна адреса – опционално</label><input id="exact_address" name="exact_address" value="{{ old('exact_address') }}" class="block w-full rounded-2xl border-slate-200 px-4 py-3.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">@error('exact_address')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <fieldset><legend class="mb-2 block text-sm font-semibold text-slate-700">Радиус во кој ти одговара да работиш *</legend><div class="grid gap-2">@foreach ($radii as $value => $label)<label class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700"><input type="radio" name="preferred_radius" value="{{ $value }}" @checked(old('preferred_radius') === $value) required class="border-slate-300 text-emerald-600 focus:ring-emerald-500">{{ $label }}</label>@endforeach</div>@error('preferred_radius')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror</fieldset>
                        <fieldset><legend class="mb-2 block text-sm font-semibold text-slate-700">Возачка дозвола *</legend><div class="grid gap-2">@foreach ($drivingStatuses as $value => $label)<label class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700"><input type="radio" name="driving_status" value="{{ $value }}" @checked(old('driving_status') === $value) required class="border-slate-300 text-emerald-600 focus:ring-emerald-500">{{ $label }}</label>@endforeach</div>@error('driving_status')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror</fieldset>
                        <fieldset class="sm:col-span-2"><legend class="mb-2 block text-sm font-semibold text-slate-700">Дали моментално си во активен работен однос? *</legend><div class="grid gap-2 sm:grid-cols-2"><label class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700"><input type="radio" name="current_employment_status" value="1" @checked(old('current_employment_status') === '1') required class="border-slate-300 text-emerald-600 focus:ring-emerald-500">Да, моментално сум вработен/а</label><label class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700"><input type="radio" name="current_employment_status" value="0" @checked(old('current_employment_status') === '0') required class="border-slate-300 text-emerald-600 focus:ring-emerald-500">Не, моментално не сум вработен/а</label></div>@error('current_employment_status')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror</fieldset>
                    </div>
                </section>

                <section class="rounded-[1.6rem] border border-slate-200 bg-white p-5 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.18)] sm:p-8">
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900">Каков ангажман бараш?</h2>
                    <fieldset class="mt-6"><legend class="mb-3 text-sm font-semibold text-slate-700">Каков работен ангажман ти одговара? *</legend><div class="grid gap-3 sm:grid-cols-2">@foreach ($engagementTypes as $value => $label)<label class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700"><input type="checkbox" name="engagement_types[]" value="{{ $value }}" @checked(in_array($value, old('engagement_types', []), true)) class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">{{ $label }}</label>@endforeach</div>@error('engagement_types')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror @error('engagement_types.*')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror</fieldset>
                </section>

                <section class="rounded-[1.6rem] border border-slate-200 bg-white p-5 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.18)] sm:p-8">
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900">Области на работа</h2>
                    <fieldset class="mt-6"><legend class="mb-3 text-sm font-semibold text-slate-700">Каков тип на работа би ти одговарал? *</legend><div class="grid gap-3 sm:grid-cols-2">@foreach ($workCategories as $value => $label)<label class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700"><input type="checkbox" name="work_categories[]" value="{{ $value }}" @checked(in_array($value, old('work_categories', []), true)) class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">{{ $label }}</label>@endforeach</div>@error('work_categories')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror @error('work_categories.*')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror</fieldset>
                    <div class="mt-5"><label for="other_work_preference" class="mb-2 block text-sm font-semibold text-slate-700">Наведи каква работа ти одговара</label><input id="other_work_preference" name="other_work_preference" value="{{ old('other_work_preference') }}" class="block w-full rounded-2xl border-slate-200 px-4 py-3.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">@error('other_work_preference')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                    <div class="mt-5"><label for="additional_information" class="mb-2 block text-sm font-semibold text-slate-700">Дополнителни информации <span class="font-normal text-slate-500">(опционално)</span></label><textarea id="additional_information" name="additional_information" rows="5" maxlength="2000" class="block w-full rounded-2xl border-slate-200 px-4 py-3.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">{{ old('additional_information') }}</textarea><p class="mt-2 text-xs leading-6 text-slate-500">Наведи искуство, вештини, достапност или друга информација што може да биде корисна.</p>@error('additional_information')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                </section>

                <section class="rounded-[1.6rem] border border-slate-200 bg-white p-5 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.18)] sm:p-8">
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900">Приватност и согласност</h2>
                    <label class="mt-6 flex items-start gap-3 text-sm leading-7 text-slate-600"><input type="checkbox" name="privacy_consent" value="1" @checked(old('privacy_consent')) required class="mt-1 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"><span>Потврдувам дека ја прочитав <a href="{{ route('privacy.policy') }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-emerald-700 underline decoration-emerald-300 underline-offset-4">Политиката за приватност</a> и се согласувам Honorarec.mk да ги обработува и чува моите податоци за евиденција во базата на кандидати, поврзување со соодветни можности за работа и контакт во врска со работен ангажман. Се согласувам релевантните податоци да бидат споделени со избрани работодавачи за да можат да ме контактираат за соодветна можност за работа.</span></label>
                    @error('privacy_consent')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    @if ($turnstileEnabled)
                        <div class="mt-6 cf-turnstile" data-sitekey="{{ $turnstileSiteKey }}" data-action="candidate_registration" data-response-field-name="turnstile_token"></div>
                        @push('scripts')<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>@endpush
                    @endif
                    <button type="submit" class="mt-7 inline-flex w-full items-center justify-center rounded-2xl bg-emerald-600 px-6 py-4 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500">Стани дел од базата</button>
                </section>
            </form>
        </main>

        @include('partials.footer')
    </div>
@endsection
