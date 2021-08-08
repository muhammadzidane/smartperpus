@extends('layouts/app')
@section('content')

<div class="home-and-anymore-show">
    <a class="tsmall mr-1" href="{{ route('home') }}">Home</a><i class="fas fa-caret-right tsmall"></i>
    <a class="tsmall mr-1" href="{{ route('books.index', array('category' => array($book->category->id))) }}">{{ $book->category->name }}</a><i class="fas fa-caret-right tsmall"></i>
    <span class="tsmall mr-1 tred-bold">{{ $book->name }}</span>
</div>

<div class="d-md-flex mt-4">
    <div class="col-md-3">
        <div>
            <div class="primary-book">
                <img id="primary-book-image" class="zoom-modal-image" src="{{ asset('storage/books/' . $book->image) }}">
            </div>
            <div class="book-show-images">
                <div class="book-show-click book-show-image-active">
                    <img src="{{ asset('storage/books/' . $book->image) }}" class="book-show-image">
                </div>
                @foreach ($book->book_images as $image)
                <div class="book-show-click">
                    <img src="{{ asset('storage/books/book_images/' . $image->image) }}" class="book-show-image">
                </div>
                @endforeach
            </div>

            @can('viewAny', 'App\\Models\User')
            <div class="row mb-5 mt-4">
                @if ($book->book_images->count() < 3) <div class="col-6 p-0 p-1">
                    <button id="book-image-store" type="submit" class="btn btn-danger w-100">Tambah Foto</button>
            </div>
            @endif
            <div class="col-6 p-0 p-1">
                <button id="book-image-edit" type="button" class="btn btn-success w-100">Edit</button>
            </div>
        </div>
        @endcan
    </div>
