@extends('layouts.app')
@section('content')
<div class="register-user py-4">
    <div id="book-create" class="form-register w-75 mx-auto">
        <form id="form-register" enctype="multipart/form-data" action="{{ route('books.store') }}" method="POST">
            @csrf
            @include('book.form', array( 'title' => 'Tambah Buku Pada Buku', 'button_text' => 'Tambah'))
        </form>
    </div>
</div>
<script src="{{ asset('js/helper-functions.js') }}"></script>
@endsection

