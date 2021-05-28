@extends('layouts/app')
@section('content')

@if (session('pesan'))
    <div class="alert alert-primary" role="alert">
        <strong>{{ session('pesan') }}</strong>
    </div>
@endif

<div class="register-user py-4">


    <div id="book-create" class="form-register w-75 mx-auto">
    <form id="form-register" multiple="multiple" enctype="multipart/form-data"
      action="{{ route('books.update', array('book' => $book->id)) }}" method="POST">
        @include('book.form', array( 'title' => 'Edit Buku', 'button_text' => 'Edit'))
        @method('PATCH')
        @csrf
    </form>
</div>
<script src="{{ asset('js/helper-functions.js') }}"></script>

@endsection
