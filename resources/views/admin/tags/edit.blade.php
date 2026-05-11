@extends('admin.layouts.app', [
    'pageTitle' => 'Измени таг',
    'pageDescription' => 'Ажурирај ги податоците за тагот.',
])

@section('content')
    <div class="rounded-[1.6rem] bg-white p-8 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
        <form method="POST" action="{{ route('admin.tags.update', $tag) }}">
            @csrf
            @method('PUT')
            @include('admin.tags._form', ['submitLabel' => 'Зачувај промени'])
        </form>
    </div>
@endsection
