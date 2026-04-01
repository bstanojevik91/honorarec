@extends('employer.layouts.app', [
    'pageTitle' => 'Детали за апликација',
    'pageDescription' => 'Преглед на кандидатот што аплицирал на вашиот оглас.',
])

@section('content')
    <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
        <div class="rounded-[1.6rem] bg-white p-8 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
            <h2 class="text-xl font-bold text-slate-900">{{ $application->full_name }}</h2>
            <p class="mt-2 text-sm text-slate-500">Аплицирано на {{ $application->created_at->format('d.m.Y H:i') }}</p>

            <div class="mt-8 grid gap-5 sm:grid-cols-2">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Телефон</p>
                    <p class="mt-2 text-base font-semibold text-slate-900">{{ $application->phone }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Град</p>
                    <p class="mt-2 text-base font-semibold text-slate-900">{{ $application->city }}</p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Порака</p>
                    <p class="mt-2 whitespace-pre-line text-sm leading-7 text-slate-600">{{ $application->message ?: 'Нема дополнителна порака.' }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-[1.6rem] bg-white p-8 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
            <h2 class="text-lg font-bold text-slate-900">Поврзан оглас</h2>

            <div class="mt-6 space-y-5">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Наслов</p>
                    <p class="mt-2 text-base font-semibold text-slate-900">{{ $application->jobListing?->title }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Компанија</p>
                    <p class="mt-2 text-base font-semibold text-slate-900">{{ $application->jobListing?->company?->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">CV</p>
                    @if ($application->cv_path)
                        <a href="{{ route('employer.applications.cv', $application) }}" class="mt-2 inline-flex text-sm font-semibold text-emerald-700 hover:text-emerald-600">Отвори CV</a>
                    @else
                        <p class="mt-2 text-sm text-slate-500">Нема прикачено CV.</p>
                    @endif
                </div>
            </div>

            <div class="mt-8">
                <a href="{{ route('employer.applications.index') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-7 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    Назад кон апликанти
                </a>
            </div>
        </div>
    </div>
@endsection
