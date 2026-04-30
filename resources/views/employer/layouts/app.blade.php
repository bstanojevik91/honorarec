<!DOCTYPE html>
<html lang="mk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Employer Panel | Honorarec.mk' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Manrope', 'sans-serif'],
                    },
                },
            },
        };
    </script>
</head>
<body class="bg-stone-100 font-sans text-slate-900 antialiased">
    <div class="min-h-screen lg:flex">
        <aside class="w-full bg-slate-950 text-white lg:min-h-screen lg:w-72">
            <div class="border-b border-white/10 px-6 py-6">
                <a href="{{ route('employer.dashboard') }}" class="inline-flex items-center gap-3">
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/15 bg-white/10 text-lg font-bold">H</span>
                    <div>
                        <p class="text-base font-semibold">{{ auth()->user()?->company?->name ?? 'Employer' }}</p>
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Employer Panel</p>
                    </div>
                </a>
            </div>

            <nav class="grid gap-2 px-4 py-5 text-sm">
                <a href="{{ route('employer.dashboard') }}" class="{{ request()->routeIs('employer.dashboard') ? 'bg-emerald-600 text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} rounded-xl px-4 py-3 font-medium transition">
                    Dashboard
                </a>
                <a href="{{ route('employer.jobs.index') }}" class="{{ request()->routeIs('employer.jobs.*') ? 'bg-emerald-600 text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} rounded-xl px-4 py-3 font-medium transition">
                    Мои огласи
                </a>
                <a href="{{ route('employer.company.edit') }}" class="{{ request()->routeIs('employer.company.*') ? 'bg-emerald-600 text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} rounded-xl px-4 py-3 font-medium transition">
                    Employer профил
                </a>
                <a href="{{ route('employer.applications.index') }}" class="{{ request()->routeIs('employer.applications.*') ? 'bg-emerald-600 text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} rounded-xl px-4 py-3 font-medium transition">
                    Апликанти
                </a>
            </nav>
        </aside>

        <div class="flex-1">
            <header class="border-b border-slate-200 bg-white">
                <div class="flex flex-col gap-4 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $pageTitle ?? 'Employer Panel' }}</h1>
                        @isset($pageDescription)
                            <p class="mt-1 text-sm text-slate-500">{{ $pageDescription }}</p>
                        @endisset
                    </div>

                    <div class="flex items-center gap-4">
                        <a href="{{ route('home') }}" class="text-sm font-medium text-slate-500 transition hover:text-slate-900">Види сајт</a>
                        <form method="POST" action="{{ route('employer.logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">
                                Одјави се
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="p-6">
                @if (session('status'))
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
                        {{ session('status') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
