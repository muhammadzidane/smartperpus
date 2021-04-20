<div class="books">
    @forelse ($books as $book)
        <a class="book-show-link" href="{{ route('books.show', array('book' => $book->id)) }}">
            <div class="book">
                @isset($book->discount)
                    <div class="book-persentage-discount">{{ round(($book->discount / ($book->price + $book->discount)) * 100) }}%</div>
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
                <div>
                    <div class="add-to-wishlist">
                        <div>
                            <i class="far fa-heart"></i>
                        </div>
                    </div>
                </div>
                <div class="book-price">
                    <div>
                        @isset($book->discount)
                            <small class="discount-line-through text-success">{{ rupiah_format($book->price + $book->discount) }}</small>
                        @endisset
                        <sn>{{ rupiah_format($book->price) }}</sn>
                    </div>
                </div>
            </div>
        </a>
        @empty
        <div class="data-not-found d-flex w-100">
            <div>
                <img class="img-no-data" src="{{ asset('img/no-data.png') }}">
            </div>
            <div class="my-auto ml-4">
                <h4>Hasil tidak ditemukan</h4>
                <div class="text-grey">Mohon untuk coba kata kunci lainnya</div>
            </div>
        </div>

        <!-- Test -->
        <h4 class="mt-c">Yang mungkin anda suka</h4>
        <div class="books">
            @foreach (\App\Models\Book::get()->take(12) as $book)
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
                    <div>
                        <div class="add-to-wishlist">
                            <div>
                                <i class="far fa-heart"></i>
                            </div>
                        </div>
                    </div>
                    <div class="book-price">
                        <div>
                            @isset($book->discount)
                                <small class="discount-line-through text-success">Rp{{ number_format($book->price, 0, 0, '.') }}</small>
                            @endisset
                            <span>Rp{{ number_format(($book->price), 0, 0, '.') }}</span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    @endforelse
</div>
