@if ($errors->any())
    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">
        {{ $errors->first() }}
    </div>
@endif

@php($dailyPayMode = old('daily_pay_mode', isset($job) ? ($job->daily_pay !== null ? 'amount' : 'agreement') : 'amount'))

<div class="grid gap-6 lg:grid-cols-2">
    <div>
        <label for="title" class="mb-2 block text-sm font-semibold text-slate-700">Наслов</label>
        <input id="title" name="title" type="text" value="{{ old('title', $job->title ?? '') }}" required class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
    </div>

    <div>
        <label for="daily_pay" class="mb-2 block text-sm font-semibold text-slate-700">Дневница / плата</label>
        <div class="mb-3 flex flex-wrap items-center gap-4 text-sm text-slate-700">
            <label class="inline-flex items-center gap-2">
                <input type="radio" name="daily_pay_mode" value="amount" @checked($dailyPayMode === 'amount') class="border-slate-300 text-emerald-600 focus:ring-emerald-100">
                <span>Бројка</span>
            </label>
            <label class="inline-flex items-center gap-2">
                <input type="radio" name="daily_pay_mode" value="agreement" @checked($dailyPayMode === 'agreement') class="border-slate-300 text-emerald-600 focus:ring-emerald-100">
                <span>По договор</span>
            </label>
        </div>
        <input id="daily_pay" name="daily_pay" type="number" min="0" step="0.01" value="{{ old('daily_pay', $job->daily_pay ?? '') }}" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
        <p class="mt-2 text-xs text-slate-500">Ако изберете „По договор“, бројката нема да се зачува.</p>
    </div>

    <div>
        <label for="location" class="mb-2 block text-sm font-semibold text-slate-700">Локација</label>
        <input id="location" name="location" type="text" value="{{ old('location', $job->location ?? '') }}" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
    </div>

    <div>
        <label for="category" class="mb-2 block text-sm font-semibold text-slate-700">Категорија</label>
        <input id="category" name="category" type="text" value="{{ old('category', $job->category ?? '') }}" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
    </div>

    <div>
        <label for="contact_phone" class="mb-2 block text-sm font-semibold text-slate-700">Број за повикување</label>
        <input id="contact_phone" name="contact_phone" type="text" value="{{ old('contact_phone', $job->contact_phone ?? '') }}" placeholder="070123456 или +38970123456" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
        <p class="mt-2 text-xs text-slate-500">Оставете празно ако сакате да се користи бројот на компанијата.</p>
    </div>

    <div>
        <label for="expires_at" class="mb-2 block text-sm font-semibold text-slate-700">Датум на истекување</label>
        <input id="expires_at" name="expires_at" type="date" value="{{ old('expires_at', isset($job) && $job->expires_at ? $job->expires_at->format('Y-m-d') : '') }}" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
    </div>

    <div class="lg:col-span-2">
        <label class="flex items-center gap-3 text-sm font-semibold text-slate-700">
            <input type="checkbox" name="call_enabled" value="1" @checked(old('call_enabled', $job->call_enabled ?? false)) class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-100">
            Прикажи копче „Повикај“ на огласот
        </label>
        <p class="mt-2 text-xs text-slate-500">Кога е вклучено, кандидатите ќе можат директно да се јават на бројот од огласот или на бројот на компанијата.</p>
    </div>

    <div class="lg:col-span-2">
        <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">Опис</label>
        <textarea id="description" name="description" rows="8" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">{{ old('description', $job->description ?? '') }}</textarea>
    </div>

    <div class="lg:col-span-2">
        <label class="flex items-center gap-3 text-sm font-semibold text-slate-700">
            <input type="checkbox" name="featured" value="1" @checked(old('featured', $job->featured ?? false)) class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-100">
            Издвоен оглас
        </label>
        <p class="mt-2 text-xs text-slate-500">По зачувување, огласот оди на одобрување од администратор. Насловот автоматски ќе добие интернет адреса, па нема потреба рачно да внесувате дополнителни технички полиња.</p>
    </div>
</div>

<div class="mt-8 flex flex-wrap gap-4">
    <button type="submit" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-7 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
        {{ $submitLabel }}
    </button>
    <a href="{{ route('employer.jobs.index') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-7 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
        Откажи
    </a>
</div>
