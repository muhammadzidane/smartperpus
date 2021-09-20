<div class="books {{ $class_books ?? '' }}">
    @foreach ($books as $book)
    <div class="book" data-id="{{ $book->id }}">
        @if(isset($book->discount) && $book->discount != 0)
        <div class="book-persentage-discount">{{ round(($book->discount / $book->price) * 100) }}%</div>
        @endif
        <div class="text-center">
            <img class="book-image" src="{{ asset('storage/books/' . $book->image )  }}">
        </div>
        <div class="desk-book">
            <div>
                @if (strlen($book->name) > 40)
                <a class="tbreak-all" href="{{ route('books.show', array('book' => $book->id)) }}">
                    {{ substr($book->name, 0, 40) }}...
                </a>

                @else
                <a class="tbreak-all" href="{{ route('books.show', array('book' => $book->id)) }}">{{ $book->name }}</a>
                @endif
            </div>
        </div>
        <div class="rating-and-author">
            <small>{{ Str::limit($book->author->name, 40, '...') }}</small>
            <div>
                <a class="text-grey" href="{{ route('books.show', array('book' => $book->id)) }}"><small></small></a>
            </div>

            @if ($book->ratings)
            <div class="d-flex">
                <div class="rating">
                    <div>
                        @for ($i = 0; $i < floor($book->ratings()->avg('rating')); $i++)
                            <i class="fa fa-star rating-star"></i>
                            @endfor

                            @if (substr($book->ratings()->avg('rating'), -1, 1) != 0)
                            <i class="fa fa-star-half rating-star" aria-hidden="true"></i>
                            @endif

                            <small class="text-grey">{{ substr($book->ratings()->avg('rating'), 0, 3) }}</small>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="mt-auto">
            @auth
            <div class="add-to-wishlist">
                @if (App\Models\Wishlist::where('book_id', $book->id)->where('user_id', Illuminate\Support\Facades\Auth::id())->first())
                <i class="add-to-my-wishlist fas fa-heart" data-id="{{ $book->id }}"></i>
                @else

                <i class="add-to-my-wishlist far fa-heart" data-id="{{ $book->id }}"></i>
                @endif
            </div>
            @endauth
            <div class="book-price">
                @if(isset($book->discount) && $book->discount != 0)
                <div>
                    <small class="discount-line-through text-success">Rp{{ number_format($book->price, 0, 0, '.') }}</small>
                    <span>Rp{{ number_format(($book->price - $book->discount), 0, 0, '.') }}</span>
                </div>

                @else
                <div class="text-left">
                    <span class="ml-2">Rp{{ number_format(($book->price - $book->discount), 0, 0, '.') }}</span>
                </div>
                @endif

            </div>
            <a class="book-show-link" href="{{ route('books.show', array('book' => $book->id)) }}"></a>
        </div>
    </div>
    @endforeach

</div>
