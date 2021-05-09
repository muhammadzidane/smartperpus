<div class="books">
    @foreach ($books as $book)
        <div class="book" data-id="{{ $book->id }}">
            @isset($book->discount)
                <div class="book-persentage-discount">{{ round(($book->discount / $book->price) * 100) }}%</div>
            @endisset
            <div class='book-cover'>

                  <div class="gambar-buku">
                    <img src="{{ url('img/book/' . $book->image )  }}">
                </div>
            </div>
            <div class="desk-book">
                <div>
                    <a class="tbreak-all" href="{{ route('books.show', array('book' => $book->id)) }}">{{ $book->name  }}</a>
                </div>
            </div>
            <div class="rating-and-author">
                <div>
                    <a class="text-grey" href="{{ route('books.show', array('book' => $book->id)) }}">{{ $book->authors[0]->name  }}</a>
                </div>
                <div class="d-flex">
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
                    <div class="add-to-wishlist">
                        <i class="far fa-heart"></i>
                        <div class="wishlist-text">
                            <div>Tambahkan pada wishlist</div>
                        </div>
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
            <a class="book-show-link" href="{{ route('books.show', array('book' => $book->id)) }}"></a>
        </div>
    @endforeach
</div>
