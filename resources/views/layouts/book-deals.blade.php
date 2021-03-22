@php $counter = 0; @endphp {{-- Membuat counter untuk Routes --}}
@foreach($book_types as $title_book => $books)

    <div class="book-deals my-5">
    <div class="position-relative">
        <h3 class="book-deals-title mr-3">{{ $title_book }}</h3>
            <a class="show-all" href="{{ route('categories', array('category' => $book_categories[$counter++])) }}">
                Lihat Semua
            </a>
        <i class="arrow-lihat-semua far fa-arrow-alt-circle-right"></i>
    </div>

    @if(session('pesan'))
    <div class="alert alert-primary" role="alert">
        <strong>{{ session('pesan') }}</strong>
    </div>
    @endif

    <div class="books">
            @foreach ($books as $book)
            <a class="book-show-link" href="{{ route('books.show', array('book' => $book->id)) }}">
                <div class="book">
                        @isset($book->discount)
                            <div class="book-persentage-discount">{{ round(($book->discount / $book->price) * 100) }}%</div>
                        @endisset
                        <div class='book-cover'>
                            <div class="rating">

                                @for ($i=0; $i < 5 ; $i++)
                                    @for ($j=0; $j < 10; $j++)

                                        @php
                                            $rating_book = $book->rating;
                                            $rating      = $i . '.' . $j;
                                        @endphp

                                        @if ((float) $rating == $rating_book)
                                            @for ($k=1; $k <= 9; $k++)
                                                @if ($j == $k)
                                                    @for ($l=0; $l < $i ; $l++)
                                                        <div>
                                                            <i class="fa fa-star rating-star" aria-hidden="true"></i>
                                                        </div>
                                                    @endfor

                                                    <div>
                                                        <i class="fa fa-star-half rating-star" aria-hidden="true"></i>
                                                    </div>
                                                @endif

                                            @endfor

                                            @if ($j == 0)
                                                @for ($l=0; $l < $i ; $l++)
                                                    <div>
                                                        <i class="fa fa-star rating-star" aria-hidden="true"></i>
                                                    </div>
                                                @endfor
                                            @endif
                                        @endif
                                    @endfor
                                @endfor

                                @if($rating_book == 5.0)
                                    @for($i = 0; $i < 5; $i++)
                                    <div>
                                        <i class="fa fa-star rating-star" aria-hidden="true"></i>
                                    </div>
                                    @endfor
                                @endif

                                <div class="rating-number">{{ $book->rating }}</div>
                            </div>
                            <div class="gambar-buku">
                                <img src="{{ url('img/book/' . $book->image )  }}">
                            </div>
                        </div>
                        <div class="desk-book">
                            <a href="{{ route('books.show', array('book' => $book->id)) }}">{{ $book->name  }}</a>
                        </div>
                        <div class="book-price">
                            <div>
                                @isset($book->discount)
                                    <small class="discount-line-through text-success">Rp{{ number_format($book->price, 0, 0, '.') }}</small>
                                @endisset
                                <span>Rp{{ number_format(($book->price - $book->discount), 0, 0, '.') }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endforeach

<div class="discount-images">
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
