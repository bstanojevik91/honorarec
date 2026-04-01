<article class="group flex h-full flex-col overflow-hidden rounded-[1.6rem] border border-slate-200 bg-white shadow-[0_20px_45px_-34px_rgba(15,23,42,0.22)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_28px_50px_-30px_rgba(15,23,42,0.26)]">
    <a href="{{ route('blog.show', $post['slug']) }}" class="block overflow-hidden">
        <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}" class="h-52 w-full object-cover transition duration-500 group-hover:scale-[1.03]">
    </a>

    <div class="flex flex-1 flex-col p-7">
        <div class="flex flex-wrap items-center gap-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">
            <span class="text-emerald-700">{{ $post['published_at'] }}</span>
            @if (!empty($post['category']))
                <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                <span>{{ $post['category'] }}</span>
            @endif
        </div>

        <h3 class="mt-4 text-[1.45rem] font-bold leading-snug text-slate-900">
            <a href="{{ route('blog.show', $post['slug']) }}" class="transition hover:text-emerald-700">
                {{ $post['title'] }}
            </a>
        </h3>

        <p class="mt-4 flex-1 text-sm leading-7 text-slate-600">{{ $post['excerpt'] }}</p>

        <div class="mt-8">
            <a href="{{ route('blog.show', $post['slug']) }}" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
                Прочитај повеќе
            </a>
        </div>
    </div>
</article>
