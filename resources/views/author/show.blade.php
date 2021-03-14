@extends('layouts/app')
@section('content')

<div style="margin: 9rem 0"> {{-- Nanti hapus--}}

    <div class="my-5 text-center h1">
        Author {{ $author->name }}
        <a class="btn btn-success" href="{{ route('authors.edit', array('author' => $author->id)) }}" role="button">Edit</a>
    </div>

    <div class="card-columns">
        @forelse($author->books as $key => $book)
            <a class="text-decoration-none text-body" href="{{ route('books.show', array('book' => $author->books[$key]->id)) }}">
                <div class="card">
                    <img class="card-img-top" src="holder.js/100x180/" alt="">
                    <div class="card-body">
                        <h4 class="card-title">{{ $book->name }}</h4>
                        <p class="card-text">Sinopsis :</p>
                        <p class="card-text">Text</p>
                    </div>
                </div>
            </a>
        @empty
            <div class="alert alert-primary" role="alert">
                <strong>Author belum di isi buku-buku</strong>
            </div>
        @endforelse
    </div>

</div>

@endsection
