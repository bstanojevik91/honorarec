@extends('employer.layouts.app', [
    'pageTitle' => 'Нов оглас',
    'pageDescription' => 'Креирајте оглас како компанија.',
])

@section('content')
    <div class="mb-6 rounded-[1.6rem] border border-emerald-100 bg-emerald-50/70 px-6 py-5 text-sm text-emerald-900 shadow-[0_16px_35px_-28px_rgba(5,150,105,0.35)]">
        Наскоро ќе може и платено објавување на огласи, со што компаниите ќе добијат уште поедноставен начин за активирање и промоција на огласите.
    </div>

    <div class="rounded-[1.6rem] bg-white p-8 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
        <form method="POST" action="{{ route('employer.jobs.store') }}">
            @csrf
            @include('employer.jobs._form', ['submitLabel' => 'Објави оглас'])
        </form>
    </div>
@endsection
