@extends('layouts\app')
@section('content')

@isset($category->books)
    @foreach ($category->books as $book)
    <ul class="list-group my-3">
        <li class="list-group-item">{{ $book->name }}</li>
        <li class="list-group-item">{{ $book->categories[0]->name }}</li>
        <li class="list-group-item">{{ $book->author->name }}</li>
    </ul>
    @endforeach
@endisset

@endsection
