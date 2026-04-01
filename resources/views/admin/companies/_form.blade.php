@if ($errors->any())
    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">
        {{ $errors->first() }}
    </div>
@endif

<div class="grid gap-6 lg:grid-cols-2">
    <div class="lg:col-span-2">
        <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Име на компанија</label>
        <input id="name" name="name" type="text" value="{{ old('name', $company->name ?? '') }}" required class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
    </div>

    <div>
        <label for="phone" class="mb-2 block text-sm font-semibold text-slate-700">Телефон</label>
        <input id="phone" name="phone" type="text" value="{{ old('phone', $company->phone ?? '') }}" required class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
    </div>

    <div>
        <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Е-пошта</label>
        <input id="email" name="email" type="email" value="{{ old('email', $company->email ?? '') }}" required class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
    </div>

    <div class="lg:col-span-2">
        <label for="logo" class="mb-2 block text-sm font-semibold text-slate-700">Лого</label>
        <input id="logo" name="logo" type="file" accept="image/*" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
    </div>

    <div class="lg:col-span-2">
        <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">Опис</label>
        <textarea id="description" name="description" rows="6" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">{{ old('description', $company->description ?? '') }}</textarea>
    </div>
</div>

<div class="mt-8 flex flex-wrap gap-4">
    <button type="submit" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-7 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
        {{ $submitLabel }}
    </button>
    <a href="{{ route('admin.companies.index') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-7 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
        Откажи
    </a>
</div>
