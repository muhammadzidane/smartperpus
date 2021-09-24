@extends('layouts/app')

@section('carousel')
    @include('layouts/carousel')
@endsection


@section('content')

<div class="kategori-pilihan mt-3 p-3">
    <h4 class="mb-4">Kategori Pilihan</h4>

    <div class="d-flex justify-content-center">
        <a href="{{ route('books.index', array('category' => array(1))) }}" class="kp-1">
            <h5>Komik</h5>
            <img src="{{ asset('img/kategori-pilihan/komik.jpg') }}">
        </a>
        <a href="{{ route('books.index', array('category' => array(12))) }}" class="kp-2">
            <h5>Pendidikan</h5>
            <img src="{{ asset('img/kategori-pilihan/pendidikan.jpg') }}">
        </a>
        <a href="{{ route('books.index', array('category' => array(6))) }}" class="kp-1">
            <h5>Sejarah</h5>
            <img src="{{ asset('img/kategori-pilihan/sejarah.jpg') }}">
        </a>
        <a href="{{ route('books.index', array('category' => array(2))) }}" class="kp-2">
            <h5>Novel</h5>
            <img src="{{ asset('img/kategori-pilihan/novel.jpg') }}">
        </a>
    </div>
</div>

<div>
    @include('layouts.book-deals',
    array(
    'title' => 'Buku Diskon',
    'books' => \App\Models\Book::where('discount', '!=', 0)->get()->take(12),
    'search_url' => '',
    )
    )
</div>

@foreach ($books as $book)

@include('layouts.book-deals',
array(
'title' => $book['title'],
'books' => $book['data']->books->take(12),
'search_url' => $book['search_url'],
)
)
@endforeach

<div class="mt-5">
    <div>wkwk</div>
</div>
@endsection
