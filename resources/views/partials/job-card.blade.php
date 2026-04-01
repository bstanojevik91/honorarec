<article class="group rounded-[1.45rem] border border-slate-200 bg-white p-5 shadow-[0_18px_42px_-32px_rgba(15,23,42,0.25)] transition duration-300 hover:shadow-[0_24px_48px_-30px_rgba(15,23,42,0.28)] sm:rounded-[1.6rem] sm:p-6 md:hover:-translate-y-1">
    <div class="flex items-start justify-between gap-3">
        <img src="{{ $job['logo'] }}" alt="{{ $job['company'] }}" class="h-14 w-14 rounded-[1rem] border border-slate-200 object-cover sm:h-16 sm:w-16 sm:rounded-[1.15rem]">
        <span class="inline-flex shrink-0 rounded-full bg-orange-100 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.14em] text-orange-700 sm:text-[11px]">
            {{ $job['badge'] }}
        </span>
    </div>

    <h3 class="mt-4 text-xl font-bold leading-snug text-slate-900 sm:mt-5 sm:text-[1.35rem]">
        <a href="{{ route('jobs.show', $job['slug']) }}" class="transition hover:text-emerald-700">
            {{ $job['title'] }}
        </a>
    </h3>

    <div class="mt-5 flex flex-wrap items-center gap-x-3 gap-y-2 text-sm leading-6 text-slate-500 sm:mt-7">
        <span>{{ $job['company'] }}</span>
        <span class="h-1 w-1 rounded-full bg-slate-300"></span>
        <span>{{ $job['category'] }}</span>
        <span class="h-1 w-1 rounded-full bg-slate-300"></span>
        <span>{{ $job['location'] }}</span>
    </div>

    @if (!empty($job['engagement_type']) || !empty($job['tags']))
        <div class="mt-4 flex flex-wrap gap-2 sm:mt-5">
            @if (!empty($job['engagement_type']))
                <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                    {{ $job['engagement_type'] }}
                </span>
            @endif

            @foreach (array_slice($job['tags'] ?? [], 0, 3) as $tag)
                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                    {{ $tag }}
                </span>
            @endforeach
        </div>
    @endif

    <div class="mt-6 sm:mt-7">
        <a href="{{ route('jobs.show', $job['slug']) }}" class="inline-flex w-full items-center justify-center gap-2 rounded-full border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700 sm:w-auto sm:justify-start sm:py-2.5">
            {{ ($showAction ?? false) === true ? 'Отвори оглас' : 'Види детали' }}
            <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4" aria-hidden="true">
                <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h9.586L10.293 5.707a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414-1.414L13.586 11H4a1 1 0 01-1-1z" clip-rule="evenodd" />
            </svg>
        </a>
    </div>
</article>
