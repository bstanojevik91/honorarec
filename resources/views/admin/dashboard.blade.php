@extends('admin.layouts.app', [
    'pageTitle' => 'Dashboard',
    'pageDescription' => 'Преглед на основните бројки и последните пристигнати апликации.',
])

@section('content')
    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-[1.6rem] bg-white p-6 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Вкупно огласи</p>
            <p class="mt-4 text-4xl font-extrabold text-slate-900">{{ $stats['jobs'] }}</p>
        </div>
        <div class="rounded-[1.6rem] bg-white p-6 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Вкупно компании</p>
            <p class="mt-4 text-4xl font-extrabold text-slate-900">{{ $stats['companies'] }}</p>
        </div>
        <div class="rounded-[1.6rem] bg-white p-6 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Вкупно апликации</p>
            <p class="mt-4 text-4xl font-extrabold text-slate-900">{{ $stats['applications'] }}</p>
        </div>
        <div class="rounded-[1.6rem] bg-white p-6 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Кликови на „Повикај“</p>
            <p class="mt-4 text-4xl font-extrabold text-slate-900">{{ $stats['callClicks'] }}</p>
        </div>
    </div>

    <div class="mt-8 rounded-[1.6rem] bg-white p-6 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Кликови на копчето „Повикај“</h2>
                <p class="mt-1 text-sm text-slate-500">Огласи со најмногу регистрирани кликови на бројот за повикување.</p>
            </div>
            <a href="{{ route('admin.jobs.index') }}" class="text-sm font-semibold text-emerald-700 transition hover:text-emerald-600">Уреди огласи</a>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead>
                    <tr class="text-left text-slate-500">
                        <th class="pb-3 font-semibold">Оглас</th>
                        <th class="pb-3 font-semibold">Компанија</th>
                        <th class="pb-3 font-semibold">Статус на број</th>
                        <th class="pb-3 font-semibold">Кликови</th>
                        <th class="pb-3 font-semibold text-right">Акција</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($topCallJobs as $job)
                        @php($effectivePhone = $job->effectiveCallPhone())
                        <tr>
                            <td class="py-4">
                                <div class="font-semibold text-slate-900">{{ $job->title }}</div>
                                <div class="text-slate-500">{{ $job->location ?: 'Без локација' }}</div>
                            </td>
                            <td class="py-4 text-slate-700">{{ $job->company?->name ?: 'Непозната компанија' }}</td>
                            <td class="py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $effectivePhone ? 'bg-sky-100 text-sky-700' : 'bg-slate-200 text-slate-700' }}">
                                    {{ $effectivePhone ? 'Вклучен' : 'Исклучен' }}
                                </span>
                            </td>
                            <td class="py-4 text-slate-700">{{ $job->call_clicks_count }}</td>
                            <td class="py-4 text-right">
                                <a href="{{ route('admin.jobs.edit', $job) }}" class="rounded-full border border-slate-200 px-4 py-2 font-semibold text-slate-700 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">Измени</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-500">Сè уште нема регистрирани кликови на „Повикај“.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8 rounded-[1.6rem] bg-white p-6 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Последни апликации</h2>
                <p class="mt-1 text-sm text-slate-500">Брз преглед на најновите кандидати и огласите за кои аплицирале.</p>
            </div>
            <a href="{{ route('admin.applications.index') }}" class="text-sm font-semibold text-emerald-700 transition hover:text-emerald-600">Види ги сите</a>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead>
                    <tr class="text-left text-slate-500">
                        <th class="pb-3 font-semibold">Кандидат</th>
                        <th class="pb-3 font-semibold">Оглас</th>
                        <th class="pb-3 font-semibold">Телефон</th>
                        <th class="pb-3 font-semibold">Датум</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($recentApplications as $application)
                        <tr>
                            <td class="py-4">
                                <div class="font-semibold text-slate-900">{{ $application->full_name }}</div>
                                <div class="text-slate-500">{{ $application->city }}</div>
                            </td>
                            <td class="py-4 text-slate-700">{{ $application->jobListing?->title }}</td>
                            <td class="py-4 text-slate-700">{{ $application->phone }}</td>
                            <td class="py-4 text-slate-500">{{ $application->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-500">Сè уште нема апликации.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
