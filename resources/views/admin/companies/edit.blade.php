@extends('admin.layouts.app', [
    'pageTitle' => 'Измени компанија',
    'pageDescription' => 'Ажурирајте ги податоците за избраната компанија.',
])

@section('content')
    <div class="space-y-6">
        <div class="rounded-[1.6rem] bg-white p-8 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
            <form method="POST" action="{{ route('admin.companies.update', $company) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.companies._form', ['submitLabel' => 'Зачувај промени'])
            </form>
        </div>

        <div class="rounded-[1.6rem] bg-white p-8 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-slate-900">Employer Login</h2>
                <p class="mt-1 text-sm text-slate-500">Креирајте најава за оваа компанија за пристап до employer панелот.</p>
            </div>

            @if ($errors->employerAccount->any())
                <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">
                    {{ $errors->employerAccount->first() }}
                </div>
            @endif

            @if ($company->user)
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4">
                    <p class="text-sm font-semibold text-emerald-800">Employer акаунт веќе постои.</p>
                    <p class="mt-1 text-sm text-emerald-700">Е-пошта за најава: {{ $company->user->email }}</p>
                </div>
            @else
                <form method="POST" action="{{ route('admin.companies.employer-account.store', $company) }}" class="grid gap-5 sm:grid-cols-2">
                    @csrf

                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Име</label>
                        <input type="text" value="{{ $company->name }}" disabled class="block w-full rounded-2xl border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-600">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="employer_email" class="mb-2 block text-sm font-semibold text-slate-700">Е-пошта за најава</label>
                        <input id="employer_email" name="email" type="email" value="{{ old('email') }}" required class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
                    </div>

                    <div>
                        <label for="employer_password" class="mb-2 block text-sm font-semibold text-slate-700">Лозинка</label>
                        <input id="employer_password" name="password" type="password" required class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
                    </div>

                    <div>
                        <label for="employer_password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">Потврди лозинка</label>
                        <input id="employer_password_confirmation" name="password_confirmation" type="password" required class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
                    </div>

                    <div class="sm:col-span-2">
                        <button type="submit" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-7 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
                            Create Employer Account
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection
