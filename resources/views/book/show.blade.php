@extends('layouts/app')
@section('content')

<div class="home-and-anymore-show">
    <a class="tsmall" href="#">Home</a><i class="fas fa-caret-right tsmall"></i>
    <a class="tsmall" href="#">Categories</a><i class="fas fa-caret-right tsmall"></i>
    <a class="tsmall" href="#">Komik</a><i class="fas fa-caret-right tsmall"></i>
    <span class="tsmall">{{ $book->name }}</span>
</div>

<div class="book-show d-sm-flex">
    <div>
        <div class="book-show-cover">
            <img class="zoom-modal-image" src="{{ url('storage/books/' . $book->image) }}">
            <div class="book-show-images">
                <div>
                    <img src="{{ url('storage/books/' . $book->image) }}" class="w-100">
                </div>
                <div>
                    <img src="{{ url('storage/books/' . $book->image) }}" class="w-100">
                </div>
                <div>
                    <img src="{{ url('storage/books/' . $book->image) }}" class="w-100">
                </div>
                <div>
                    <img src="{{ url('storage/books/' . $book->image) }}" class="w-100">
                </div>
            </div>
        </div>
    </div>
    <div id="book-show" data-id="{{ $book->id }}" class="book-show-sinopsis">
        <div class="white-content">
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
        <div class="detail-and-buy">
            <div class="book-show-detail">
                <div>
                    <h5 class="title-border-red">Detail</h5>
                    <div class="d-flex">
                        <div class="w-50">
                            <div>ISBN</div>
                            <div>Subtitle</div>
                            <div>Jumlah Halaman</div>
                            <div>Penerbit</div>
                            <div>Tanggal Rilis</div>
                            <div>Berat</div>
                            <div>Lebar</div>
                            <div>Panjang</div>
                        </div>
                        <div class="w-50 text-grey">
                            <div>{{ $book->isbn }}</div>
                            <div>{{ $book->subtitle }}</div>
                            <div>{{ $book->pages }}</div>
                            <div>{{ $book->publisher }}</div>
                            <div>{{ $book->release_date }}</div>
                            <div>{{ $book->weight }} kg</div>
                            <div>{{ $book->width }} cm</div>
                            <div>{{ $book->height }} cm</div>
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
                            <div class="tred-bold">{{ rupiah_format($book->price) }}</div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <div class="d-flex justify-content-between">
                            <div>
                                <button class="btn-none btn-wishlist">
                                    <i class="far fa-heart"></i>
                                    <i class="fa fa-heart"></i>
                                    <span>Wishlist</span>
                                </button>
                            </div>
                            <div>
                                <button class="btn-none"><i class="fas fa-comment-dots"></i> Tanya Produk</button>
                            </div>
                            <div>
                                <button class="btn-none"><i class="add-shop fa fa-plus" aria-hidden="true"></i> Keranjang</button>
                            </div>
                        </div>
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
