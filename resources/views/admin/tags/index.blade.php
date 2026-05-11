@extends('admin.layouts.app', [
    'pageTitle' => 'Тагови',
    'pageDescription' => 'Креирање и одржување на тагови за огласите.',
])

@section('content')
    <div class="mb-6 flex items-center justify-end">
        <a href="{{ route('admin.tags.create') }}" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
            Додади таг
        </a>
    </div>

    <div class="overflow-hidden rounded-[1.6rem] bg-white shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr class="text-left text-slate-500">
                        <th class="px-6 py-4 font-semibold">Име</th>
                        <th class="px-6 py-4 font-semibold">Slug</th>
                        <th class="px-6 py-4 font-semibold">Огласи</th>
                        <th class="px-6 py-4 font-semibold text-right">Акции</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($tags as $tag)
                        <tr>
                            <td class="px-6 py-4 font-semibold text-slate-900">{{ $tag->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $tag->slug }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $tag->job_listings_count }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('admin.tags.edit', $tag) }}" class="rounded-full border border-slate-200 px-4 py-2 font-semibold text-slate-700 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">Измени</a>
                                    <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}" onsubmit="return confirm('Дали сте сигурни дека сакате да го избришете тагот?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-full border border-rose-200 px-4 py-2 font-semibold text-rose-700 transition hover:bg-rose-50">Избриши</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-500">Сè уште нема креирани тагови.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
