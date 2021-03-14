@extends('layouts/app')
@section('content')

<h1 class="text-center my-5">Create Book</h1>

<form action="{{ route('books.store') }}" class="my-5 w-50" method="post" enctype="multipart/form-data">
    @include('book.form', array('submit_button' => 'Buat'))
</form>

@endsection
