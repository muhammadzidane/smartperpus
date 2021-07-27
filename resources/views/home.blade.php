@extends('layouts/app')

@section('carousel')
@include('layouts/carousel')
@endsection


@section('content')

@if (session('pesan'))
<div class="alert alert-primary" role="alert">
    <strong>{{ session('pesan') }}</strong>
</div>
@endif

<div class="kategori-pilihan px-3">
    <h3 class="text-righteous p-2">Kategori Pilihan</h3>

    <div class="text-righteous overflow-auto">
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
'books' => \App\Models\Book::where('discount', '!=', null)->get()->take(12),
)
)

@include('layouts.book-deals',
array(
'title' => 'Rekomendasi Komik / Manga',
'books' => \App\Models\Book::where('category_id', 1)->get()->take(9),
)
)
@endsection
