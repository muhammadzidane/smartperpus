@extends('layouts/app')
@section('content')

<div class="container-fluid">
    @include('layouts/carousel')
</div>

<div class="book-index-recommended px-3">

    <h3 class="text-righteous p-2">Kategori Pilihan</h3>

    <div class="text-righteous">
        <div class="red">
            <h5>Komik</h5>
            <img src="{{ url('img/kategori-pilihan/komik.jpg') }}">
        </div>
        <div class="blue">
            <h5>Pendidikan</h5>
            <img src="{{ url('img/kategori-pilihan/pendidikan.jpg') }}">
        </div>
        <div class="red">
            <h5>Biografi</h5>
            <img src="{{ url('img/kategori-pilihan/biografi.jpg') }}">
        </div>
        <div class="blue">
            <h5>Sejarah</h5>
            <img src="{{ url('img/kategori-pilihan/sejarah.jpg') }}">
        </div>
        <div class="red">
            <h5>Fantasi</h5>
            <img src="{{ url('img/kategori-pilihan/fantasi.jpg') }}">
        </div>
        <div class="blue">
            <h5>Novel</h5>
            <img src="{{ url('img/kategori-pilihan/novel.jpg') }}">
        </div>
    </div>
</div>

<div class="best-seller-books">
    <div>
        <h3 class="mr-3">Buku Diskon</h3>
        <div class="discount-time">
            <div>00</div>
            <div>00</div>
            <div>00</div>
        </div>
        <a class="show-all" href="#">Lihat Semua <i class="far fa-arrow-alt-circle-right"></i></a>
    </div>


    @if(session('pesan'))
    <div class="alert alert-primary" role="alert">
        <strong>{{ session('pesan') }}</strong>
    </div>
    @endif

    <div class="books">

        @for($i = 0; $i < 6; $i++)
        <a>
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
                        <img src="{{ url('img/book/jujutsu-kaisen-02.jpg') }}">
                    </div>
                </div>
                <div class="desk-book">
                    <div>Jujutsu Kaisen</div>
                    <div>
                        <a href="">Suneo</a>
                    </div>
                </div>
                <div class="book-price">
                    <div>Rp40.000</div>
                </div>
            </div>
        </a>
        @endfor

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
</div>

<div class="best-seller-books my-5">
    <div>
        <h3>Best Seller</h3>
        <a class="show-all" href="#">Lihat Semua <i class="far fa-arrow-alt-circle-right"></i></a>
    </div>

    @if(session('pesan'))
    <div class="alert alert-primary" role="alert">
        <strong>{{ session('pesan') }}</strong>
    </div>
    @endif

    <div class="books">

        @for($i = 0; $i < 6; $i++)
        <a>
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
                        <img src="{{ url('img/book/jujutsu-kaisen-02.jpg') }}">
                    </div>
                </div>
                <div class="desk-book">
                    <div>Jujutsu Kaisen</div>
                    <div>
                        <a href="">Suneo</a>
                    </div>
                </div>
                <div class="book-price">
                    <div>Rp40.000</div>
                </div>
            </div>
        </a>
        @endfor

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
</div>

<div class="best-seller-books my-5">
    <div>
        <h3>Rekomendasi Komik</h3>
        <a class="show-all" href="#">Lihat Semua <i class="far fa-arrow-alt-circle-right"></i></a>
    </div>

    @if(session('pesan'))
    <div class="alert alert-primary" role="alert">
        <strong>{{ session('pesan') }}</strong>
    </div>
    @endif

    <div class="books">

        @for($i = 0; $i < 6; $i++)
        <a>
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
                        <img src="{{ url('img/book/jujutsu-kaisen-02.jpg') }}">
                    </div>
                </div>
                <div class="desk-book">
                    <div>Jujutsu Kaisen</div>
                    <div>
                        <a href="">Suneo</a>
                    </div>
                </div>
                <div class="book-price">
                    <div>Rp40.000</div>
                </div>
            </div>
        </a>
        @endfor

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
</div>


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
                <a href="{{ route('authors.show', array('author' => $book->author->id)) }}">{{ $author->name }}</a>
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
