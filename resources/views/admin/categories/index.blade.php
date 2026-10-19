@extends('admin.layouts.app', [
    'pageTitle' => 'Категории',
    'pageDescription' => 'Категории за огласите за работа.',
])

@section('content')
    <div class="mb-6 rounded-[1.6rem] bg-white p-6 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Име на категорија</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required maxlength="255" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
            @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <div class="mt-8 flex flex-wrap gap-4">
                <button type="submit" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-7 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">Додади категорија</button>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-[1.6rem] bg-white shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr class="text-left text-slate-500"><th class="px-6 py-4 font-semibold">Име</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($categories as $category)
                        <tr><td class="px-6 py-4 font-semibold text-slate-900">{{ $category->name }}</td></tr>
                    @empty
                        <tr><td class="px-6 py-10 text-center text-slate-500">Сè уште нема креирани категории.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
