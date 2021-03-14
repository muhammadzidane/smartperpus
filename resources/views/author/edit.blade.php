@extends('layouts/app')
@section('content')

<div style="margin: 9rem 0"> {{-- Nanti hapus--}}

    <h1 class="my-5 text-center">Edit Author {{ $author->name }}</h1>

    <form action="{{ route('authors.update', array('author' => $author->id)) }}"
        method="post" class="w-50 my-5" enctype="multipart/form-data">

        <div class="form-group">
          <label for="">Nama Author</label>
          <input type="text" class="form-control" name="name" value="{{ old('name') ?? $author->name }}">
        </div>

        @error('name')
            <div>
                <small class="text-danger">Nama Author {{ $message }}</small>
            </div>
        @enderror

        @csrf
        @method('patch')

        <button class="btn btn-primary" type="submit">Edit</button>
    </form>

</div>

@endsection
