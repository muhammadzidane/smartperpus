@extends('layouts/app')
@section('content')

<div class="home-and-anymore-show">
    <a class="tsmall" href="#">Home</a><i class="fas fa-caret-right tsmall"></i>
    <a class="tsmall" href="#">Categories</a><i class="fas fa-caret-right tsmall"></i>
    <a class="tsmall" href="#">Komik</a><i class="fas fa-caret-right tsmall"></i>
    <span class="tsmall">{{ $book->name }}</span>
</div>

<div class="book-show">
    <div>
        <div class="book-show-cover">
            <img src="{{ url('img/book/' . $book->image) }}">
            <div class="book-show-images">
                <div>
                    <img src="{{ url('img/book/' . $book->image) }}" class="w-100">
                </div>
                <div>
                    <img src="{{ url('img/book/' . $book->image) }}" class="w-100">
                </div>
                <div>
                    <img src="{{ url('img/book/' . $book->image) }}" class="w-100">
                </div>
                <div>
                    <img src="{{ url('img/book/' . $book->image) }}" class="w-100">
                </div>
            </div>
        </div>
    </div>
    <div class="book-show-sinopsis">
        <div class="white-content">
            <h4 class="tbold">{{ $book->name }}</h4>
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
            <h5><a href="{{ route('authors.show', array('author' => $book->authors[0]->id )) }}">{{ $book->authors[0]->name }}</a></h5>
            <div class="mt-3">
                @if (strlen($book->synopsis->text) > 500)
                    <p>{{ substr($book->synopsis->text, 0, 500) }} <a href="">Lihat Semua....</a></p>

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
                        <div class="w-50">
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
                    <h4 class="mb-3">Stok Buku Cetak: <span class="tred-bold">{{ $book->printedStock->amount }}</span></h4>
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
                            <div>E-Book</div>
                        </div>
                        <div>
                            <div class="tred-bold">{{ rupiah_format($book->price * 2) }}</div>

                            <div class="tred-bold">{{ rupiah_format($book->price) }}</div>
                        </div>
                    </div>

                    <div class="buy">
                        <div class="d-flex justify-content-between">
                            <button class="btn-wishlist">
                                <i class="far fa-heart"></i>
                                <i class="fa fa-heart"></i>
                                <span>Wishlist</span>
                            </button>
                            <div><i class="add-shop fas fa-shopping-cart"></i>Keranjang</div>
                            <div>
                                <span class="share-sosmed">
                                    <span class="mr-1"><i class="fa fa-share-alt" aria-hidden="true"></i></span>
                                    <span><a href="#"><i class="fab fa-facebook-f"></i></a></span>
                                    <span><a href="#"><i class="fab fa-twitter" aria-hidden="true"></i></a></span>
                                    <span><a href="#"><i class="fab fa-instagram"></i></a></span>
                                </span>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('books.buy') }}" type="button" class="btn btn-red w-100 mb-2">Beli Buku Cetak</a>
                            <button type="button" class="btn btn-yellow w-100">Beli E-Book</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="rating" class="white-content p-4 mt-c">
    <h4 class="tbold title-border-red my-3">Ulasan Produk</h4>
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

    @for($i = 0; $i < 3; $i++)
        <div class="customer-reviews">
            <div>
                <img class="customer-reviews-profile" src="{{ url('img/book/' . $book->image) }}">
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
    </div>

    <div>
        <div class="white-content border-yellow p-4 mb-5">
            <!-- Pertanyaan Customer -->
            <div class="borbot-gray">
                <div class="d-flex">
                    <img class="customer-reviews-profile" src="{{ url('img/book/' . $book->image) }}">
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
                    <img class="customer-reviews-profile" src="{{ url('img/book/' . $book->image) }}">
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
                    <img class="customer-reviews-profile" src="{{ url('img/book/' . $book->image) }}">
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
                    <img class="customer-reviews-profile" src="{{ url('img/book/' . $book->image) }}">
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
        'books' => \App\Models\Category::where('name', 'komik')->first()->books->take(6)
    )
)

@include('layouts.book-deals',
    array(
        'title' => 'Buku Diskon',
        'books' => \App\Models\Category::where('name', 'komik')->first()->books->take(6)
    )
)
@endsection
