@extends('layouts/app')
@section('content')

@include('layouts/carousel')

<h2 class="text-righteous my-2">Buku Terlaris</h2>

@if(session('pesan'))
<div class="alert alert-primary" role="alert">
    <strong>{{ session('pesan') }}</strong>
</div>
@endif

<div class="books">

    @foreach($books as $book)
        <a href="{{ route('books.show', array('book' => $book->id)) }}">
            <div class="book">
                <div class='book-cover'>
                    <div class="rating">
                        @for($j = 1; $j <= 5; $j++)
                            <div>
                                <i class="fa fa-star rating-star" aria-hidden="true"></i>
                            </div>
                        @endfor
                    </div>
                    <div class="gambar-buku">
                        <img src="{{ url('img/book/jujutsu-kaisen-02.jpg') }}" alt="" srcset="">
                    </div>
                </div>
                <div class="desk-book">
                    <div>
                        <span class="judul-buku">{{ $book->name }}</span>
                        <span>Rp{{ $book->price }}</span>
                    </div>
                    <div class="author">
                        <a href="{{ route('authors.show', array('author' => $book->authors[0]->id)) }}">{{ $book->authors[0]->name }}</a>
                    </div>
                </div>
            </div>
        </a>
    @endforeach

</div>

<!-- <a class="btn btn-primary float-right my-3" href="{{ route('books.create') }}" role="button">Buat Buku</a> -->

@can('create', \App\Models\Book::class)
<form action="{{ route('books.store') }}" method="post">
    <div class="row my-3">
        @csrf
        <button class="btn btn-primary ml-auto" type="submit">Buat Baru</button>
    </div>
</form>
@endcan


<!-- <table class="table">
    <thead>
    <tr>
        <th>#</th>
        <th>Nama</th>
        <th>Harga</th>
        <th>Author</th>
        <th>Kategori</th>
        </tr>
    </thead>
    <tbody>
        @foreach($books as $book)
            <tr>
                <td>{{ $book->id }}</td>
                <td>
                    <a href="{{ route('books.show', array('book' => $book->id)) }}">{{ $book->name }}</a>
                </td>
                <td>{{ $book->price }}</td>

                @foreach($book->authors as $author)
                    <td>
                        <a href="{{ route('authors.show', array('author' => $book->authors[0]->id)) }}">{{ $author->name }}</a>
                    </td>
                @endforeach

                @if(count($book->categories) > 1)
                    <td>
                        @foreach($book->categories as $categories)
                            {{ $categories->name }},
                        @endforeach
                    </td>
                @else
                    <td>
                        @foreach($book->categories as $categories)
                            {{ $categories->name }}
                        @endforeach
                    </td>
                @endif

            </tr>
        @endforeach

    </tbody>
</table> -->

@endsection
