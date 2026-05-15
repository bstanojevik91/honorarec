@if ($errors->any())
    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">
        {{ $errors->first() }}
    </div>
@endif

@php($dailyPayMode = old('daily_pay_mode', isset($job) ? ($job->daily_pay !== null ? 'amount' : 'agreement') : 'amount'))
@php($engagementTypeOptions = \App\Models\JobListing::engagementTypeOptions())
@php($selectedLocation = old('location', $job->location ?? ''))
@php($selectedTagIds = old('tag_ids', isset($job) && $job->relationLoaded('tags') ? $job->tags->pluck('id')->all() : []))

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

    @include('partials.location-filter', [
        'locationTree' => \App\Support\LocationOptions::tree(),
        'inputName' => 'location',
        'selectedValue' => $selectedLocation,
        'selectedLabel' => \App\Support\LocationOptions::displayLabel($selectedLocation),
        'placeholder' => 'Избери локација',
        'containerClass' => 'relative z-[80] block',
        'triggerClass' => 'flex h-[3.125rem] w-full min-w-0 items-center justify-between gap-3 overflow-hidden rounded-2xl border border-slate-200 bg-white pl-4 pr-3 text-sm font-medium text-slate-900 outline-none transition hover:border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100',
        'panelClass' => 'absolute left-0 right-0 top-[calc(100%+0.7rem)] z-[120] lg:right-auto lg:w-[24rem]',
    ])

    <div>
        <label for="category" class="mb-2 block text-sm font-semibold text-slate-700">Категорија</label>
        <input id="category" name="category" type="text" value="{{ old('category', $job->category ?? '') }}" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
    </div>

    <div>
        <label for="engagement_type" class="mb-2 block text-sm font-semibold text-slate-700">Вид на работен ангажман</label>
        <select id="engagement_type" name="engagement_type" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
            <option value="">Избери ангажман</option>
            @foreach ($engagementTypeOptions as $engagementType)
                <option value="{{ $engagementType }}" @selected(old('engagement_type', $job->engagement_type ?? '') === $engagementType)>{{ $engagementType }}</option>
            @endforeach
        </select>
    </div>

    @include('partials.tag-selector', [
        'availableTags' => $availableTags ?? collect(),
        'selectedTagIds' => $selectedTagIds,
        'helpText' => 'Избери тагови што најдобро го опишуваат огласот за полесно пронаоѓање од кандидатите.',
    ])

    <div class="lg:col-span-2">
        <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">Опис</label>
        <textarea id="description" name="description" rows="8" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">{{ old('description', $job->description ?? '') }}</textarea>
    </div>

    <div class="lg:col-span-2">
        <label for="job_image" class="block text-sm font-semibold text-slate-700">
            Слика за оглас / банер
        </label>

        <input
            type="file"
            name="job_image"
            id="job_image"
            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
            class="mt-2 block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700"
        >

        <p class="mt-2 text-xs text-slate-500">
            Опционално. Дозволени формати: JPG, PNG, WEBP. Максимум 3MB.
        </p>

        @if (!empty($job?->job_image))
            <div class="mt-4">
                <p class="text-sm font-semibold text-slate-700">Моментална слика:</p>
                <img
                    src="{{ asset($job->job_image) }}"
                    alt="{{ $job->title }}"
                    class="mt-2 max-h-48 rounded-xl border border-slate-200 object-contain"
                >

                <label class="mt-3 flex items-center gap-2 text-sm text-slate-700">
                    <input type="checkbox" name="remove_job_image" value="1" @checked(old('remove_job_image')) class="rounded border-slate-300">
                    <span>Избриши ја моменталната слика</span>
                </label>
            </div>
        @endif

        @error('job_image')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
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
