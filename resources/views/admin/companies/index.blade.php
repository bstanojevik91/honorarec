@extends('admin.layouts.app', [
    'pageTitle' => 'Компании',
    'pageDescription' => 'Додавање, уредување и бришење компании.',
])

@section('content')
    <div class="mb-6 flex items-center justify-end">
        <a href="{{ route('admin.companies.create') }}" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:bg-emerald-500">
            Додади компанија
        </a>
    </div>

    <div class="overflow-hidden rounded-[1.6rem] bg-white shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr class="text-left text-slate-500">
                        <th class="px-6 py-4 font-semibold">Компанија</th>
                        <th class="px-6 py-4 font-semibold">Телефон</th>
                        <th class="px-6 py-4 font-semibold">Е-пошта</th>
                        <th class="px-6 py-4 font-semibold text-right">Акции</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($companies as $company)
                        @php
                            $noPublicCallToken = '__NO_PUBLIC_CALL__';
                            $companyPhones = collect(preg_split('/(?:\r\n|\r|\n|,|;|\|)+/', (string) $company->phone) ?: [])
                                ->map(fn (string $phone): string => trim($phone))
                                ->filter()
                                ->reject(fn (string $phone): bool => mb_strtoupper($phone) === $noPublicCallToken)
                                ->values();
                            $isCallPublic = ! collect(preg_split('/(?:\r\n|\r|\n|,|;|\|)+/', (string) $company->phone) ?: [])
                                ->map(fn (string $phone): string => mb_strtoupper(trim($phone)))
                                ->contains($noPublicCallToken);
                            $primaryPhone = $companyPhones->first() ?? '';
                        @endphp
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">{{ $company->name }}</div>
                                <div class="mt-1 max-w-md text-slate-500">{{ \Illuminate\Support\Str::limit($company->description, 90) }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-700">
                                <div class="font-medium">{{ $primaryPhone }}</div>
                                <div class="mt-2">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $isCallPublic ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-700' }}">
                                        {{ $isCallPublic ? 'Повикај: вклучено' : 'Повикај: исклучено' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-700">{{ $company->email }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('admin.companies.edit', $company) }}" class="rounded-full border border-slate-200 px-4 py-2 font-semibold text-slate-700 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">Измени</a>
                                    <form method="POST" action="{{ route('admin.companies.destroy', $company) }}" onsubmit="return confirm('Дали сте сигурни дека сакате да ја избришете компанијата?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-full border border-rose-200 px-4 py-2 font-semibold text-rose-700 transition hover:bg-rose-50">Избриши</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-500">Нема додадени компании.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 px-6 py-4">
            {{ $companies->links() }}
        </div>
    </div>
@endsection
