@extends('layouts/app')
@section('content')

<div class="home-and-anymore-show">
    <a href="#">Home</a><i class="fas fa-caret-right"></i>
    <a href="#">Categories</a><i class="fas fa-caret-right"></i>
    <a href="#">Komik</a><i class="fas fa-caret-right"></i>
    <a href="#">{{ $book->name }}</a>
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
    <div>
        <div class="book-show-sinopsis white-content">
            <h4 class="tbold">{{ $book->name }}</h4>
            <h5><a href="{{ route('authors.show', array('author' => $book->authors[0]->id )) }}">{{ $book->authors[0]->name }}</a></h5>
            <p>{{ $book->synopsis->text }}</p>
        </div>
        <div class="d-flex h-50">
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
                    <h4>Stok Buku Cetak: <span class="tred-bold">{{ $book->printedStock->amount }}</span></h4>
                    <i class="info-book fas fa-info-circle"></i>
                </div>
                <div>
                    <div class="d-flex justify-content-between">
                        <div>
                            <div>Buku Cetak</div>
                            <div>E-Book</div>
                        </div>
                        <div>
                            <div class="tred-bold">Rp{{ $book->price * 2 }}</div>

                            <div class="tred-bold">Rp{{ $book->price }}</div>
                        </div>
                    </div>

                    <div class="buy">
                        <div class="d-flex justify-content-between">
                            <button class="btn-wishlist">
                                <i class="far fa-heart"></i>
                                <i class="fa fa-heart"></i>
                                <span>Wishlist</span>
                            </button>
                            <div><i class="add-shop fas fa-shopping-cart"></i><i class="add-shop fa fa-plus mr-1" aria-hidden="true"></i>Keranjang</div>
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
                            <button type="button" class="btn btn-red w-100 mb-2">Beli Buku Cetak</button>
                            <button type="button" class="btn btn-yellow w-100">Beli E-Book</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="white-content p-4 mt-5">
    <h4 class="tbold title-border-red">Ulasan Produk</h4>
    <div class="rating-product-reviews">
        <div>
            <div><i class="fa fa-star" aria-hidden="true"></i></div>
            <div><i class="fa fa-star" aria-hidden="true"></i></div>
            <div><i class="fa fa-star" aria-hidden="true"></i></div>
            <div><i class="fa fa-star" aria-hidden="true"></i></div>
            <div><i class="fa fa-star" aria-hidden="true"></i></div>
        </div>
        <div>
            <h5>5.0/5</h5>
        </div>
    </div>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloremque explicabo perspiciatis illo placeat totam, ipsam veniam vero porro nobis consequuntur enim tempore sapiente provident rem amet impedit sunt atque vel!</p>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloremque explicabo perspiciatis illo placeat totam, ipsam veniam vero porro nobis consequuntur enim tempore sapiente provident rem amet impedit sunt atque vel!</p>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloremque explicabo perspiciatis illo placeat totam, ipsam veniam vero porro nobis consequuntur enim tempore sapiente provident rem amet impedit sunt atque vel!</p>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloremque explicabo perspiciatis illo placeat totam, ipsam veniam vero porro nobis consequuntur enim tempore sapiente provident rem amet impedit sunt atque vel!</p>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloremque explicabo perspiciatis illo placeat totam, ipsam veniam vero porro nobis consequuntur enim tempore sapiente provident rem amet impedit sunt atque vel!</p>
</div>

@endsection
