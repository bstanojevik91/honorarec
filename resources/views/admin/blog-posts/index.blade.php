@extends('admin.layouts.app', [
    'pageTitle' => 'Блог постови',
    'pageDescription' => 'Креирање, објавување и управување со блог содржина.',
])

@section('content')
    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <form method="GET" action="{{ route('admin.blog-posts.index') }}" class="grid gap-4 rounded-[1.6rem] bg-white p-5 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.18)] md:grid-cols-4">
            <div>
                <label for="search" class="mb-2 block text-sm font-semibold text-slate-700">Пребарување</label>
                <input id="search" name="search" type="text" value="{{ $filters['search'] }}" placeholder="Наслов на пост..." class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
            </div>

            <div>
                <label for="status" class="mb-2 block text-sm font-semibold text-slate-700">Статус</label>
                <select id="status" name="status" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
                    <option value="">Сите</option>
                    @foreach ($statusOptions as $value => $label)
                        <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="category" class="mb-2 block text-sm font-semibold text-slate-700">Категорија</label>
                <select id="category" name="category" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
                    <option value="">Сите</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected($filters['category'] === $category)>{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-3">
                <button type="submit" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
                    Филтрирај
                </button>
                <a href="{{ route('admin.blog-posts.index') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    Исчисти
                </a>
            </div>
        </form>

        <a href="{{ route('admin.blog-posts.create') }}" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
            Додади пост
        </a>
    </div>

    <div class="overflow-hidden rounded-[1.6rem] bg-white shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr class="text-left text-slate-500">
                        <th class="px-6 py-4 font-semibold">Наслов</th>
                        <th class="px-6 py-4 font-semibold">Статус</th>
                        <th class="px-6 py-4 font-semibold">Категорија</th>
                        <th class="px-6 py-4 font-semibold">Датум на објава</th>
                        <th class="px-6 py-4 font-semibold">Креиран</th>
                        <th class="px-6 py-4 font-semibold text-right">Акции</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($posts as $post)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">{{ $post->title }}</div>
                                <div class="mt-1 text-xs text-slate-500">{{ $post->slug }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $post->status === 'published' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $post->statusLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-700">{{ $post->category ?: 'Без категорија' }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $post->published_at?->format('d.m.Y H:i') ?: 'Не е објавен' }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $post->created_at?->format('d.m.Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('admin.blog-posts.edit', $post) }}" class="rounded-full border border-slate-200 px-4 py-2 font-semibold text-slate-700 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">Измени</a>
                                    <form method="POST" action="{{ route('admin.blog-posts.toggle-status', $post) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="rounded-full border border-sky-200 px-4 py-2 font-semibold text-sky-700 transition hover:bg-sky-50">
                                            {{ $post->status === 'published' ? 'Врати во нацрт' : 'Објави' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.blog-posts.destroy', $post) }}" onsubmit="return confirm('Дали сте сигурни дека сакате да го избришете постот?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-full border border-rose-200 px-4 py-2 font-semibold text-rose-700 transition hover:bg-rose-50">Избриши</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">Нема креирани блог постови.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 px-6 py-4">
            {{ $posts->links() }}
        </div>
    </div>
@endsection
