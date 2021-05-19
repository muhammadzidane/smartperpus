@extends('layouts.app')
@section('content')
<div class="row mt-5">
    <div class="col-lg-9">
        <div class="white-content-0">
            <div class="white-content-header-2 flex-column">
                <h4 class="hd-14 m-0">Daftar Transaksi</h4>
                <div class="mt-2">
                    <div class="wishlist-search">
                        <span><i class="fa fa-search wishlist-search-icon" aria-hidden="true"></i></span>
                        <input class="text-righteous hd-12" type="search" placeholder="Cari Daftar Transaksi">
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
                                    <span>Jumlah Pembelian : </span>
                                    <span class="text-grey tbold">1 x Rp.30.000 - Buku Cetak</span>
                                </div>
                                <div>
                                    <div class="text-right text-righteous">
                                        <button type="button" class="btn-none p-0 tred mr-1 hd-14"
                                          data-toggle="modal" data-target="#modal-transaction-lists">Lihat Daftar Transaksi</button>
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
                                          data-toggle="modal" data-target="#modal-transaction-lists">Lihat Daftar Transaksi</button>
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
                                          data-toggle="modal" data-target="#modal-transaction-lists">Lihat Daftar Transaksi</button>
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
    @include('account.purchases-and-inboxes', array('transaction_list' => 'active-acc'))
</div>


<!-- Modal Transacton Lists -->
<div class="modal fade" id="modal-transaction-lists" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered p-5" role="document">
        <div class="modal-content modal-content-login">
            <div class="px-3 mb-4 d-flex justify-content-between">
                <h5 class="modal-title tred login-header">Daftar Transaksi</h5>
                <button id="login-exit" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Pengiriman -->
                <div class="d-flex p-15 borbot-gray-0">
                    <div class="mr-5">
                        <div>Pengiriman</div>
                        <div class="text-grey">JNE - Reguler Expedition</div>
                    </div>
                    <div class="mr-5">
                        <div>Dikirim pada</div>
                        <div class="text-grey">
                            <div>Muhammad Zidane Alsaadawi</div>
                            <div class="mt-1">Jl Pasir Honje No 221 RT004 RW 001 Cimenyan Kab. Bandung, 40191 Jawa Barat</div>
                        </div>
                    </div>
                </div>
                <!-- Pembayaran -->
                <div class="mt-4 px-15 w-50">
                    <div>
                        <div>Pembayaran</div>
                        <div class="text-grey">
                            <div>
                                <div class="d-flex justify-content-between">
                                    <span>Buku Cetak</span>
                                    <span>1</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>E-Book</span>
                                    <span>0</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Ongkos Kirim (200gr)</span>
                                    <span>Rp20.000</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Total Pembayaran</span>
                                    <span class="tred-bold">Rp50.000</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
