@extends('layouts.app', [
    'title' => 'Блог | Honorarec.mk',
    'description' => 'Прочитајте корисни статии за работа на дневница, сезонски ангажмани, втора работа и совети за кандидати на Honorarec.mk.',
])

@section('content')
    <div class="relative isolate overflow-hidden bg-slate-950">
        <div class="absolute inset-0">
            <div class="h-full w-full bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.18),_transparent_34%),linear-gradient(180deg,_rgba(2,6,23,0.8),_rgba(2,6,23,0.94))]"></div>
        </div>

        @include('partials.header')

        <section class="relative mx-auto max-w-7xl px-4 pb-14 pt-28 sm:px-6 sm:pb-18 sm:pt-32 lg:px-8 lg:pb-20 lg:pt-44">
            <div class="grid gap-8 lg:grid-cols-[1.05fr_0.95fr] lg:items-end lg:gap-10">
                <div class="max-w-4xl text-white">
                    <p class="text-sm font-semibold uppercase tracking-[0.28em] text-emerald-300">Honorarec Блог</p>
                    <h1 class="mt-4 text-[2.1rem] font-extrabold tracking-tight sm:text-5xl lg:text-[3.5rem] lg:leading-[1.04]">Совети, новости и корисни статии за работа и ангажмани</h1>
                    <p class="mt-4 max-w-3xl text-sm leading-7 text-slate-200 sm:mt-6 sm:text-lg sm:leading-8">
                        Прочитајте практични совети за кандидати, увид во пазарот на труд и насоки како побрзо да пронајдете ангажман што ви одговара.
                    </p>
                </div>

                @if ($featuredPost)
                    <a href="{{ route('blog.show', $featuredPost['slug']) }}" class="group overflow-hidden rounded-[1.5rem] border border-white/10 bg-white/10 shadow-[0_24px_70px_-36px_rgba(15,23,42,0.85)] backdrop-blur sm:rounded-[2rem]">
                        <img src="{{ $featuredPost['image'] }}" alt="{{ $featuredPost['title'] }}" class="h-56 w-full object-cover transition duration-500 group-hover:scale-[1.03] sm:h-72">
                        <div class="p-5 text-white sm:p-6">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-300">Издвоена статија</p>
                            <h2 class="mt-3 text-xl font-bold leading-tight sm:text-2xl">{{ $featuredPost['title'] }}</h2>
                            <p class="mt-3 text-sm leading-7 text-slate-300">{{ $featuredPost['excerpt'] }}</p>
                        </div>
                    </a>
                @endif
            </div>
        </section>
    </div>

    <main class="bg-stone-50">
        <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
            <div class="mb-6 sm:mb-8">
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-[2rem]">Сите статии</h2>
                <p class="mt-2 text-sm text-slate-600">Разгледајте статии што може да ви помогнат при барање работа, аплицирање и избор на вистински ангажман.</p>
            </div>

            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3 sm:gap-6">
                @foreach ($posts as $post)
                    @include('partials.blog-card', ['post' => $post])
                @endforeach
            </div>
        </section>
    </main>

    @include('partials.footer')
@endsection
