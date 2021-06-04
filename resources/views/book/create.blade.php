@extends('layouts.app')
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
        <form id="book-create-form" enctype="multipart/form-data" action="{{ route('books.store') }}" method="POST">
            @csrf
            @include('book.form',
                array(
                    'title'       => 'Tambah Buku Pada Buku',
                    'button_id'   => 'book-create-submit',
                    'button_text' => 'Tambah',
                )
            )
        </form>
    </div>
</div>
<script src="{{ asset('js/helper-functions.js') }}"></script>
@endsection

