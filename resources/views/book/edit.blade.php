@extends('layouts/app')
@section('content')

@if (session('pesan'))
    <div class="alert alert-primary" role="alert">
        <strong>{{ session('pesan') }}</strong>
    </div>
@endif

<div id="pesan" class="alert alert-warning d-none" role="alert">
    <strong></strong>
</div>

<div class="register-user py-4">
    <div id="book-create" class="form-register w-75 mx-auto">
    <form id="book-edit-form" data-id="{{ $book->id }}" enctype="multipart/form-data"
      action="{{ route('books.update', array('book' => $book->id)) }}" method="POST">
        @include('book.form',
            array(
                'title'       => 'Edit Buku',
                'button_id'   => 'book-edit-submit',
                'button_text' => 'Edit',
                )
        )
        @method('PATCH')
        @csrf
    </form>
</div>

@endsection
