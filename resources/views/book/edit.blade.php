@extends('layouts/app')
@section('content')

<div style="margin: 9rem 0"> {{-- Nanti hapus--}}

    <h1 class="my-5 text-center">Edit Book</h1>

    <form action="{{ route('books.update', array('book' => $book->id)) }}" method="post" class="w-50 my-5" enctype="multipart/form-data">
        @include('book.form', array('submit_button' => 'Edit'))
        @method('patch')
    </form>

</div>

@endsection
