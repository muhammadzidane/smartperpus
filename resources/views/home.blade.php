@extends('layouts/app')
@section('carousel')
    @include('layouts/carousel')
@endsection

@section('content')


<div class="kategori-pilihan px-3">

    <h3 class="text-righteous p-2">Kategori Pilihan</h3>

    <div class="text-righteous">
        <div class="kp-1">
            <h5>Komik</h5>
            <img src="{{ url('img/kategori-pilihan/komik.jpg') }}">
        </div>
        <div class="kp-2">
            <h5>Pendidikan</h5>
            <img src="{{ url('img/kategori-pilihan/pendidikan.jpg') }}">
        </div>
        <div class="kp-1">
            <h5>Sejarah</h5>
            <img src="{{ url('img/kategori-pilihan/sejarah.jpg') }}">
        </div>
        <div class="kp-2">
            <h5>Novel</h5>
            <img src="{{ url('img/kategori-pilihan/novel.jpg') }}">
        </div>
    </div>
</div>

@include('layouts.book-deals',
    array(
        'title' => 'Buku Diskon',
        'books' => \App\Models\Book::where('discount', '!=', null)->get()
    )
)

@include('layouts.book-deals',
    array(
        'title' => 'Rekomendasi Komik / Manga',
        'books' => \App\Models\Category::where('name', 'komik')->first()->books
    )
)


<div class="container discount-images">
    <div>
        <img src="{{ url('img/book/book-discount.jpg') }}">
    </div>
    <div>
        <img src="{{ url('img/book/book-discount.jpg') }}">
    </div>
    <div>
        <img src="{{ url('img/book/book-discount.jpg') }}">
    </div>
</div>

<div class="click-to-the-top">
    <button class="btn-to-the-top d-flex ml-auto"><i class="to-the-top fa fa-caret-up" aria-hidden="true"></i></button>
</div>

@endsection
