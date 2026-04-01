<article class="group flex h-full flex-col overflow-hidden rounded-[1.45rem] border border-slate-200 bg-white shadow-[0_18px_42px_-34px_rgba(15,23,42,0.2)] transition duration-300 hover:shadow-[0_24px_48px_-30px_rgba(15,23,42,0.24)] sm:rounded-[1.6rem] md:hover:-translate-y-1">
    <a href="{{ route('blog.show', $post['slug']) }}" class="block overflow-hidden">
        <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}" class="h-48 w-full object-cover transition duration-500 group-hover:scale-[1.03] sm:h-52">
    </a>

    <div class="flex flex-1 flex-col p-5 sm:p-7">
        <div class="flex flex-wrap items-center gap-2.5 text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-400 sm:text-xs sm:tracking-[0.18em]">
            <span class="text-emerald-700">{{ $post['published_at'] }}</span>
            @if (!empty($post['category']))
                <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                <span>{{ $post['category'] }}</span>
            @endif
        </div>

        <h3 class="mt-3 text-xl font-bold leading-snug text-slate-900 sm:mt-4 sm:text-[1.45rem]">
            <a href="{{ route('blog.show', $post['slug']) }}" class="transition hover:text-emerald-700">
                {{ $post['title'] }}
            </a>
        </h3>

        <p class="mt-3 flex-1 text-sm leading-7 text-slate-600 sm:mt-4">{{ $post['excerpt'] }}</p>

        <div class="mt-6 sm:mt-8">
            <a href="{{ route('blog.show', $post['slug']) }}" class="inline-flex w-full items-center justify-center rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500 sm:w-auto">
                Прочитај повеќе
            </a>
        </div>
    </div>
</article>
