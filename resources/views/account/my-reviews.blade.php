@extends('layouts.app')
@section('content')
<div class="row mt-5">
    <div class="col-lg-9">
        <div class="white-content-0">
            <div class="white-content-header-2 flex-column">
                <h4 class="hd-14 m-0">Ulasan Saya</h4>
                <div class="mt-2">
                    <div class="wishlist-search">
                        <span><i class="fa fa-search wishlist-search-icon" aria-hidden="true"></i></span>
                        <input class="text-righteous hd-12" type="search" placeholder="Cari Ulasan">
                    </div>
                </div>
            </div>
        </div>
        <div class="white-content-0">
            <div class="p-15 borbot-gray-bold">
                <div class="d-flex justify-content-between">
                    <div>
                        <span>Tanggal Pembelian - </span>
                        <span class="text-grey">19 Juli 2021, 15:30 WIB</span>
                    </div>
                    <div class="text-grey">ABCD2K209128</div>
                </div>
            </div>
            <div class="p-4 borbot-gray-bold">
                <div class="ml-1 borbot-gray-0">
                    <div class="d-flex">
                        <div class="img-shcart">
                            <img class="w-100" src="{{ asset('img/book/jujutsu-kaisen-01.jpg') }}">
                        </div>
                        <div class="d-flex flex-column w-100">
                            <div class="pl-3 flex-grow-1 d-flex flex-column justify-content-between">
                                <div class="text-righteous">Jujutsu Kaisen 01</div>
                                <div>
                                    <div class="d-flex">
                                        <div><i class="fa fa-star mr-1" aria-hidden="true"></i></div>
                                        <div><i class="fa fa-star mr-1" aria-hidden="true"></i></div>
                                        <div><i class="fa fa-star mr-1" aria-hidden="true"></i></div>
                                        <div><i class="fa fa-star mr-1" aria-hidden="true"></i></div>
                                        <div><i class="fa fa-star mr-1" aria-hidden="true"></i></div>
                                        <div>5.0</div>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-right text-righteous">
                                        <button type="button" class="btn-none p-0 tred mr-1 hd-14"
                                          data-toggle="modal" data-target="#modal-review">Lihat Ulasan</button>
                                        <a href="#" class="btn btn-sm-0 btn-red hd-14">Beli Lagi</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ml-1 borbot-gray-0 mt-5">
                    <div class="d-flex">
                        <div class="img-shcart">
                            <img class="w-100" src="{{ asset('img/book/jujutsu-kaisen-01.jpg') }}">
                        </div>
                        <div class="d-flex flex-column w-100">
                            <div class="pl-3 flex-grow-1 d-flex flex-column justify-content-between">
                                <div class="text-righteous">Jujutsu Kaisen 01</div>
                                <div>
                                    <span>Jumlah Pembelian : </span>
                                    <span class="text-grey tbold">1 x Rp.30.000 - Buku Cetak</span>
                                </div>
                                <div>
                                    <div class="text-right text-righteous">
                                        <button type="button" class="btn-none p-0 tred mr-1 hd-14"
                                          data-toggle="modal" data-target="#modal-review">Lihat Ulasan</button>
                                        <a href="#" class="btn btn-sm-0 btn-red hd-14">Beli Lagi</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="white-content-0 mt-5">
            <div class="p-15 borbot-gray-0">
                <div class="d-flex justify-content-between">
                    <div>
                        <span>Tanggal Pembelian - </span>
                        <span class="text-grey">19 Juli 2021, 15:30 WIB</span>
                    </div>
                    <div class="text-grey">ABCD2K209128</div>
                </div>
            </div>
            <div class="p-4 borbot-gray-bold">
                <div class="ml-1">
                    <div class="d-flex">
                        <div class="img-shcart">
                            <img class="w-100" src="{{ asset('img/book/jujutsu-kaisen-01.jpg') }}">
                        </div>
                        <div class="d-flex flex-column w-100">
                            <div class="pl-3 flex-grow-1 d-flex flex-column justify-content-between">
                                <div class="text-righteous">Jujutsu Kaisen 01</div>
                                <div>
                                    <span>Jumlah Pembelian : </span>
                                    <span class="text-grey tbold">1 x Rp.30.000 - Buku Cetak</span>
                                </div>
                                <div>
                                    <div class="text-right text-righteous">
                                        <button type="button" class="btn-none p-0 tred mr-1 hd-14"
                                          data-toggle="modal" data-target="#modal-review">Lihat Ulasan</button>
                                        <a href="#" class="btn btn-sm-0 btn-red hd-14">Beli Lagi</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 h-maxc">
        <div class="white-content m-0 h-100 borbot-gray-bold">
            <div class="borbot-gray-0 pb-3">
                <div class="container mt-1">
                    <h4 class="hd-16">Pembelian</h4>
                    <div class="text-grey">
                        <div class="d-flex w-maxc position-relative">
                            <div>Menunggu pembayaran</div>
                            <div class="waiting-for-payment">6</div>
                        </div>
                        <div>Daftar transaksi</div>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <div class="container mt-1">
                    <h4 class="hd-16">Kontak Masuk</h4>
                    <div class="text-grey">
                        <div class="active-acc">Ulasan</div>
                        <div>Diskusi produk</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Transacton Lists -->
<div class="modal fade" id="modal-review" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered p-5" role="document">
        <div class="modal-content modal-content-login">
            <div class="px-3 mb-4 d-flex justify-content-between">
                <h5 class="modal-title tred login-header">Ulasan</h5>
                <button id="login-exit" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <div class="white-content border-yellow p-4 mb-5">
            <!-- Pertanyaan Customer -->
            <div class="borbot-gray">
                <div class="d-flex">
                    <img class="customer-reviews-profile" src="{{ asset('img/book/jujutsu-kaisen-01.jpg') }}">
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
                    <img class="customer-reviews-profile" src="{{ asset('img/book/jujutsu-kaisen-01.jpg') }}">
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
    </div>
</div>

@endsection
