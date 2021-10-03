<div class="books {{ $class_books ?? '' }}">
    @foreach ($books as $book)
        <div class="book" data-id="{{ $book->id }}">
            @if(isset($book->discount) && $book->discount != 0)
                <div class="book-persentage-discount">{{ round(($book->discount / $book->price) * 100) }}%</div>
            @endif
            <div class="text-center">
                <img class="book-image" src="{{ asset('storage/books/' . $book->image )  }}">
            </div>
            <div class="p-2 d-flex flex-column justify-content-between h-100">
                <div>
                    <a class="tbreak-all" href="{{ route('books.show', array('book' => $book->id)) }}">{{ Str::limit($book->name, 40, '...') }}</a>
                </div>
                <div class="mt-2">
                    <small class="d-block text-grey">{{ Str::limit($book->author->name, 40, '...') }}</small>
                </div>
            </div>
            <div class="mt-auto p-2">
                @if ($book->ratings)
                    @if ($book->book_user()->where('payment_status', 'arrived')->count() != 0)
                        <div class="{{ !$book->ratings()->exists() ? 'float-left' : '' }}">
                            <small class="mt-1 d-block text-grey">{{ $book->book_user()->where('payment_status', 'arrived')->count() }} Terjual</small>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between">
                        <div class="d-flex">
                            <div class="rating">
                                <div>
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $book->ratings()->avg('rating'))
                                            <i class="fa fa-star rating-star"></i>

                                        @elseif ($i >= $book->ratings()->avg('rating') + 1)
                                            <i class="far fa-star rating-star"></i>
                                        @else
                                            <i class="fas fa-star-half-alt rating-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                        @auth
                            <div class="add-to-wishlist">
                                @if (App\Models\Wishlist::where('book_id', $book->id)->where('user_id', Auth::id())->first())
                                <i class="add-to-my-wishlist fas fa-heart" data-id="{{ $book->id }}"></i>
                                @else

                                <i class="add-to-my-wishlist far fa-heart" data-id="{{ $book->id }}"></i>
                                @endif
                            </div>
                        @endauth
                    </div>
                @endif
                <a class="book-show-link" href="{{ route('books.show', array('book' => $book->id)) }}"></a>
            </div>
            <div class="book-price">
                @if(isset($book->discount) && $book->discount != 0)
                    <div>
                        <small class="discount-line-through">Rp{{ number_format($book->price, 0, 0, '.') }}</small>
                        <span>Rp{{ number_format(($book->price - $book->discount), 0, 0, '.') }}</span>
                    </div>

                @else
                    <div class="text-left">
                        <span class="ml-2">Rp{{ number_format(($book->price - $book->discount), 0, 0, '.') }}</span>
                    </div>
                @endif
            </div>
        </div>
    @endforeach

</div>
