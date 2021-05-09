@extends('layouts.app')
@section('content')

<h1 id="test" class="w-sm-100">wkwk</h1>

<div class="borbot-gray-bold">
    <div class="d-flex w-maxc c-p text-grey">
        <div class="d-flex mr-4">
            <i class="fa fa-heart mr-2 tred f-20" aria-hidden="true"></i>
            <h4 class="hd-16">Daftar Wishlist</h4>
        </div>
        <div class="d-flex mr-4">
            <i class="fas fa-shopping-basket mr-2 text-green f-20"></i>
            <h4 class="hd-16">Keranjang Saya</h4>
        </div>
        <div class="d-flex mr-4 active-authbook">
            <i class="fas fa-user-circle mr-2 f-20 text-grey"></i>
            <h4 class="hd-16">Akun Saya</h4>
        </div>
    </div>
</div>

<div class="mt-5">
    <div class="w-78 mr-5 bg-dark w-sm-100">
        <div class="white-content px-0 pt-0 pb-4 m-0 borbot-gray-bold">
            <div class="container pt-4">
                <div class="d-flex container pb-4 borbot-gray-0">
                    <div class="change-profile-img">
                        <div>
                            <div>
                                <img class="profile-img" src="{{ asset('img/avatar-icon.png') }}" alt="">
                            </div>
                            <div class="mt-4">
                                <button type="button" class="btn btn-sm btn-outline-yellow w-100">
                                    <i class="fas fa-images"></i>
                                    <span>Tambah Foto</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="w-79">
                        <div class="ml-5 d-flex justify-content-between overflow-hidden">
                            <div class="tbold">
                                <div>
                                    <div>Nama</div>
                                    <div class="mt-2">Tanggal Lahir</div>
                                    <div class="mt-2">Jenis Kelamin</div>
                                </div>
                                <div class="mt-4">
                                    <div>Email</div>
                                    <div class="mt-2">Nomer Handphone</div>
                                </div>
                            </div>
                            <div class="text-grey">
                                <div>
                                    <div>Muhammad Zidane Alsaadawi</div>
                                    <div class="mt-2">19 Juli 2000</div>
                                    <div class="mt-2 tred-bold">Tambah jenis kelamin</div>
                                </div>
                                <div class="mt-4">
                                    <div>muhammmadzidanealsaadawi@gmail.com</div>
                                    <div class="mt-2">0895364040902</div>
                                </div>
                            </div>
                            <div class="tred-bold">
                                <div>
                                    <div>Ubah</div>
                                    <div class="mt-2">Ubah</div>
                                    <div class="mt-2">Ubah</div>
                                </div>
                                <div class="mt-4">
                                    <div>Ubah</div>
                                    <div class="mt-2">Ubah</div>
                                </div>
                            </div>
                        </div>
                        <div class="ml-5 change-password">
                            <button class="btn btn-sm btn-outline-yellow">Ubah Password</button>
                        </div>
                    </div>
                </div>
                <div class="mx-3 mt-3 change-profile-img">
                    <p class="text-grey">Besar file: maksimum 10.000.000 bytes (10 Megabytes). Ekstensi file yang diperbolehkan: .JPG .JPEG .PNG</p>
                </div>
            </div>
        </div>
    </div>
    <div class="w-22">
        <div class="white-content m-0 h-100 borbot-gray-bold">
            <div class="borbot-gray-0 pb-3">
                <div class="container mt-1">
                    <h4 class="hd-16">Pembelian</h4>
                    <div class="text-grey">
                        <div class="d-flex position-relative">
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
                        <div>Ulasan</div>
                        <div>Diskusi produk</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
