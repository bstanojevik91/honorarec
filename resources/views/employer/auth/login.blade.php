<!DOCTYPE html>
<html lang="mk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employer Login | Honorarec.mk</title>
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
<body class="min-h-screen bg-slate-950 font-sans text-slate-900 antialiased">
    <div class="grid min-h-screen lg:grid-cols-2">
        <div class="hidden bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.22),_transparent_35%),linear-gradient(180deg,_rgba(2,6,23,0.78),_rgba(2,6,23,0.98))] p-10 text-white lg:flex lg:flex-col lg:justify-between">
            <a href="{{ route('home') }}" class="inline-flex items-center">
                <img src="{{ asset('images/honorarec-logo.png') }}" alt="Хонорарец" class="h-14 w-auto max-w-[18rem] object-contain lg:h-16">
            </a>

            <div class="max-w-xl">
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-emerald-300">За компании</p>
                <h1 class="mt-4 text-5xl font-extrabold tracking-tight">Панел за компании што објавуваат огласи</h1>
                <p class="mt-5 text-lg leading-8 text-slate-300">
                    Најавете се за да објавувате нови огласи, да ги менувате постојните и да ги следите пристигнатите апликации.
                </p>
            </div>
        </div>

        <div class="flex items-center justify-center px-4 py-12 sm:px-6">
            <div class="w-full max-w-md">
                <div class="mb-5 flex min-h-11 items-center md:hidden">
                    <a
                        href="{{ route('home') }}"
                        class="inline-flex min-h-11 items-center gap-2 px-1 py-2 text-sm font-semibold text-slate-100 transition hover:text-white active:opacity-80"
                    >
                        <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5" aria-hidden="true">
                            <path fill-rule="evenodd" d="M11.78 4.22a.75.75 0 010 1.06L7.06 10l4.72 4.72a.75.75 0 11-1.06 1.06l-5.25-5.25a.75.75 0 010-1.06l5.25-5.25a.75.75 0 011.06 0z" clip-rule="evenodd" />
                        </svg>
                        <span>Врати се назад</span>
                    </a>
                </div>

                <div class="rounded-[2rem] bg-white p-8 shadow-[0_40px_100px_-40px_rgba(15,23,42,0.45)]">
                <div class="text-center">
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-emerald-600">Employer Access</p>
                    <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-900">Најава за компанија</h2>
                </div>

                @if (session('status'))
                    <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('employer.login.store') }}" class="mt-8 space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Е-пошта</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Лозинка</label>
                        <input id="password" name="password" type="password" required class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">
                    </div>

                    <label class="flex items-center gap-3 text-sm text-slate-600">
                        <input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-100">
                        Запомни ме
                    </label>

                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-600 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
                        Најави се
                    </button>
                </form>

                <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-center">
                    <p class="text-sm text-slate-600">Немате employer профил?</p>
                    <a href="{{ route('employer.register') }}" class="mt-3 inline-flex items-center justify-center rounded-full border border-emerald-200 px-5 py-2.5 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-50">
                        Регистрирај компанија
                    </a>
                </div>
            </div>
            </div>
        </div>
    </div>
</body>
</html>
