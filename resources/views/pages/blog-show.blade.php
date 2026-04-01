@extends('layouts.app', [
    'title' => $post['title'] . ' | Honorarec.mk',
    'description' => $post['meta_description'] ?? $post['excerpt'],
])

@section('content')
    @php
        $authorName = $post['author'] ?? 'Тимот на Honorarec.mk';
    @endphp

    <div class="relative isolate overflow-hidden bg-slate-950">
        <div class="absolute inset-0">
            <div class="h-full w-full bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.18),_transparent_34%),linear-gradient(180deg,_rgba(2,6,23,0.8),_rgba(2,6,23,0.94))]"></div>
        </div>

        @include('partials.header')

        <section class="relative mx-auto max-w-7xl px-4 pb-20 pt-36 sm:px-6 lg:px-8 lg:pb-24 lg:pt-44">
            <div class="max-w-5xl text-white">
                <div class="flex flex-wrap items-center gap-3 text-sm">
                    <span class="rounded-full bg-emerald-500/15 px-3 py-1 font-semibold text-emerald-300">{{ $post['category'] }}</span>
                    <span class="text-slate-300">{{ $post['published_at'] }}</span>
                    <span class="h-1 w-1 rounded-full bg-slate-500"></span>
                    <span class="text-slate-300">{{ $post['reading_time'] }}</span>
                    <span class="h-1 w-1 rounded-full bg-slate-500"></span>
                    <span class="text-slate-300">{{ $authorName }}</span>
                </div>

                <h1 class="mt-6 max-w-5xl text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-[4rem] lg:leading-[1.02]">{{ $post['title'] }}</h1>
                <p class="mt-8 max-w-3xl text-base leading-8 text-slate-200 sm:text-lg sm:leading-9">{{ $post['intro'] }}</p>
            </div>
        </section>
    </div>

    <main class="bg-stone-50">
        <section class="mx-auto max-w-7xl px-4 pb-14 sm:px-6 lg:px-8 lg:pb-18">
            <div class="grid gap-12 pt-[15px] lg:grid-cols-[minmax(0,1.55fr)_minmax(18rem,0.7fr)]">
                <article class="rounded-[1.8rem] border border-slate-200 bg-white p-7 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.18)] sm:p-10 lg:p-12">
                    <div class="border-b border-slate-100 pb-8">
                        <a href="{{ route('blog.index') }}" class="text-sm font-semibold text-emerald-700 transition hover:text-emerald-600">Назад кон блогот</a>
                        <p class="mt-5 max-w-3xl text-[1.02rem] leading-8 text-slate-600">{{ $post['excerpt'] }}</p>
                    </div>

                    @if (!empty($post['content']))
                        <div class="mt-12 max-w-3xl space-y-14">
                            <div class="rounded-[1.4rem] border border-emerald-100 bg-emerald-50/70 px-6 py-5">
                                <p class="text-base font-semibold leading-8 text-emerald-900">
                                    „Најдобро поминуваат кандидатите што аплицираат брзо, јасно и со точни контакт податоци.“
                                </p>
                            </div>

                            <div class="blog-content space-y-6 text-[1.02rem] leading-9 text-slate-600 [&_h2]:mt-12 [&_h2]:text-[2rem] [&_h2]:font-extrabold [&_h2]:tracking-tight [&_h2]:text-slate-900 [&_h3]:mt-10 [&_h3]:text-xl [&_h3]:font-bold [&_h3]:tracking-tight [&_h3]:text-slate-900 [&_p]:mt-6 [&_p]:leading-9 [&_ul]:mt-7 [&_ul]:space-y-4 [&_ul]:pl-6 [&_li]:list-disc [&_li]:pl-1 [&_a]:font-semibold [&_a]:text-emerald-700 [&_a]:underline [&_strong]:font-semibold [&_strong]:text-slate-900">
                                {!! $post['content'] !!}
                            </div>

                            <div class="rounded-[1.4rem] border border-slate-200 bg-slate-50 px-6 py-5">
                                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Корисен совет</p>
                                <p class="mt-3 text-[0.98rem] leading-8 text-slate-600">Пред објава секогаш префрли го текстот уште еднаш за да бидеш сигурен дека пораката е јасна, структурирана и лесна за читање.</p>
                            </div>
                        </div>
                    @else
                        <div class="mt-12 max-w-3xl space-y-14">
                            @foreach ($post['sections'] as $index => $section)
                                <section class="{{ $index > 0 ? 'border-t border-slate-100 pt-12' : '' }}">
                                    <h2 class="text-[2rem] font-extrabold tracking-tight text-slate-900 sm:text-[2.15rem]">{{ $section['heading'] }}</h2>
                                    <p class="mt-6 text-[1.02rem] leading-9 text-slate-600">{{ $section['content'] }}</p>

                                    @if (!empty($section['subheading']))
                                        <h3 class="mt-10 text-xl font-bold tracking-tight text-slate-900 sm:text-[1.45rem]">{{ $section['subheading'] }}</h3>
                                    @endif

                                    @if (!empty($section['points']))
                                        <ul class="mt-7 space-y-4 pl-6 text-[1.02rem] leading-9 text-slate-600">
                                            @foreach ($section['points'] as $point)
                                                <li class="list-disc pl-1">{{ $point }}</li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    @if ($index === 0)
                                        <blockquote class="mt-10 rounded-[1.4rem] border border-emerald-100 bg-emerald-50/70 px-6 py-5">
                                            <p class="text-base font-semibold leading-8 text-emerald-900">
                                                „Најдобро поминуваат кандидатите што аплицираат брзо, јасно и со точни контакт податоци.“
                                            </p>
                                        </blockquote>
                                    @endif

                                    @if (!empty($section['points']))
                                        <div class="mt-10 rounded-[1.4rem] border border-slate-200 bg-slate-50 px-6 py-5">
                                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Корисен совет</p>
                                            <p class="mt-3 text-[0.98rem] leading-8 text-slate-600">Пред аплицирање одвои една минута за да провериш дали пораката е јасна, а телефонот и градот се внесени точно.</p>
                                        </div>
                                    @endif
                                </section>
                            @endforeach
                        </div>
                    @endif
                </article>

                <aside class="space-y-5">
                    <div class="rounded-[1.6rem] border border-slate-200 bg-white p-5 shadow-[0_18px_40px_-34px_rgba(15,23,42,0.14)]">
                        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-600">Скорешни статии</p>
                        <div class="mt-5 space-y-3">
                            @foreach ($recentPosts as $recentPost)
                                <a href="{{ route('blog.show', $recentPost['slug']) }}" class="block rounded-[1.15rem] border border-slate-100 px-4 py-4 transition hover:border-emerald-200 hover:bg-white hover:shadow-[0_14px_30px_-24px_rgba(15,23,42,0.22)]">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">{{ $recentPost['published_at'] }}</p>
                                    <p class="mt-2 text-sm font-bold leading-6 text-slate-900 transition group-hover:text-emerald-700">{{ $recentPost['title'] }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-[1.6rem] border border-slate-200 bg-white p-5 shadow-[0_18px_40px_-34px_rgba(15,23,42,0.14)]">
                        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-600">Категории</p>
                        <div class="mt-5 flex flex-wrap gap-2.5">
                            @foreach ($categories as $category)
                                <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">
                                    {{ $category['name'] }}
                                    <span class="rounded-full bg-white px-2 py-0.5 text-xs text-slate-500">{{ $category['count'] }}</span>
                                </span>
                            @endforeach
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 pb-16 pt-4 sm:px-6 lg:px-8 lg:pb-20 lg:pt-8">
            <div class="mb-10">
                <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-[2rem]">Слични статии</h2>
                <p class="mt-2 text-sm text-slate-600">Прочитајте уште неколку корисни теми поврзани со работа, ангажмани и кариера.</p>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                @foreach ($relatedPosts as $relatedPost)
                    @include('partials.blog-card', ['post' => $relatedPost])
                @endforeach
            </div>
        </section>
    </main>

    @include('partials.footer')
@endsection
