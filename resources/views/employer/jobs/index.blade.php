@extends('employer.layouts.app', [
    'pageTitle' => 'Мои огласи',
    'pageDescription' => 'Креирање, ажурирање и бришење на вашите огласи.',
])

@section('content')
    <div class="mb-6 rounded-[1.35rem] border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm text-emerald-900">
        Новите огласи и измените на постоечките огласи прво одат на одобрување од администратор пред да станат јавно видливи.
    </div>

    <div class="mb-6 flex items-center justify-end">
        <a href="{{ route('employer.jobs.create') }}" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
            Објави оглас
        </a>
    </div>

    <div class="overflow-hidden rounded-[1.6rem] bg-white shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr class="text-left text-slate-500">
                        <th class="px-6 py-4 font-semibold">Оглас</th>
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
                                <div class="mt-1 text-slate-500">
                                    {{ $job->category }}
                                    ·
                                    @if($job->daily_pay !== null)
                                        {{ number_format((float) $job->daily_pay, 0, ',', '.') }} ден.
                                    @else
                                        По договор
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-700">{{ $job->location ?: 'Не е внесено' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $job->status === 'active' ? 'bg-emerald-100 text-emerald-700' : ($job->status === 'pending' ? 'bg-sky-100 text-sky-700' : ($job->status === 'paused' ? 'bg-amber-100 text-amber-700' : ($job->status === 'rejected' ? 'bg-rose-100 text-rose-700' : 'bg-slate-200 text-slate-700'))) }}">
                                    {{ $job->statusLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('employer.jobs.edit', $job) }}" class="rounded-full border border-slate-200 px-4 py-2 font-semibold text-slate-700 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">Измени</a>
                                    <form method="POST" action="{{ route('employer.jobs.destroy', $job) }}" onsubmit="return confirm('Дали сте сигурни дека сакате да го избришете огласот?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-full border border-rose-200 px-4 py-2 font-semibold text-rose-700 transition hover:bg-rose-50">Избриши</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-500">Сè уште немате објавено огласи.</td>
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
