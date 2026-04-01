@extends('admin.layouts.app', [
    'pageTitle' => 'Измени компанија',
    'pageDescription' => 'Ажурирајте ги податоците за избраната компанија.',
])

@section('content')
    <div class="rounded-[1.6rem] bg-white p-8 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
        <form method="POST" action="{{ route('admin.companies.update', $company) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.companies._form', ['submitLabel' => 'Зачувај промени'])
        </form>
    </div>
@endsection
