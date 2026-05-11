@if ($errors->any())
    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">
        {{ $errors->first() }}
    </div>
@endif

<div class="grid gap-6 lg:grid-cols-2">
    <div>
        <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Име</label>
        <input id="name" name="name" type="text" value="{{ old('name', $tag->name ?? '') }}" required class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
    </div>

    <div>
        <label for="slug" class="mb-2 block text-sm font-semibold text-slate-700">Slug</label>
        <input id="slug" name="slug" type="text" value="{{ old('slug', $tag->slug ?? '') }}" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
        <p class="mt-2 text-xs text-slate-500">Ако го оставиш празно, автоматски ќе се генерира од името.</p>
    </div>
</div>

<div class="mt-8 flex flex-wrap gap-4">
    <button type="submit" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-7 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
        {{ $submitLabel }}
    </button>
    <a href="{{ route('admin.tags.index') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-7 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
        Откажи
    </a>
</div>
