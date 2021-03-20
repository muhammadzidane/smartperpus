@extends('layouts/app')
@section('content')

<div class="container-fluid">
    @include('layouts/carousel')
</div>

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
            <h5>Biografi</h5>
            <img src="{{ url('img/kategori-pilihan/biografi.jpg') }}">
        </div>
        <div class="kp-2">
            <h5>Sejarah</h5>
            <img src="{{ url('img/kategori-pilihan/sejarah.jpg') }}">
        </div>
        <div class="kp-1">
            <h5>Fantasi</h5>
            <img src="{{ url('img/kategori-pilihan/fantasi.jpg') }}">
        </div>
        <div class="kp-2">
            <h5>Novel</h5>
            <img src="{{ url('img/kategori-pilihan/novel.jpg') }}">
        </div>
    </div>
</div>

@include('layouts/book-deals')


{{-- <div class="a">
    <div class="a-1">
        <img src="{{ url('img/book/book-discount.jpg') }}">
    </div>
    <div class="a-2">
        <img src="{{ url('img/book/book-discount.jpg') }}">
    </div>
    <div class="a-3">
        <img src="{{ url('img/book/book-discount.jpg') }}">
    </div>
</div> --}}



@endsection
