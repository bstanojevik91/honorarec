@extends('admin.layouts.app', [
    'pageTitle' => 'Нов таг',
    'pageDescription' => 'Креирај нов таг за огласи.',
])

@section('content')
    <div class="rounded-[1.6rem] bg-white p-8 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
        <form method="POST" action="{{ route('admin.tags.store') }}">
            @csrf
            @include('admin.tags._form', ['submitLabel' => 'Зачувај таг'])
        </form>
    </div>
@endsection
