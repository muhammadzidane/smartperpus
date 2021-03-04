@extends('layouts/app')
@section('content')

<h1 class="my-5">Book Model</h1>

@can('create', \App\Models\Book::class)
    <form action="{{ route('books.store') }}" method="post">
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
                <td>{{ $book->name }}</td>
                <td>{{ $book->price }}</td>
                <td>{{ $book->author_id }}</td>
                <td>{{ $book->category_id }}</td>
            </tr>
        @endforeach

    </tbody>
</table>


@endsection