</div>
<div id="book-show" data-id="{{ $book->id }}" class="col-md-9 pl-md-5">
    <div class="white-content m-0">
        @can('view', $book)
        <div class="d-flex justify-content-end">
            <button id="book-add-stock" data-target="#book-add-stock-modal" data-toggle="modal" class="mr-2 btn-none tred-bold">Tambah Stok</button>
            <div class="mr-2">
                <a href="{{ route('books.edit', array('book' => $book->id)) }}" type="button" class="btn btn-success">Edit</a>
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

        @if ($book->discount)
        <small class="discount-line-through text-danger">{{ rupiah_format($book->price) }}</small>
        <span class="tred-bold">{{ rupiah_format($book->price - $book->discount) }}</span>
        @else
        <div class="tbold">{{ rupiah_format($book->price - $book->discount ) }}</div>
        @endif

        <div class="tbold">{{ $book->category->name }}</div>
        <div class="my-1 d-flex">
            <div>
                <div>
                    <a href="#rating" class="text-decoration-none">
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <span class="ml-1">{{ $book->rating }}</span>
                    </a>
                </div>
            </div>
            <div class="d-flex ml-2">
                <div class="ml-1">
                    <div>Terjual (103) <span class="tbold">|</span></div>
                </div>
                <div class="ml-1">
                    <div>Ulasan (103) <span class="tbold">|</span></div>
                </div>
                <div class="ml-1">
                    <div>Diskusi (20)</div>
                </div>
            </div>
        </div>
        <h4 class="hd-14">
            <a class="text-grey" href="{{ route('authors.show', array('author' => $book->author->id )) }}">{{ $book->author->name }}</a>
        </h4>
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
    <div class="detail-and-buy mt-5">
        <div class="book-show-detail">
            <div>
                <h5 class="title-border-red">Detail</h5>
                <div class="pr-4">
                    <div class="d-flex justify-content-between">
                        <div>ISBN</div>
                        <div>{{ $book->isbn }}</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Subtitle</div>
                        <div>{{ $book->subtitle }}</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Halaman</div>
                        <div>{{ $book->pages }}</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Penerbit</div>
                        <div>{{ $book->publisher }}</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Tanggal Rilis</div>
                        <div>{{ $book->release_date }}</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Berat</div>
                        <div>{{ $book->weight }}</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Panjang</div>
                        <div>{{ $book->width }}</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Lebar</div>
                        <div>{{ $book->height }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="book-payment">
            <div class="d-flex justify-content-between">
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
            <div>
                <div class="d-flex justify-content-between">
                    <div>
                        <div>Buku Cetak</div>
                    </div>
                    <div>
                        @if ($book->discount)

                        <div class="tred-bold">
                            <span>
                                <small class="discount-line-through d-inline">
                                    {{ rupiah_format($book->price - $book->discount) }}
                                </small>
                                {{ rupiah_format($book->price) }}
                            </span>
                        </div>

                        @else

                        <div class="tred-bold">{{ rupiah_format($book->price) }}</div>
                        @endif
                    </div>
                </div>

                <div class="mt-5">
                    @auth
                    <div class="d-flex justify-content-between">
                        <div>
                            <button id="book-show-wishlist" class="btn-none">
                                @if (App\Models\Wishlist::where('book_id', $book->id)->where('user_id', Illuminate\Support\Facades\Auth::id())->first())
                                <i class="fas fa-heart text-danger"></i>
                                @else
                                <i class="far fa-heart text-danger"></i>

                                @endif
                                <span>Wishlist</span>
                            </button>
                        </div>
                        <div>
                            <button class="btn-none"><i class="add-shop fa fa-plus" aria-hidden="true"></i> Keranjang</button>
                        </div>
                    </div>
                    @endauth
                    <div>
                        @if ($book->ebook === 0)
                        <button type="button" class="btn btn-grey w-100" disabled>E-Book tidak tersedia</button>
                        @else
                        <a href="{{ route('books.buy', array('book' => $book->name)) }}" type="button" class="btn btn-yellow w-100 mt-2">Beli E-Book</a>
                        @endif

                        @auth
                        <a href="{{ route('books.buy', array('book' => $book->name)) }}" type="button" class="btn btn-red w-100 mt-2">Beli Buku Cetak</a>
                        @endauth

                        @guest
                        <a href="{{ route('login') }}" type="button" class="btn btn-red w-100 mt-2">Beli Buku Cetak</a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div id="rating" class="white-content p-4 mt-c">
    <h5 class="tbold title-border-red my-3">Ulasan Produk</h5>
    <p class="tred-bold">{{ $book->name }}.</p>
    <div class="rating-product-reviews">
        <div>
            <div><i class="fa fa-star" aria-hidden="true"></i></div>
            <div><i class="fa fa-star" aria-hidden="true"></i></div>
            <div><i class="fa fa-star" aria-hidden="true"></i></div>
            <div><i class="fa fa-star" aria-hidden="true"></i></div>
            <div><i class="fa fa-star" aria-hidden="true"></i></div>
        </div>
        <div>
            <div class="rating-product-star">
                <h5 class="mt-auto">{{ $book->rating }}/ 5</h5>
                <div>(344)Total Ulasan</div>
            </div>
        </div>
    </div>
    <div class="filter-star">
        <span class="tbold mr-4">Filter</span>
        <button class="btn">Lihat Semua</button>
        <button class="btn"><i class="fa fa-star" aria-hidden="true"></i> 5 (32)</button>
        <button class="btn"><i class="fa fa-star" aria-hidden="true"></i> 4 (40)</button>
        <button class="btn"><i class="fa fa-star" aria-hidden="true"></i> 3 (0)</button>
        <button class="btn"><i class="fa fa-star" aria-hidden="true"></i> 2 (2)</button>
        <button class="btn"><i class="fa fa-star" aria-hidden="true"></i> 1 (0)</button>
    </div>

    @for($i = 0; $i < 3; $i++) <div class="customer-reviews">
        <div>
            <img class="customer-reviews-profile" src="{{ url('storage/books/' . $book->image) }}">
        </div>
        <div>
            <div>
                <span>Muhammad Zidane</span>
                <span class="purchase-date">1 hari yang lalu</span>
            </div>
            <div>
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true"></i>
            </div>
            <div class="mt-2">
                <p>
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ab assumenda commodi, maiores animi quaerat est fugiat quo dolorem. Quas, nulla repellendus eaque exercitationem laudantium perspiciatis temporibus quae iure vero ad? lorem
                </p>
            </div>
        </div>
</div>
@endfor
</div>

<div class="mt-c">
    <div class="product-discussion">
        <div class="d-flex justify-content-between">
            <h5 class="tbold title-border-red">Diskusi Terkait Produk (12)</h5>
            <div class="d-flex">
                <div class="mr-3 mt-2">Login untuk berdiskusi</div>
                <div>
                    <a class="btn btn-outline-primary" href="#">Login</a>
                </div>
            </div>
        </div>
    </div>

    <div>
        <p class="tred-bold">{{ $book->name }}.</p>
        <div>{{ $book->category->name }}</div>
    </div>

    <div>
        <div class="white-content border-yellow p-4 mb-5">
            <!-- Pertanyaan Customer -->
            <div class="borbot-gray">
                <div class="d-flex">
                    <img class="customer-reviews-profile" src="{{ url('storage/books/' . $book->image) }}">
                    <div class="d-flex justify-content-between w-100">
                        <div>Muhammad Zidane</div>
                        <div class="purchase-date">2 Minggu yang lalu</div>
                    </div>
                </div>
                <div>
                    <div class="mt-2">
                        <p>
                            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ab assumenda commodi, maiores animi quaerat est fugiat quo dolorem. Quas, nulla repellendus eaque exercitationem laudantium perspiciatis temporibus quae iure vero ad? lorem
                        </p>
                    </div>
                </div>
            </div>

            <!-- Jawaban Admin -->
            <div class="px-3">
                <div class="d-flex">
                    <img class="customer-reviews-profile" src="{{ url('storage/books/' . $book->image) }}">
                    <div class="d-flex justify-content-between w-100">
                        <div>Admin</div>
                        <div class="purchase-date">6 Hari yang lalu</div>
                    </div>
                </div>
                <div>
                    <div class="mt-2">
                        <p>
                            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ab assumenda commodi, maiores animi quaerat est fugiat quo dolorem. Quas, nulla repellendus eaque exercitationem laudantium perspiciatis temporibus quae iure vero ad? lorem
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="white-content border-yellow p-4 mb-5">
            <!-- Pertanyaan Customer -->
            <div class="borbot-gray">
                <div class="d-flex">
                    <img class="customer-reviews-profile" src="{{ url('storage/books/' . $book->image) }}">
                    <div class="d-flex justify-content-between w-100">
                        <div>Muhammad Zidane</div>
                        <div class="purchase-date">2 Minggu yang lalu</div>
                    </div>
                </div>
                <div>
                    <div class="mt-2">
                        <p>
                            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ab assumenda commodi, maiores animi quaerat est fugiat quo dolorem. Quas, nulla repellendus eaque exercitationem laudantium perspiciatis temporibus quae iure vero ad? lorem
                        </p>
                    </div>
                </div>
            </div>

            <!-- Jawaban Admin -->
            <div class="px-3">
                <div class="d-flex">
                    <img class="customer-reviews-profile" src="{{ url('storage/books/' . $book->image) }}">
                    <div class="d-flex justify-content-between w-100">
                        <div>Admin</div>
                        <div class="purchase-date">6 Hari yang lalu</div>
                    </div>
                </div>
                <div>
                    <div class="mt-2">
                        <p>
                            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ab assumenda commodi, maiores animi quaerat est fugiat quo dolorem. Quas, nulla repellendus eaque exercitationem laudantium perspiciatis temporibus quae iure vero ad? lorem
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
