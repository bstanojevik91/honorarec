<!DOCTYPE html>
<html lang="mk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Регистрација за компанија | Honorarec.mk</title>
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
    <div class="grid min-h-screen lg:grid-cols-[0.95fr_1.05fr]">
        <div class="hidden bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.22),_transparent_35%),linear-gradient(180deg,_rgba(2,6,23,0.78),_rgba(2,6,23,0.98))] p-10 text-white lg:flex lg:flex-col lg:justify-between">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/15 bg-white/10 text-lg font-bold">H</span>
                <span class="text-lg font-semibold tracking-wide">Хонорарец</span>
            </a>

            <div class="max-w-xl">
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-emerald-300">Регистрација за компании</p>
                <h1 class="mt-4 text-5xl font-extrabold tracking-tight">Отворете employer профил и објавувајте огласи</h1>
                <p class="mt-5 text-lg leading-8 text-slate-300">
                    Внесете ги податоците за компанијата и контакт лицето. По регистрацијата веднаш ќе имате пристап до вашиот панел за огласи и апликанти.
                </p>
            </div>
        </div>

        <div class="flex items-center justify-center px-4 py-12 sm:px-6">
            <div class="w-full max-w-3xl rounded-[2rem] bg-white p-8 shadow-[0_40px_100px_-40px_rgba(15,23,42,0.45)] sm:p-10">
                <div class="text-center">
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-emerald-600">Employer Access</p>
                    <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-900">Регистрација на компанија</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Пополнете ги основните податоци за компанијата и профилот за најава.</p>
                </div>

                @if ($errors->any())
                    <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('employer.register.store') }}" enctype="multipart/form-data" class="mt-8 space-y-8">
                    @csrf

                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Податоци за компанијата</h3>
                        <div class="mt-5 grid gap-5 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label for="company_name" class="mb-2 block text-sm font-semibold text-slate-700">Име на компанија</label>
                                <input id="company_name" name="company_name" type="text" value="{{ old('company_name') }}" required class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">
                            </div>

                            <div>
                                <label for="company_phone" class="mb-2 block text-sm font-semibold text-slate-700">Телефон</label>
                                <input id="company_phone" name="company_phone" type="text" value="{{ old('company_phone') }}" required class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">
                            </div>

                            <div>
                                <label for="company_email" class="mb-2 block text-sm font-semibold text-slate-700">Е-пошта на компанија</label>
                                <input id="company_email" name="company_email" type="email" value="{{ old('company_email') }}" required class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">
                            </div>

                            <div class="sm:col-span-2">
                                <label for="company_logo" class="mb-2 block text-sm font-semibold text-slate-700">Лого</label>
                                <input id="company_logo" name="company_logo" type="file" accept="image/*" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">
                                <p class="mt-2 text-xs text-slate-500">Опционално. Поддржани се слики до 5MB.</p>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="company_description" class="mb-2 block text-sm font-semibold text-slate-700">Опис на компанијата</label>
                                <textarea id="company_description" name="company_description" rows="4" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">{{ old('company_description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Профил за најава</h3>
                        <div class="mt-5 grid gap-5 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label for="contact_name" class="mb-2 block text-sm font-semibold text-slate-700">Контакт лице</label>
                                <input id="contact_name" name="contact_name" type="text" value="{{ old('contact_name') }}" required class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">
                            </div>

                            <div class="sm:col-span-2">
                                <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Е-пошта за најава</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">
                            </div>

                            <div>
                                <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Лозинка</label>
                                <input id="password" name="password" type="password" required class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">
                            </div>

                            <div>
                                <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">Потврди лозинка</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" required class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-100">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-600 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
                            Регистрирај компанија
                        </button>

                        <p class="text-center text-sm text-slate-600">
                            Веќе имате профил?
                            <a href="{{ route('employer.login') }}" class="font-semibold text-emerald-700 transition hover:text-emerald-600">Најавете се тука</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
