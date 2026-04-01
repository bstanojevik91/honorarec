@extends('admin.layouts.app', [
    'pageTitle' => 'Нов оглас',
    'pageDescription' => 'Креирајте нов оглас за работа.',
])

@section('content')
    <div class="rounded-[1.6rem] bg-white p-8 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
        <form method="POST" action="{{ route('admin.jobs.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.jobs._form', ['submitLabel' => 'Зачувај оглас'])
        </form>
    </div>
@endsection
