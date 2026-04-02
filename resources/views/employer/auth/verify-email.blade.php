<!DOCTYPE html>
<html lang="mk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Потврда на е-пошта | Honorarec.mk</title>
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
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-emerald-300">Потврда на компанија</p>
                <h1 class="mt-4 text-5xl font-extrabold tracking-tight">Потврдете ја е-поштата за да го активирате профилот</h1>
                <p class="mt-5 text-lg leading-8 text-slate-300">
                    Испративме порака на вашата e-пошта. Кликнете на линкот во пораката за да ја завршите регистрацијата и да продолжите кон employer панелот.
                </p>
            </div>
        </div>

        <div class="flex items-center justify-center px-4 py-12 sm:px-6">
            <div class="w-full max-w-xl rounded-[2rem] bg-white p-8 shadow-[0_40px_100px_-40px_rgba(15,23,42,0.45)]">
                <div class="text-center">
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-emerald-600">Проверка на е-пошта</p>
                    <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-900">Потврдете ја регистрацијата</h2>
                    <p class="mt-4 text-sm leading-7 text-slate-600">
                        Откако ќе кликнете на линкот од пораката, автоматски ќе бидете вратени и ќе добиете потврда дека регистрацијата е успешна.
                    </p>
                </div>

                @if (session('status'))
                    <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="mt-8 space-y-4">
                    <form method="POST" action="{{ route('employer.verification.send') }}">
                        @csrf
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-600 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
                            Испрати повторно линк за потврда
                        </button>
                    </form>

                    <form method="POST" action="{{ route('employer.logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-200 px-6 py-3.5 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                            Назад кон најава
                        </button>
                    </form>

                    <form method="POST" action="{{ route('employer.logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-sm font-medium text-slate-500 transition hover:text-slate-900">
                            Одјави се
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
