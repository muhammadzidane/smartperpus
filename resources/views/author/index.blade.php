@extends('layouts/app')
@section('content')

@include('layouts/carousel')

<h1 class="my-5 text-center">Author Index</h1>

@can('create', \App\Models\Author::class)
    <form action="{{ route('authors.store') }}" method="post">
        <div class="row my-3">
            @csrf
            <button class="btn btn-primary ml-auto" type="submit">Buat Baru</button>
        </div>
    </form>
@endcan

<table class="table">
    <thead>
    <tr>
        <th>#</th>
        <th>Nama Pengarang</th>
        <th>Karya</th>
        </tr>
    </thead>
    <tbody>

        @foreach($authors as $author)
            <tr>
                <td>{{ $author->id }}</td>
                <td>
                    <a href="{{ route('authors.show', array('author' => $author->id)) }}">{{ $author->name }}</a>
                </td>

                @if(count($author->books) > 1)
                    <td>

                        @foreach($author->books as $book)
                            <a href="{{ route('books.show', array('book' => $book->id)) }}">{{ $book->name }}</a>,
                        @endforeach

                    </td>

                    @else

                    <td>
                        <a href="{{ route('books.show', array('book' => $author->books[0]->id)) }}">{{ $author->books[0]->name  }}</a>
                    </td>

                @endif

            </tr>
        @endforeach

    </tbody>
</table>

@endsection
