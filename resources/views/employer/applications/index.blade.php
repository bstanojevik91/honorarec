@extends('employer.layouts.app', [
    'pageTitle' => 'Апликанти',
    'pageDescription' => 'Прегледајте ги кандидатите што аплицирале на вашите огласи.',
])

@section('content')
    <div class="mb-6 rounded-[1.6rem] bg-white p-6 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
        <form method="GET" action="{{ route('employer.applications.index') }}" class="grid gap-4 md:grid-cols-[1fr_auto_auto]">
            <select name="job" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
                <option value="">Сите мои огласи</option>
                @foreach ($jobs as $job)
                    <option value="{{ $job->id }}" @selected($selectedJobId === $job->id)>{{ $job->title }}</option>
                @endforeach
            </select>
            <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-500">
                Филтрирај
            </button>
            <a href="{{ route('employer.applications.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                Исчисти
            </a>
        </form>
    </div>

    <div class="overflow-hidden rounded-[1.6rem] bg-white shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr class="text-left text-slate-500">
                        <th class="px-6 py-4 font-semibold">Кандидат</th>
                        <th class="px-6 py-4 font-semibold">Оглас</th>
                        <th class="px-6 py-4 font-semibold">Телефон</th>
                        <th class="px-6 py-4 font-semibold">Град</th>
                        <th class="px-6 py-4 font-semibold">Датум</th>
                        <th class="px-6 py-4 font-semibold text-right">Преглед</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($applications as $application)
                        <tr>
                            <td class="px-6 py-4 font-semibold text-slate-900">{{ $application->full_name }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $application->jobListing?->title }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $application->phone }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $application->city }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $application->created_at->format('d.m.Y H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('employer.applications.show', $application) }}" class="font-semibold text-emerald-700 transition hover:text-emerald-600">Отвори</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">Нема апликации за вашите огласи.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 px-6 py-4">
            {{ $applications->links() }}
        </div>
    </div>
@endsection
