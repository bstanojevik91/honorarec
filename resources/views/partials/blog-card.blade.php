<article class="flex h-full flex-col rounded-[1.6rem] border border-slate-200 bg-stone-50 p-7 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.3)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_28px_50px_-32px_rgba(15,23,42,0.28)]">
    <h3 class="text-[1.45rem] font-bold leading-snug text-slate-900">{{ $post['title'] }}</h3>
    <p class="mt-4 flex-1 text-sm leading-7 text-slate-600">{{ $post['excerpt'] }}</p>
    <div class="mt-8">
        <a href="#" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
            Прочитај повеќе
        </a>
    </div>
</article>
