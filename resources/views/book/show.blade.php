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
                <div>Harga 40000</div>
                <div>stock : 50</div>
                <div>Button Beli</div>
            </div>
        </div>
    </div>
    <div>
        <div class="book-show-sinopsis">
            <h3>wkwkwkwkwkwkwkwkwkwkwkdqwdqwkd qodwkwqokodqw wq</h3>
            <h5>Nama Pengarang</h5>
            <div>
                <h4>Genre</h4>
                <div>Action, Drama, Crime</div>
            </div>

            <h5 class="book-show-sinhead my-2">Sinopsis :</h5>
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Fugiat debitis voluptas perferendis omnis odio voluptatibus quaerat eum maiores suscipit quas expedita, culpa, aliquid maxime laborum consectetur assumenda officiis. Deserunt, quos. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eaque nihil natus voluptatem velit animi ipsum. Solut</p>
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
                <a href="{{ route('books.show', array('book' => $book->id)) }}">
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
                            <div>
                                <span class="judul-buku">{{ $book->name }}</span>
                                <span>Rp{{ $book->price }}</span>
                            </div>
                            <div class="author">
                                <a href="{{ route('authors.show', array('author' => $book->authors[0]->id)) }}">{{ $book->authors[0]->name }}</a>
                            </div>
                        </div>
                    </div>
                </a>
            @endfor

        </div>
    </div>
</div>

<!-- <div style="margin: 9rem 0"> {{-- Nanti hapus--}} -->

    <!-- <h1 class="my-5 text-center">Book Show</h1> -->

    <!-- <form class="d-flex my-3" action="{{ route('books.destroy', array('book' => $book->id)) }}" method="post">
        @method('delete')
        @csrf

        <div class="ml-auto">
            <a class="btn btn-success mr-1" href="{{ route('books.edit', array('book' => $book->id)) }}">Edit</a>
            <button class="btn btn-danger" type="submit">Hapus</button>
        </div>
    </form> -->

    <!-- <div class="card border-primary">
        <img class="card-img-top" src="holder.js/100px180/" alt="">
        <div class="card-header">
            <h4 class="card-title">{{ $book->name }}</h4>
            <p>{{ $book->image }}</p>
        </div>
        <div class="card-body">
            <div class="my-1">
                <h5 class="d-inline">Pengarang :</h5>
                <span><b>{{ $book->authors[0]->name }}</b></span>
            </div>
            <h5 class="d-inline">Kategori :</h5>

            @if(count($book->categories) > 1)
                @foreach($book->categories as $categories)
                    <span>{{ $categories->name }},</span>
                @endforeach

                @else
                    @foreach($book->categories as $categories)
                        <span>{{ $categories->name }}</span>
                    @endforeach
            @endif

            <div class="my-1">
                <h5 class="d-inline">Harga :</h5>
                <span>{{ $book->price }}</span>
            </div>

            <h5 class="my-3">Sinopsis :</h5>

            <p class="card-text">
                {{ $book->synopsis->text }}
            </p>
        </div>
    </div> -->
<!-- </div> -->

@endsection
