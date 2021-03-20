@extends('layouts/app')
@section('content')

<div class="book-show">
    <div>
        <div class="book-show-cover">
            <img src="{{ url('img\book\kimetsu-no-yaiba-01.jpg') }}">
        </div>
        <div class="book-show-desk">
            <div>
                <div>Rating 1 2 3 4 5</div>
                <div>Rp{{ number_format($book->price, 0, 0 , '.') }},-</div>
                <div>Stok Buku Cetak : {{ $book->printedStock->amount }}</div>
                <div class="mt-3">
                    <div id="" class="btn btn-primary d-block my-2 badge-pill">Beli Versi Cetak</div>
                    <div id="" class="btn btn-success d-block badge-pill">Beli E-Book</div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="book-show-sinopsis">
            <h3>{{ $book->name }}</h3>
            <h5>{{ $book->authors[0]->name }}</h5>
            <div>
                <h5>Genre</h5>
                <div>
                    @foreach($book->categories as $category)
                        {{ $category->name }},
                    @endforeach
                </div>
            </div>
            <h5 class="book-show-sinhead my-2">Sinopsis :</h5>
            <p></p>
        </div>
        <div class="book-show-recomended">
            <div class="bg-dark">
                <h5>Deskripsi</h5>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Mollitia, atque? Voluptatibus impedit suscipit dolores dicta fugiat hic optio distinctio veritatis voluptatum nisi? Dolorum fuga iste cumque nobis totam, sed perspiciatis.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas assumenda reprehenderit nesciunt quis culpa facilis dolorum, sapiente quasi optio neque magni, at facere dicta itaque. Vel tenetur culpa repellendus totam?</p>
            </div>
            <div class="bg-primary">
                <h5>Ulasan</h5>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Delectus quo quas officiis necessitatibus laboriosam ipsam commodi dolorum. Sunt, inventore! Facere reiciendis ipsa veniam eligendi aut nulla officiis eos perspiciatis tempore!</p>
            </div>
        </div>
    </div>
</div>

<div class="book-recommendations">
    <h1>Rekomendasi Buku</h1>
    <div>
        <div class="books">

            @for($i = 0; $i < 10; $i++)
                <!-- <a href="{{ route('books.show', array('book' => $book->id)) }}"> -->
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
                            <span class="judul-buku">{{ $book->name }}</span>
                            <div>
                                <a href="{{ route('authors.show', array('author' => $book->authors[0]->id)) }}">{{ $book->authors[0]->name }}</a>
                            </div>
                        </div>
                        <div class="book-price">
                            <div>Rp40.000,-</div>
                        </div>
                    </div>
                </a>
            @endfor

        </div>
    </div>
</div>

@endsection
