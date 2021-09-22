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

<form id="checkout-form" action="{{ route('checkout.payment', array('user' => auth()->user()->id)) }}" method="POST">
    @csrf
    <div class="row flex-md-row-reverse mt-4">
        <div class="col-md-3 mb-4">
            <div class="white-content pt-0 m-0">
                <div id="book-payment" class="text-grey py-2 mb-0 borbot-gray pb-3">
                    <div class="mt-2 mb-4">
                        <h4 class="hd-16 text-center text-body">Ringkasan Pembayaran</h4>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Jumlah Barang</div>
                        <input name="amount" type="text" value="{{ $amount }}" class="input-none text-right w-50 text-grey" readonly>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Ongkos Kirim</div>
                        <div id="checkout-shipping-price">Rp0</div>
                        <input id="checkout-shipping-cost" name="shipping_cost" type="hidden" value="0" class="input-none text-right w-50" readonly>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Subtotal Produk</div>
                        <div>{{ rupiah_format($total)  }}</div>
                    </div>
                </div>
                <div class="mt-2 text-grey">
                    <div class="d-flex justify-content-between">
                        <div>Total Pembayaran</div>
                        <div id="checkout-total-payment-text" data-price="{{ $total }}" class="tred-bold">{{ rupiah_format($total) }}</div>
                        <input name="total" type="hidden" value="{{ $total }}" class="input-none text-right tred-bold w-50" readonly>
                    </div>
                    <div class="mt-3">
                        <button type="submit" id="payment-button" class="btn btn-red w-100">
                            <i class="fas fa-shield-alt mr-2"></i>
                            <span>Buat Pesanan</span>
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
                    <div>
                        <a href="/users/{{ auth()->user()->id }}#user-customers" class="btn btn-outline-danger">Kelola Alamat</a>
                        @if (auth()->user()->customers->count() < 5) <button type="button" class="btn-none tred-bold ml-2" data-toggle="modal" data-target="#customer-store-modal">Tambah</button>
                            @endif
                    </div>
                </div>

                @if ($main_customer)
                <div id="user-customer-lists">
                    <div class="mt-4">
                        <div class="user-customer-main">Utama</div>
                        <input id="checkout-customer-main" type="hidden" name="customer" class="my-auto" value="{{ $main_customer->id }}">
                        <div class="h5 row mt-2">
                            <div class="col-md-3 mt-1 mt-md-0">
                                <div class="tbold">{{ $main_customer->name }}</div>
                                <div class="text-grey">{{ $main_customer->phone_number }}</div>
                            </div>
                            <div class="col-md-6 mt-2 mt-md-0 text-grey">
                                <div>{{ $main_customer->address }},</div>
                                <span>{{ $main_customer->city->type }}</span>
                                <span>{{ $main_customer->city->name }},</span>
                                <span id="checkout-district" data-id="{{ $main_customer->district->id }}">Kec.{{ $main_customer->district->name }},</span>
                                <span>{{ $main_customer->province->name }},</span>
                            </div>
                            @if (auth()->user()->customers()->count() != 1)
                            <div class="col-md-3 mt-2 mt-md-0">
                                <div class="text-right">
                                    <button type="button" class="btn btn-outline-danger text-right" data-toggle="modal" data-target="#checkout-address-modal">Ubah Alamat Utama</button>
                                </div>

                                <div class="modal fade" id="checkout-address-modal" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="p-3 d-flex justify-content-between">
                                                <h4 class="modal-title tred login-header">Ubah alamat utama</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body pt-0 mt-4">
                                                <form action="/checkouts/change-main-address" method="POST">

                                                    @forelse (auth()->user()->customers()->where('main', false)->get() as $customer)
                                                    <div class="container">
                                                        <div class="row mt-4 borbot-gray-0 pb-3">
                                                            <div class="col-4">
                                                                <div class="d-flex">
                                                                    <div class="my-auto mr-3">
                                                                        <label class="my-auto">
                                                                            <input form="deleteForm" type="radio" name="customer" value="{{ $customer->id }}">
                                                                            <span>Pilih</span>
                                                                        </label>
                                                                    </div>
                                                                    <div>
                                                                        <div class=" tbold">{{ $customer->name }}
                                                                        </div>
                                                                        <div class="text-grey">{{ $customer->phone_number }}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-8 text-right text-grey">
                                                                <div>{{ $customer->address }},</div>
                                                                <span>{{ $customer->city->type }}</span>
                                                                <span>{{ $customer->city->name }},</span>
                                                                <span id="checkout-district" data-id="{{ $customer->district->id }}">Kec.{{ $customer->district->name }},</span>
                                                                <span>{{ $customer->province->name }},</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @empty
                                                    <h3 class="tbold">Belum punya alamat</h3>
                                                    @endforelse
                                                    <div class="p-3 text-right">
                                                        <button form="deleteForm" type="submit" class="btn btn-outline-danger">Ubah</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="white-content mt-4">
                <div id="checkout-courier-choise">
                    <div id="checkout-courier-choise-title">
                        <div class="tbold">Pilih Kurir</div>
                    </div>
                    <span>* Dikirim dari Kec. Cimenyan, Kab. Bandung Jawa Barat</span>
                    <div class="radio-toolbar mt-3">
                        <input type="radio" id="jne" name="courier_name" value="jne">
                        <label for="jne">JNE</label>

                        <input type="radio" id="pos" name="courier_name" value="pos">
                        <label for="pos">Pos Indonesia</label>

                        <input type="radio" id="tiki" name="courier_name" value="tiki">
                        <label for="tiki">TIKI</label>
                    </div>
                </div>
            </div>
            <div class="white-content mt-4">
                <div>
                    <div id="payment-method-title" class="tbold">Metode pembayaran (Cek manual)</div>
                    <div class="radio-toolbar mt-3">
                        <input type="radio" id="bri" name="payment_method" value="bri">
                        <label for="bri">Transfer Bank BRI</label>

                        <input type="radio" id="bni" name="payment_method" value="bni">
                        <label for="bni">Transfer Bank BNI</label>

                        <input type="radio" id="bca" name="payment_method" value="bca">
                        <label for="bca">Transfer Bank BCA</label>
                    </div>
                </div>
            </div>

            @foreach ($checkouts as $checkout)
            <div class="customer-book white-content mt-4 borbot-gray-bold">
                <div class="container-sm mx-sm-3 mt-4">
                    <div class="row">
                        <div class="col-sm-2 mb-4">
                            <img class="zoom-modal-image w-100" src="{{ asset('storage/books/' . $checkout->books[0]->image) }}">
                        </div>
                        <div class="col-sm-10">
                            <div class="text-righteous">{{ $checkout->books[0]->name }}</div>
                            <div class="tred-bold">{{ $checkout->book_version == 'hard_cover' ? 'Buku Cetak' : 'E-Book' }}</div>
                            <div class="mt-4 d-flex flex-md-column justify-content-md-between">
                                <div class="d-md-flex">
                                    <div class="mr-4 mb-4">
                                        <div class="tbold">Harga</div>
                                        <div class="cart-book-price text-grey" data-price="{{ $checkout->books[0]->price - $checkout->books[0]->discount }}">{{ rupiah_format($checkout->books[0]->price - $checkout->books[0]->discount)   }}</div>
                                    </div>
                                    <div class="mr-4">
                                        <div class="tbold">Jumlah</div>
                                        <div class="text-grey">{{ $checkout->amount }}</div>
                                    </div>
                                    <div class="mr-4">
                                        <div class="tbold">Berat</div>
                                        <div class="text-grey">
                                            <span class="book-weight">{{ $checkout->books[0]->weight }}</span>
                                            <span>gram</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <i class="fas fa-pencil-alt mr-1" aria-hidden="true"></i>
                                    <span class="tred-bold">Catatan:</span>
                                    <span>{{ $checkout->note ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</form>

<form id="deleteForm" action="/checkouts/change-main-address" method="post">
    @method('PATCH')
    @csrf
</form>

<form id="checkout-customer-store" action="/checkouts/customer-store" method="POST">
    @csrf
    <div class="modal fade" id="customer-store-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="p-3 d-flex justify-content-between">
                    <h5 class="modal-title tred login-header">Tambah Alamat Pengiriman</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <div class="mt-4 form-group mx-auto">
                        <label for="nama_penerima">Nama Penerima</label>
                        <input id="user-customer-name" type="text" name="nama_penerima" class="form-control-custom book-edit-inp">
                    </div>
                    <div class="form-group mx-auto mt-4">
                        <label for="alamat_tujuan">Alamat Tujuan</label>
                        <input id="user-customer-address" type="text" name="alamat_tujuan" class="form-control-custom book-edit-inp">
                    </div>
                    <div class="form-group mx-auto mt-4 position-relative">
                        <label for="provinsi">Cari Kota / Kecamatan</label>
                        <input id="user-city-district-search" type="text" class="form-control-custom book-edit-inp">
                        <input id="user-city-or-district" type="hidden" name="kota_atau_kecamatan">
                        <div id="user-hidden-address" class="user-address">
                        </div>
                    </div>
                    <div class="form-group mx-auto mt-4">
                        <label for="nomer_handphone">Nomer Handphone</label>
                        <input id="user-customer-phone" type="text" name="nomer_handphone" class="form-control-custom book-edit-inp">
                    </div>
                    <div class="form-group mt-4 text-right asu">
                        <button class="cursor-disabled btn btn-outline-danger" type="submit" disabled>Tambah</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


<div class="mt-5">
    <div class="w-maxc ml-auto">{{ $checkouts->links() }}</div>
</div>

@endsection
