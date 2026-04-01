@extends('admin.layouts.app', [
    'pageTitle' => 'Нов блог пост',
    'pageDescription' => 'Креирајте нов блог пост за јавниот сајт.',
])

@section('content')
    <div class="rounded-[1.6rem] bg-white p-8 shadow-[0_20px_45px_-34px_rgba(15,23,42,0.2)]">
        <form method="POST" action="{{ route('admin.blog-posts.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.blog-posts._form', ['submitLabel' => 'Зачувај пост', 'post' => null])
        </form>
    </div>
@endsection
