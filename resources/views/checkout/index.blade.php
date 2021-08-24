@extends('layouts.app')
@section('content')

<div class="borbot-gray-bold">
    <div class="w-maxc d-flex c-p text-grey pb-1">
        <div class="d-flex mr-4 active-authbook">
            <i class="fas fa-shopping-basket mr-2 text-green f-20"></i>
            <h4 class="hd-14">Keranjang Saya</h4>
        </div>
    </div>
</div>

<form id="cart-form" action="{{ route('checkout.index') }}" method="GET">
    <div class="row flex-md-row-reverse mt-4">
        <div class="col-md-3 mb-4">
            <div class="white-content pt-0 m-0">
                <div id="book-payment" class="text-grey py-2 mb-0 bordash-gray">
                    <div class="mt-2 mb-4">
                        <h4 class="hd-16 text-center text-body">Ringkasan Pembayaran</h4>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Jumlah Barang</div>
                        <input id="cart-amounts" name="amount" type="text" value="{{ request('amount') }}" class="input-none text-right w-25" readonly>
                    </div>
                </div>
                <div class="mt-2 text-grey">
                    <div class="d-flex justify-content-between">
                        <div>Total Pembayaran</div>
                        <input id="cart-total-payment" name="total" type="text" value="{{ request('total') }}" class="input-none text-right tred-bold w-50" readonly>
                    </div>
                    <div class="mt-3">
                        <button type="submit" id="payment-button" class="btn btn-red w-100">
                            <i class="fas fa-shield-alt mr-2"></i>Bayar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="white-content borbot-gray-bold mt-0">
                <div>
                    <span class="mr-2">
                        <i class="fas fa-map-marker-alt fa-2x tred-bold"></i>
                    </span>
                    <h4 class="d-inline">Alamat Pengiriman</h4>
                </div>
                <div>
                    <div class="mt-3 d-flex borbot-gray-0 pb-3">
                        <div class="d-flex">
                            <div class="d-flex mr-3">
                                <input type="radio" class="my-auto" name="address" checked>
                            </div>
                            <div>
                                <div>Muhammad Zidane Alsaadawi</div>
                                <div>
                                    <span>Jl Pasir Honje No 221,</span>
                                    <span>Cimenyan</span>
                                    <span>Kabupaten Bandung</span>
                                    <span>Jawa Barat</span>
                                </div>
                            </div>
                        </div>
                        <div class="ml-auto">
                            <div class="text-grey">Utama</div>
                            <div class="tred-bold">Ubah</div>
                        </div>
                    </div>
                </div>
            </div>
            @foreach ($datas as $data)
            <div class="white-content mt-4 borbot-gray-bold">
                <div class="container-sm mx-sm-3 mt-4">
                    <div class="row">
                        <div class="col-sm-2 mb-4">
                            <img class="zoom-modal-image w-100" src="{{ asset('storage/books/' . $data['book']->image) }}">
                        </div>
                        <div class="col-sm-10">
                            <div class="text-righteous">{{ $data['book']->name }}</div>
                            <div class="tred-bold">{{ $data['book']->ebook ? 'E-Book' : 'Buku Cetak' }}</div>
                            <div class="mt-4 d-flex flex-md-column justify-content-md-between">
                                <div class="d-md-flex">
                                    <div class="mr-4 mb-4">
                                        <div class="tbold">Harga</div>
                                        <div class="cart-book-price text-grey" data-price="{{ $data['book']->price - $data['book']->discount }}">{{ rupiah_format($data['book']->price - $data['book']->discount)   }}</div>
                                    </div>
                                    <div class="mr-4">
                                        <div class="tbold">Jumlah</div>
                                        <div class="text-grey">{{ $data['amount'] }}</div>
                                    </div>
                                </div>
                                <div>
                                    <i class="fas fa-pencil-alt mr-1" aria-hidden="true"></i>
                                    <span class="tred-bold">Catatan:</span>
                                    <span>{{ $data['note'] ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @csrf
</form>

<div class="mt-5">
    <div class="w-maxc ml-auto">{{ 'links()' }}</div>
</div>

@endsection
