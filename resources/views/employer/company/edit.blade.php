@extends('employer.layouts.app', [
    'pageTitle' => 'Employer профил',
    'pageDescription' => 'Поставете default број што ќе се користи за копчето „Повикај“.',
])

@section('content')
    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="rounded-[1.6rem] bg-white p-8 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
        <form method="POST" action="{{ route('employer.company.update') }}">
            @csrf
            @method('PUT')

            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Компанија</label>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
                        {{ $company->name }}
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Основен телефон</label>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
                        {{ $company->phone }}
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <label for="call_phone" class="mb-2 block text-sm font-semibold text-slate-700">Број за повикување</label>
                    <input id="call_phone" name="call_phone" type="text" value="{{ old('call_phone', $company->call_phone ?? '') }}" placeholder="070123456 или +38970123456" class="block w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-100">
                    <p class="mt-2 text-xs text-slate-500">Овој број ќе се користи на огласите каде што е вклучено копчето „Повикај“, а не е поставен посебен број на самиот оглас. Ако го оставите празно, ќе се користи основниот телефон на компанијата.</p>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap gap-4">
                <button type="submit" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-7 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
                    Зачувај промени
                </button>
                <a href="{{ route('employer.dashboard') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-7 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    Назад
                </a>
            </div>
        </form>
    </div>
@endsection
