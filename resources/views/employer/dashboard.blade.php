@extends('employer.layouts.app', [
    'pageTitle' => 'Dashboard',
    'pageDescription' => 'Преглед на вашите огласи и апликанти.',
])

@section('content')
    <div class="grid gap-6 md:grid-cols-2">
        <div class="rounded-[1.6rem] bg-white p-6 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Мои огласи</p>
            <p class="mt-4 text-4xl font-extrabold text-slate-900">{{ $stats['jobs'] }}</p>
        </div>
        <div class="rounded-[1.6rem] bg-white p-6 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Вкупно апликации</p>
            <p class="mt-4 text-4xl font-extrabold text-slate-900">{{ $stats['applications'] }}</p>
        </div>
    </div>

    <div class="mt-8 rounded-[1.6rem] bg-white p-6 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Последни апликации</h2>
                <p class="mt-1 text-sm text-slate-500">Апликации пристигнати за вашите активни и претходни огласи.</p>
            </div>
            <a href="{{ route('employer.applications.index') }}" class="text-sm font-semibold text-emerald-700 transition hover:text-emerald-600">Види ги сите</a>
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
