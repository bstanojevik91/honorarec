@extends('admin.layouts.app', [
    'pageTitle' => 'Огласи',
    'pageDescription' => 'Управување со сите огласи за работа.',
])

@section('content')
    <div class="mb-6 flex items-center justify-end">
        <a href="{{ route('admin.jobs.create') }}" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
            Додади оглас
        </a>
    </div>

    <div class="overflow-hidden rounded-[1.6rem] bg-white shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr class="text-left text-slate-500">
                        <th class="px-6 py-4 font-semibold">Оглас</th>
                        <th class="px-6 py-4 font-semibold">Компанија</th>
                        <th class="px-6 py-4 font-semibold">Локација</th>
                        <th class="px-6 py-4 font-semibold">Статус</th>
                        <th class="px-6 py-4 font-semibold text-right">Акции</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($jobs as $job)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">{{ $job->title }}</div>
                                <div class="mt-1 text-slate-500">{{ $job->category }} @if($job->daily_pay) · {{ number_format((float) $job->daily_pay, 0, ',', '.') }} ден. @endif</div>
                            </td>
                            <td class="px-6 py-4 text-slate-700">{{ $job->company?->name }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $job->location }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $job->status === 'active' ? 'bg-emerald-100 text-emerald-700' : ($job->status === 'paused' ? 'bg-amber-100 text-amber-700' : 'bg-slate-200 text-slate-700') }}">
                                    {{ $job->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('admin.jobs.edit', $job) }}" class="rounded-full border border-slate-200 px-4 py-2 font-semibold text-slate-700 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">Измени</a>
                                    <form method="POST" action="{{ route('admin.jobs.destroy', $job) }}" onsubmit="return confirm('Дали сте сигурни дека сакате да го избришете огласот?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-full border border-rose-200 px-4 py-2 font-semibold text-rose-700 transition hover:bg-rose-50">Избриши</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500">Нема креирани огласи.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 px-6 py-4">
            {{ $jobs->links() }}
        </div>
    </div>
@endsection
