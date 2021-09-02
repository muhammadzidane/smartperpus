@extends('layouts.app')
@section('content')

<div class="borbot-gray-bold">
    <div class="w-maxc d-flex c-p text-grey pb-1">
        <div class="d-flex mr-4 active-authbook">
            <i class="fas fa-shopping-basket mr-2 text-green f-20"></i>
            <h4 class="hd-14">Checkout</h4>
        </div>
    </div>
</div>

<form id="checkout-form" action="#" method="GET">
    <div class="row flex-md-row-reverse mt-4">
        <div class="col-md-3 mb-4">
            <div class="white-content pt-0 m-0">
                <div id="book-payment" class="text-grey py-2 mb-0 borbot-gray pb-3">
                    <div class="mt-2 mb-4">
                        <h4 class="hd-16 text-center text-body">Ringkasan Pembayaran</h4>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Jumlah Barang</div>
                        <input name="amount" type="text" value="{{ request('amount') }}" class="input-none text-right w-50" readonly>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Ongkos Kirim</div>
                        <input id="checkout-shipping-price" type="text" value="0" class="input-none text-right w-50" readonly>
                    </div>
                </div>
                <div class="mt-2 text-grey">
                    <div class="d-flex justify-content-between">
                        <div>Total Pembayaran</div>
                        <input name="total" type="text" value="{{ request('total') }}" class="input-none text-right tred-bold w-50" readonly>
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
                <div class="d-flex justify-content-between">
                    <div id="user-customer-title">
                        <span class="mr-2">
                            <i class="fas fa-map-marker-alt fa-2x tred-bold"></i>
                        </span>
                        <h4 class="d-inline">Alamat Pengiriman</h4>
                    </div>
                    @if (auth()->user()->customers->count() != 0 && auth()->user()->customers->count() < 5) <button id="user-create-customer" class="btn-none tred-bold">Tambah</button>
                        @endif
                </div>
                <div id="user-customer-lists">
                    @forelse (auth()->user()->customers->take(5) as $customer)
                    <div class="user-customer mt-3 d-flex borbot-gray-0 pb-2">
                        <label>
                            <div class="d-flex">
                                <div class="mr-2 d-flex">
                                    <input type="radio" name="customer" class="my-auto">
                                </div>
                                <div>
                                    <div>
                                        <span class="customer-name">{{ $customer->name }}</span> -
                                        <span class="customer-phone-number">{{ $customer->phone_number }}</span>
                                    </div>
                                    <div>
                                        <span class="customer-address">{{ $customer->address }}</span>.
                                        <span class="customer-province" data-province="{{ $customer->province->id }}">{{ $customer->province->name }},</span>
                                        <span class="customer-city" data-city="{{ $customer->city->id }}">{{ $customer->city->type  . ' ' . $customer->city->name }},</span>
                                        <span class="customer-district" data-district="{{ $customer->district->id }}">{{ $customer->district->name }}</span>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <div class="ml-auto text-right">
                            <div>
                                <button class="user-customer-update btn-none tred-bold" type="button" data-id="{{ $customer->id }}">Ubah</button>
                            </div>
                            <div>
                                <button class="user-customer-delete btn-none tred-bold" type="button" data-id="{{ $customer->id }}">Hapus</butt>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="mt-4">
                        <span id="user-customer-message">Anda belum memiliki alamat pengiriman.</span>
                        <button id="user-create-customer" class="btn-none tred-bold">Tambah</button>
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="white-content mt-4">
                <div id="checkout-courier-choise">
                    <div id="checkout-courier-choise-title">
                        <div class="tbold">Pilih Kurir</div>
                    </div>
                    <span>* Dikirim dari Kec. Cimenyan, Kab. Bandung Jawa Barat</span>
                    <div class="radio-toolbar mt-3">
                        <input type="radio" id="jne" name="courier" value="jne">
                        <label for="jne">JNE</label>

                        <input type="radio" id="pos" name="courier" value="pos">
                        <label for="pos">Pos Indonesia</label>

                        <input type="radio" id="tiki" name="courier" value="tiki">
                        <label for="tiki">TIKI</label>
                    </div>
                </div>
            </div>
            <div class="white-content mt-4">
                <div>
                    <div id="payment-method-title" class="tbold">Metode pembayaran</div>
                    <div class="radio-toolbar mt-3">
                        <input type="radio" id="bri" name="payment_method" value="all">
                        <label for="bri">Transfer Bank BRI</label>

                        <input type="radio" id="bni" name="payment_method" value="false">
                        <label for="bni">Transfer Bank BNI</label>

                        <input type="radio" id="bca" name="payment_method" value="true">
                        <label for="bca">Transfer Bank BCA</label>
                    </div>
                </div>
            </div>
            @foreach ($datas as $data)
            <div class="customer-book white-content mt-4 borbot-gray-bold">
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
                                    <div class="mr-4">
                                        <div class="tbold">Berat</div>
                                        <div class="text-grey">
                                            <span class="book-weight">{{ $data['book']->weight }}</span>
                                            <span>gram</span>
                                        </div>
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
