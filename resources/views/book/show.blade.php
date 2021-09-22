@extends('layouts/app')

@section('content')

<div class="home-and-anymore-show">
    <a class="tsmall mr-1" href="{{ route('home') }}">Home</a><i class="fas fa-caret-right tsmall"></i>
    <a class="tsmall mr-1" href="{{ route('books.index', array('category' => array($book->category->id))) }}">{{ $book->category->name }}</a><i class="fas fa-caret-right tsmall"></i>
    <span class="tsmall mr-1 tred-bold">{{ Str::limit($book->name, 50, '...') }}</span>
</div>

<div class="row mt-4">
    <div class="col-md-3">
        <div>
            <div class="primary-book">
                <img id="primary-book-image" class="zoom-modal-image" src="{{ asset('storage/books/' . $book->image) }}">
            </div>
            <div class="book-show-images">
                <div class="book-show-click book-show-image-active" data-id="{{ $book->id }}">
                    <img src="{{ asset('storage/books/' . $book->image) }}" class="book-show-image">
                </div>

                @foreach ($book->book_images as $image)
                    <div class="book-show-click" data-id="{{ $image->id }}">
                        <img src="{{ asset('storage/books/book_images/' . $image->image) }}" class="book-show-image">
                        @can('viewAny', 'App\\Models\User')
                        <button class="book-image-delete btn-none"><i class="fa fa-times"></i></button>
                        @endcan
                    </div>
                @endforeach
            </div>

            @can('viewAny', 'App\\Models\User')
                <div class="mb-5 mt-4">
                    <div class="mb-2 mt-3">
                        <div class="text-grey mb-2">Klik pada gambar untuk mengedit</div>
                        <form id="book-image-edit-form" method="post" enctype="multipart/form-data">
                            <input id="book-image-edit-file" type="file" name="image" accept="image/png, image/jpeg, image/jpg">
                            <button id="book-image-edit" type="submit" class="btn btn-success w-100 mt-2">Edit</button>
                            @method('PATCH')
                            @csrf
                        </form>
                    </div>
                    @if ($book->book_images->count() < 3)
                        <div class="mt-3">
                            <form id="add-book-image-form" action="{{ route('add.book.images', array('book' => $book->id)) }}" enctype="multipart/form-data" method="POST">
                                <div class="form-group">
                                    <input type="file" id="image" name="image" accept="image/png, image/jpeg, image/jpg">
                                </div>
                                <button type="submit" class="btn btn-danger w-100">Tambah Gambar</button>
                                @csrf
                            </form>
                        </div>
                    @endif
                </div>
            @endcan
        </div>
    </div>
    <div id="book-show" data-id="{{ $book->id }}" class="col-md-9">
        <div class="white-content mt-5 mt-md-0">
            @can('view', $book)
            <div class="d-flex justify-content-end">
                <button id="book-add-stock" data-target="#book-add-stock-modal" data-toggle="modal" class="mr-2 btn-none tred-bold">Tambah Stok</button>
                <div class="mr-2">
                    <a id="book-edit" href="{{ route('books.edit', array('book' => $book->id)) }}" type="button" class="btn btn-success">Edit</a>
                </div>
                <div>
                    <form id="book-delete-form" action="{{ route('books.destroy', array('book' => $book->id)) }}" method="post">
                        <button id="book-delete-modal" type="submit" class="btn btn-danger">Hapus</button>
                        @method('DELETE')
                        @csrf
                    </form>
                </div>
            </div>
            @endcan

            <h5>{{ $book->name }}</h5>
            <h5 class="mt-2">
                <a class="text-grey" href="{{ route('authors.show', array('author' => $book->author->id )) }}">{{ $book->author->name }}</a>
            </h5>
            <div class="my-3 d-flex">
                <a href="#rating" class="text-decoration-none">
                    @if ($book->ratings->isNotEmpty())
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $book->ratings()->avg('rating'))
                                <i class="book-show-rating-icon star-icon-color fa fa-star"></i>

                            @elseif ($i >= $book->ratings()->avg('rating') + 1)
                                <i class="book-show-rating-icon star-icon-color far fa-star"></i>
                            @else
                                <i class="book-show-rating-icon star-icon-color fas fa-star-half-alt"></i>
                            @endif
                        @endfor

                    @else
                        <div class="text-grey">Belum ada ulasan</div>
                    @endif
                    <span>{{ Str::substr($book->ratings()->avg('rating'), 0, 3)  }}</span>
                </a>

                @if ($book->book_user()->where('payment_status', 'arrived')->count() != 0)
                    <div class="mx-3">
                        <i class="fas fa-dot-circle text-grey"></i>
                    </div>
                    <div>{{ $book->book_user()->where('payment_status', 'arrived')->count() }} Terjual</div>
                @endif
            </div>

            @if ($book->discount)
                <small class="discount-line-through text-danger">{{ rupiah_format($book->price) }}</small>
                <h4 class="tred-bold">{{ rupiah_format($book->price - $book->discount) }}</h4>
            @else
                <h4 class="tred-bold">{{ rupiah_format($book->price - $book->discount ) }}</h4>
            @endif
            <div id="synopsis" class="mt-3">
                @if (strlen($book->synopsis->text) > 500)
                    <p>
                        <span>{{ substr($book->synopsis->text, 0, 500) }}</span>
                        <span>
                            <button id="book-synopsis-toggle-button" class="btn-none p-0 ml-1 text-primary">Lihat Semua....</button>
                            <span id="book-synopsis-show" style="display: none;">{{ substr($book->synopsis->text, 500) }}</span>
                        </span>
                    </p>

                @else
                    <p class="synopsis">{{ $book->synopsis->text }}</p>
                @endif
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <h5 class="title-border-red">Detail</h5>
                <div>
                    <div class="d-flex justify-content-between">
                        <div>ISBN</div>
                        <div class="text-right">{{ $book->isbn }}</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Subtitle</div>
                        <div class="text-right">{{ $book->subtitle }}</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Halaman</div>
                        <div class="text-right">{{ $book->pages }}</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Penerbit</div>
                        <div class="text-right">{{ $book->publisher }}</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Tanggal Rilis</div>
                        <div class="text-right">{{ $book->release_date }}</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Berat</div>
                        <div class="text-right">{{ $book->weight }}</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Panjang</div>
                        <div class="text-right">{{ $book->width }}</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Lebar</div>
                        <div class="text-right">{{ $book->height }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-4 mt-md-0">
                <div class="book-payment">
                    <div>
                        <div>
                            <h5 class="mb-3">Stok Buku Cetak : <span id="book-stock" class="tred-bold">{{ $book->printed_book_stock }}</span></h5>
                            <div class="info-book">
                                <div>
                                    <i class="info-book-tooltips fas fa-info-circle"></i>
                                </div>
                                <div class="info-book-text">
                                    <div>Pembelian E-Book hanya bisa satu per akun</div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div>
                                <div>Buku Cetak</div>
                            </div>
                            <div>
                                @if ($book->discount)

                                <div class="tred-bold">
                                    <span>
                                        <small class="discount-line-through d-inline">
                                            {{ rupiah_format($book->price) }}
                                        </small>
                                        {{ rupiah_format($book->price - $book->discount) }}
                                    </span>
                                </div>

                                @else

                                <div class="tred-bold">{{ rupiah_format($book->price) }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div>
                        @auth
                            @cannot('authAdminOnly', 'App/Models/User')
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button id="book-show-wishlist" class="btn-none">
                                        @if (App\Models\Wishlist::where('book_id', $book->id)->where('user_id', auth()->user()->id)->first())
                                        <i class="fas fa-heart text-danger"></i>
                                        @else
                                        <i class="far fa-heart text-danger"></i>

                                        @endif
                                        <span>Wishlist</span>
                                    </button>
                                </div>
                                <div>
                                    @if (auth()->user()->carts->where('book_id', $book->id)->isNotEmpty())
                                    <button id="cart-delete" class="btn-none tred" data-id="{{ auth()->user()->carts->where('book_id', $book->id)->first()->id }}">Hapus dari keranjang</button>
                                    @else
                                    <button id="cart-store" class="btn-none"><i class="add-shop fa fa-plus"></i> Keranjang</button>
                                    @endif
                                </div>
                            </div>
                            @endcannot
                        @endauth

                        <div>
                            @auth
                            <div>
                                <form action="{{ route('carts.bought.directly', array('book' => $book->id)) }}" class="m-0" method="POST">
                                    <button type="submit" class="btn btn-red w-100">Beli Langsung</button>
                                    @csrf
                                </form>
                            </div>
                            @endauth

                            @guest
                            <a href="{{ route('login') }}" type="button" class="btn btn-red w-">Beli Langsung</a>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="rating" class="white-content px-4 py-3 mt-4">
    <h4 class="tbold title-border-red my-3 pb-2">Penilaian Produk</h4>

    @if ($ratings->total() != 0 || (request()->filter == 5 || request()->filter == 4 || request()->filter == 3 || request()->filter == 2 || request()->filter == 1))
        <p class="tred-bold">{{ $book->name }}</p>
        <div class="d-flex">
            <div>
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $book->ratings()->avg('rating'))
                        <div><i class="rating-product-reviews star-icon-color fa fa-star mt-2"></i></div>

                    @elseif ($i >= $book->ratings()->avg('rating') + 1)
                        <div><i class="rating-product-reviews star-icon-color far fa-star mt-2"></i></div>
                    @else
                        <div><i class="rating-product-reviews star-icon-color fas fa-star-half-alt mt-2"></i></div>
                    @endif
                @endfor
            </div>
            <div class="d-flex align-items-center ml-4">
                <div>
                    <h1 class="mt-auto">
                        <span class="tred-bold">{{ Str::substr($book->ratings()->avg('rating'), 0, 3) }}</span>
                        <span>/ 5</span>
                    </h1>
                    <div>({{ $book->ratings()->count() }}) Total Ulasan</div>
                </div>
            </div>
        </div>
        <h5 class="tbold mt-4 mb-2">FILTER</h4>
        <div class="mb-4">
            <div class="filter-review">
                <input type="radio" id="filter-review-all" name="filter_rating" value="all" {{ request()->filter == 'all' ? 'checked' : '' }}>
                <label class="d-inline-flex" for="filter-review-all">
                    <a class="flex-grow-1" href="{{ url()->current() }}?filter=all#rating">
                        <span>Semua</span>
                    </a>
                </label>

                @for ($i = 5; $i >= 1; $i--)
                    <input type="radio" id="filter-{{ $i }}" name="filter_rating" value="{{ $i }}" {{ request()->filter == $i ? 'checked' : '' }}>
                    <label class="d-inline-flex" for="filter-{{ $i }}">
                        <a class="flex-grow-1" href="{{ url()->current() }}?filter={{ $i }}#rating">
                            <i class="star-icon-color fa fa-star"></i>
                            <span class="tbold">{{ $i }}</span>
                            <span class="tbold">({{ $book->ratings()->where('rating', $i)->count() }})</span>
                        </a>
                    </label>
                @endfor
            </div>
        </div>

        @foreach($ratings as $rating)
            <div class="customer-reviews" data-id="{{ $rating->rating }}">
                <div class="customer-reviews-profile mr-3">
                    <img src="{{ asset($rating->user->profile_image ? 'storage/users/profiles/' . $rating->user->profile_image : 'img/avatar-icon.png' ) }}">
                </div>
                <div>
                    <div>
                        <span>{{ $rating->user->first_name . ' ' . $rating->user->last_name }}</span>
                    </div>
                    <div class="mt-1">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($rating->rating < $i)
                                <i class="star-icon-color far fa-star"></i>

                            @else
                                <i class="star-icon-color fa fa-star"></i>
                            @endif
                        @endfor

                        <span class="text-grey ml-3">{{ \Carbon\Carbon::make($rating->created_at)->diffForHumans() }}</span>
                    </div>
                    <div class="mt-3">
                        <p>{{ $rating->review }}</p>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="d-flex justify-content-end">{{ $ratings->links() }}</div>

        @else
            <div class="w-50 mx-auto pb-5 pt-2">
                @include('empty-image', array('title' => 'Belum ada penilaian'))
            </div>
    @endif
</div>

@include('layouts.book-deals',
    array(
        'title' => 'Rekomendasi Komik / Manga',
        'books' => \App\Models\Book::where('category_id', 1)->get()->take(6),
    )
)

@include('layouts.book-deals',
    array(
        'title' => 'Buku Diskon',
        'books' => \App\Models\Book::where('category_id', 1)->get()->take(6),
    )
)

<div class="click-to-the-top">
    <button class="btn-to-the-top d-flex ml-auto"><i class="to-the-top fa fa-caret-up"></i></button>
</div>

@endsection

<script src="{{ asset('js/book-show.js') }}"></script>
