@extends('layouts.app')
@section('content')

<div class="borbot-gray-bold">
    <div class="d-flex w-maxc c-p text-grey">
        <div class="d-flex mr-4">
            <i class="fa fa-heart mr-2 tred f-20" aria-hidden="true"></i>
            <h4 class="hd-16">Daftar Wishlist</h4>
        </div>
        <div class="d-flex mr-4 active-authbook">
            <i class="fas fa-shopping-basket mr-2 text-green f-20"></i>
            <h4 class="hd-16">Keranjang Saya</h4>
        </div>
        <div class="d-flex mr-4">
            <i class="fas fa-user-circle mr-2 f-20 text-grey"></i>
            <h4 class="hd-16">Akun Saya</h4>
        </div>
    </div>
</div>

<div class="d-flex mt-5">
    <div class="purchase-records">
        <div class="white-content px-0 pt-0 m-0 borbot-yellow-bold">
            <div class="p-15 pb-0 d-flex">
                <input type="checkbox" class="mt-1 mr-2" name="" id="checked-all">
                <label for="checked-all" class="tbold m-0">Pilih Semua</label>
            </div>
        </div>
        <div class="white-content mt-4 px-0 pt-0 pb-4 m-0 borbot-gray-bold">
            <div class="white-content-header-2">
                <div class="d-flex justify-content-between w-100">
                    <div class="d-flex">
                        <input type="checkbox" class="mt-1 mr-2" name="" id="pilih">
                        <label for="pilih" class="tbold m-0">Pilih</label>
                    </div>
                    <div>
                        <div class="tred-bold">Hapus</div>
                    </div>
                </div>
            </div>
            <div class="container mx-3 mt-4">
                <div class="ml-1">
                    <div class="d-flex">
                        <div>
                            <img class="anjir-img" src="{{ asset('img/book/jujutsu-kaisen-01.jpg') }}">
                        </div>
                        <div class="w-30 pl-3">
                            <div class="text-righteous overflow-hidden">Jujutsu Kaisen 01</div>
                        </div>
                        <div class="d-flex flex-column justify-content-between">
                            <div class="d-flex">
                                <div class="mr-5">
                                    <div class="tbold">Harga</div>
                                    <div class="text-grey">Rp30.000</div>
                                </div>
                                <div class="mr-5">
                                    <div class="tbold">Jumlah</div>
                                    <div class="text-grey d-flex">
                                        <div>
                                            <span id="book-needed">1</span>
                                            <span>/</span>
                                            <span id="total-book" data-total-book="">230</span>
                                        </div>
                                        <div class="ml-2">
                                            <button id="plus-one-book" class="btn-none p-0"><i class="fa fa-plus-circle"
                                            aria-hidden="true"></i></button>
                                            <button id="sub-one-book" class="btn-none p-0"><i class="fa fa-minus-circle"
                                            aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <i class="fas fa-pencil-alt mr-1" aria-hidden="true"></i>
                                <span class="tred-bold">Tulis Catatan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="white-content mt-4 px-0 pt-0 pb-4 m-0 borbot-gray-bold">
            <div class="white-content-header-2">
                <div class="d-flex justify-content-between w-100">
                    <div class="d-flex">
                        <input type="checkbox" class="mt-1 mr-2" name="" id="pilih">
                        <label for="pilih" class="tbold m-0">Pilih</label>
                    </div>
                    <div>
                        <div class="tred-bold">Hapus</div>
                    </div>
                </div>
            </div>
            <div class="container mx-3 mt-4">
                <div class="ml-1">
                    <div class="d-flex">
                        <div class="w-12">
                            <img class="w-100" src="{{ asset('img/book/jujutsu-kaisen-02.jpg') }}">
                        </div>
                        <div class="w-30 pl-3">
                            <div class="text-righteous overflow-hidden">Jujutsu Kaisen 02</div>
                        </div>
                        <div class="d-flex flex-column justify-content-between">
                            <div class="d-flex">
                                <div class="mr-5">
                                    <div class="tbold">Harga</div>
                                    <div class="text-grey">Rp30.000</div>
                                </div>
                                <div class="mr-5">
                                    <div class="tbold">Jumlah</div>
                                    <div class="text-grey d-flex">
                                        <div>
                                            <span id="book-needed">1</span>
                                            <span>/</span>
                                            <span id="total-book" data-total-book="">230</span>
                                        </div>
                                        <div class="ml-2">
                                            <button id="plus-one-book" class="btn-none p-0"><i class="fa fa-plus-circle"
                                            aria-hidden="true"></i></button>
                                            <button id="sub-one-book" class="btn-none p-0"><i class="fa fa-minus-circle"
                                            aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <i class="fas fa-pencil-alt mr-1" aria-hidden="true"></i>
                                <span class="tred-bold">Tulis Catatan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="white-content mt-4 px-0 pt-0 pb-4 m-0 borbot-gray-bold">
            <div class="white-content-header-2">
                <div class="d-flex justify-content-between w-100">
                    <div class="d-flex">
                        <input type="checkbox" class="mt-1 mr-2" name="" id="pilih">
                        <label for="pilih" class="tbold m-0">Pilih</label>
                    </div>
                    <div>
                        <div class="tred-bold">Hapus</div>
                    </div>
                </div>
            </div>
            <div class="container mx-3 mt-4">
                <div class="ml-1">
                    <div class="d-flex">
                        <div class="w-12">
                            <img class="w-100" src="{{ asset('img/book/detektif-conan-96.jpg') }}">
                        </div>
                        <div class="w-30 pl-3">
                            <div class="text-righteous overflow-hidden">Detektif Conan 96</div>
                        </div>
                        <div class="d-flex flex-column justify-content-between">
                            <div class="d-flex">
                                <div class="mr-5">
                                    <div class="tbold">Harga</div>
                                    <div class="text-grey">Rp30.000</div>
                                </div>
                                <div class="mr-5">
                                    <div class="tbold">Jumlah</div>
                                    <div class="text-grey d-flex">
                                        <div>
                                            <span id="book-needed">1</span>
                                            <span>/</span>
                                            <span id="total-book" data-total-book="">230</span>
                                        </div>
                                        <div class="ml-2">
                                            <button id="plus-one-book" class="btn-none p-0"><i class="fa fa-plus-circle"
                                            aria-hidden="true"></i></button>
                                            <button id="sub-one-book" class="btn-none p-0"><i class="fa fa-minus-circle"
                                            aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <i class="fas fa-pencil-alt mr-1" aria-hidden="true"></i>
                                <span class="tred-bold">Tulis Catatan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pay-for-the-book">
        <div class="w-75 ml-auto">
            <div class="white-content pt-0 m-0">
                <div id="book-payment" class="text-grey py-2 mb-0 bordash-gray">
                    <div class="mt-2 mb-4">
                        <h4 class="hd-16 text-center text-body">Ringkasan Pembayaran</h4>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Jumlah Barang</div>
                        <div id="jumlah-barang">1</div>
                    </div>
                </div>
                <div class="mt-2 text-grey">
                    <div class="d-flex justify-content-between">
                        <div>Total Pembayaran</div>
                        <div id="total-payment" data-total-payment=""
                          class="tred-bold text-righteous">Rp30.000</div>
                    </div>
                    <div class="mt-3">
                        <a id="payment-button" href="#" class="btn btn-red w-100">
                            <i class="fas fa-shield-alt mr-2"></i>Bayar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
