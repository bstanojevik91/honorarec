@extends('admin.layouts.app', [
    'pageTitle' => 'Нова компанија',
    'pageDescription' => 'Внесете ги основните информации за компанијата.',
])

@section('content')
    <div class="rounded-[1.6rem] bg-white p-8 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
        <form method="POST" action="{{ route('admin.companies.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.companies._form', ['submitLabel' => 'Зачувај компанија'])
        </form>
    </div>
@endsection
