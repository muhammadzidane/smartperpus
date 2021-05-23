@extends('layouts.app')
@section('content')
    @include('layouts.books', array('books' => \App\Models\Book::get()))
@endsection
