@extends('layouts.app')
@section('content')

<div class="home-and-anymore-show">
    <a class="tsmall" href="{{ route('home') }}">Home</a><i class="fas fa-caret-right tsmall"></i>
    <a class="tsmall" href="#">Categories</a><i class="fas fa-caret-right tsmall"></i>
    <a class="tsmall" href="#">{{ $book->categories[0]->name }}</a><i class="fas fa-caret-right tsmall"></i>
    <span class="tsmall tred-bold">{{ $book->name }}</span>
</div>

@if (session('pesan') || $errors->any())
<div id="pesan" class="mt-4 alert alert-primary" role="alert">
    @if (session('pesan'))
    <div><strong>{{ session('pesan') }}</strong></div>
    @endif

    @if ($errors->any())
    @foreach ($errors->all() as $error)
    <div>
        <strong>{{ $error }}</strong>
    </div>
    @endforeach
    @endif
</div>
@endif

<div id="pesan" class="mt-4 alert d-none alert-warning" role="alert">
    <strong></strong>
</div>


<div id="buying-content" class="d-flex">
    <div class="purchase-records">
        <div class="white-content px-0 pt-0 pb-4 borbot-gray-bold">
            <div class="white-content-header">
                <h4 class="hd-14">{{ $book->name }}</h4>
            </div>
            <div class="container mx-3">
                <div class="book-buy-desc d-sm-flex">
                    <div class="mw-17 mr-sm-4">
                        <div>
                            <img class="w-100" src="{{ asset('storage/books/' . $book->image) }}">
                        </div>
                    </div>
                    <div class="d-flex flex-wrap">
                        <div class="text-righteous mr-5">
                            <div>Harga</div>
                            <div class="tred-bold">{{ rupiah_format($book->price) }}</div>
                        </div>
                        <div class="text-righteous mr-5">
                            <div>Jumlah</div>
                            <div class="d-flex">
                                <div>
                                    <span id="book-needed">1</span>
                                    <span>/</span>
                                    <span id="total-book" data-total-book="{{ $book->printed_book_stock }}">{{ $book->printed_book_stock - 1 }}</span>
                                </div>
                                <div class="ml-2">
                                    <button id="plus-one-book" class="btn-none p-0"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                                    <button id="sub-one-book" class="btn-none p-0"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="text-righteous mr-5">
                            <div>Berat Barang</div>
                            <div id="weight" data-weight="300">{{ $book->weight }} gram</div>
                        </div>
                        <div class="text-righteous mr-5">
                            <div>Asuransi Pengiriman <i class="fa fa-info-circle" aria-hidden="true"></i></div>
                            <div>
                                <label class="d-flex c-p">
                                    <input type="checkbox" class="mt-1 mr-2 c-p" name="shipping_insurance" value="1000" id="shipping-insurance">
                                    <div>Rp1.000</div>
                                </label>
                            </div>
                        </div>
                        <div class="text-righteous mr-5">
                            <div>Versi Buku</div>
                            <div class="tred-bold">{{ $book_version === 'ebook' ? 'Buku Cetal, E-Book' : 'Buku Cetak' }}</div>
                        </div>
                    </div>
                </div>
                <div class="text-righteous mt-4">
                    <button id="btn-write-notes" class="btn-none"><i class="fas fa-pencil-alt"></i> Tulis Catatan</button>
                    <div id="input-write-notes" class="d-none">
                        <div class="d-flex">
                            <input type="text" class="form-control w-25" name="write_note" id="write-note" aria-describedby="helpId" placeholder="Contoh : Ebook, Buku Cetak">
                            <div class="d-flex align-items-center ml-2">
                                <button id="write-notes-cancel" class="btn-none tred-bold">Batal</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="white-content px-0 pt-0 pb-4 m-0 mt-5 borbot-gray-bold">
            <div class="white-content-header-2">
                <div class="d-flex justify-content-between w-100">
                    <div id="alamat-pengiriman">
                        <h4 class="hd-18">Alamat Pengiriman</h4>
                    </div>
                    <div>
                        @if (count($user->customer) < 5) <button id="customer-store-modal-trigger" class="btn-none tred-bold" data-toggle="modal" data-target="#modal-customer-store">Tambah</button>
                            @endif

                            <form id="customer-store" data-id="{{ $user->id }}" action="{{ route('customers.store')  }}" method="post">

                                @include('book.form-edit-delete-customer',
                                array(
                                'modal_trigger' => 'modal-customer-store',
                                'modal_header' => 'Tambah Alamat Tujuan',
                                'modal_submit_button' => 'Tambah',
                                )
                                )
                                @csrf
                            </form>
                    </div>
                </div>
            </div>
            <div class="container px-4 mt-4">
                <div id="customer-store-msg" class="alert d-none mt-4 alert-warning" role="alert">
                    <strong></strong>
                </div>
                <div>
                    <div>
                        <div class="mb-2">
                            <span class="tbold">Alamat Smartperpus</span> -
                            <span id="origin" class="text-grey tbold" data-origin-id="22" data-subdistrict-id="337" data-origin-type="subdistrict">
                                Jl Pasir Honje No. 221, Cimenyan, Kota Bandung, Jawa Barat
                            </span>
                        </div>
                        <div id="data-customers">
                            @forelse ($user->customer->take(5) as $customer)
                            <div class="data-customer mt-3 pb-3">
                                <div class="row justify-content-between">
                                    <div class="col-10">
                                        <div class="destination">
                                            <i class="fas fa-circle-notch text-grey mr-1"></i>
                                            <span class="tbold">Alamat Tujuan</span>
                                            <div id="destination" class="text-grey" data-destination-id="22" data-subdistrict-id="317" data-destination-type="subdistrict">
                                                <span class="customer-address">{{ $customer->address . ', ' }}</span>
                                                <span class="customer-district">{{ $customer->district . ', ' }}</span>
                                                <span class="customer-city">{{ $customer->city  . '. ' }}</span>
                                                <span class="customer-province">{{ $customer->province }}</span>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <i class="fas fa-circle-notch text-grey mr-1"></i>
                                            <span class="tbold">Nama Penerima</span> -
                                            <span class="customer-name text-grey">{{ $customer->name }}</span>
                                            <span class="customer-phone-number text-grey">( {{ $customer->phone_number }} )</span>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="ml-auto w-maxc">
                                            <label>
                                                <input class="customer-address" type="radio" name="alamat_pengiriman" value="{{ $customer->id }}">
                                                <span>Pilih</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex mt-2 borbot-gray-0">
                                    <div class="ml-auto">
                                        <div class="d-flex">
                                            <div class="c-middle mr-1">
                                                <button class="customer-edit btn-none tred-bold" data-id="{{ $customer->id }}" data-toggle="modal" data-target="#modal-customer-edit">Edit</button>
                                            </div>
                                            <div>
                                                <form class="customer-destroy-form" data-id="{{ $customer->id }}" action="{{ route('customers.destroy', array('customer' =>  $customer->id)) }}" method="post">
                                                    <button class="btn btn-red btn-sm-0" type="submit">Hapus</button>

                                                    @method('DELETE')
                                                    @csrf
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div id="customer-store-empty">
                                <div class="d-flex mt-4 mx-3">
                                    <div>
                                        <img class="w-100" src="{{ asset('img/map-book2.png') }}">
                                    </div>
                                    <div class="col-9 ml-2 c-middle">
                                        <h4 class="tred-bold hd-18">Alamat Tujuan Wajib Diisi</h4>
                                    </div>
                                </div>

                            </div>
                            @endforelse

                            <div>
                                <form id="customer-edit-form" action="#" data method="post">

                                    @include('book.form-edit-delete-customer',
                                    array(
                                    'modal_trigger' => 'modal-customer-edit',
                                    'modal_header' => 'Edit Alamat Tujuan',
                                    'modal_submit_button' => 'Edit',
                                    )
                                    )

                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="courier-choise" class="white-content px-0 pt-0 pb-4 mt-5 borbot-gray-bold">
            <div class="white-content-header-2 d-block">
                <h4 class="hd-18">Pilih Kurir</h4>
                <form>
                    <div class="ml-3 mt-4 d-flex overflow-auto">
                        <div class="courier-choise">
                            <input type="radio" name="courier-choise" value="jne" class="d-none">
                            <img src="{{ asset('img/couriers/jne.jpg') }}">
                        </div>
                        <div class="courier-choise">
                            <input type="radio" name="courier-choise" value="tiki" class="d-none">
                            <img src="{{ asset('img/couriers/tiki.jpg') }}">
                        </div>
                        <div class="courier-choise">
                            <input type="radio" name="courier-choise" value="pos" class="d-none">
                            <img src="{{ asset('img/couriers/pos.png') }}">
                        </div>
                    </div>
                </form>


                <!-- <button id="cek-ongkir" type="submit">Cek Ongkir</button> -->
            </div>
            <div class="container ml-3 mt-4">
                <h4 id="courier-choise-name" class="hd-18 mb-3"></h4>
                <div id="courier-choise-result">
                </div>
            </div>
        </div>
        <div class="white-content px-0 pt-0 pb-4 mt-5 borbot-gray-bold">
            <div class="white-content-header-2 d-block">
                <h4 class="hd-18">Metode Pembayaran</h4>
            </div>
            <div class="container ml-3 mt-4">
                <div class="d-flex">
                    <div>
                        <label class="c-p d-block">
                            <div>
                                <input type="radio" name="payment_method" class="inp-payment-method mr-2" value="Transfer Bank BRI" id="">
                                <img src="{{ asset('img/transfer/bri.png') }}" class="img-transfer mr-1" alt="" srcset="">
                                <span class="tbold">Transfer Bank BRI</span>
                            </div>
                        </label>
                        <label class="c-p d-block">
                            <div class="mt-4">
                                <input type="radio" name="payment_method" class="inp-payment-method mr-2" value="Transfer Bank BNI" id="">
                                <img src="{{ asset('img/transfer/bni.png') }}" style="width:47px;" class="img-transfer mr-1" alt="" srcset="">
                                <span class="tbold">Transfer Bank BNI</span>
                            </div>
                        </label>
                        <label class="c-p d-block">
                            <div class="mt-4">
                                <input type="radio" name="payment_method" class="inp-payment-method mr-2" value="Transfer Bank BCA" id="">
                                <img src="{{ asset('img/transfer/bca.png') }}" style="width:70px;" class="img-transfer mr-1" alt="" srcset="">
                                <span class="tbold">Transfer Bank BCA</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pay-for-the-book">
        <div class="w-75 ml-auto">
            <form id="book-payment-form" data-id="{{ $book->id }}" action="{{ route('book-purchases.store', array('book' => $book->id))  }}" method="post">
                <div class="white-content pt-0">
                    <div class="d-flex flex-column">
                        <div id="book-payment" class="text-greypy-2 mb-0 bordash-gray">
                            <div class="my-4">
                                <h4 class="hd-16 text-center text-body">Ringkasan Pembayaran</h4>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>Harga</div>
                                <div id="book-price" class="text-right" data-book-price="{{ $book->price }}">{{ rupiah_format($book->price) }}</div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>Jumlah Barang</div>
                                <div class="text-right" id="jumlah-barang">1</div>
                            </div>
                        </div>
                        <div class="mt-auto text-grey">
                            <div class="d-flex justify-content-between">
                                <div>Total Pembayaran</div>
                                <div id="total-payment" data-total-payment="{{ $book->price }}" class="tred-bold text-righteous text-right">{{ rupiah_format($book->price) }}</div>
                            </div>
                            <div class="mt-3">
                                <input type="hidden" id="book-version" name="book_version" value="{{ $book_version === 'ebook' ? 'ebook' : 'hard_cover' }}">
                                <input type="hidden" id="book-amount" name="amount">
                                <input type="hidden" id="book-courier-name" name="courier_name">
                                <input type="hidden" id="book-courier-service" name="pilihan_kurir">
                                <input type="hidden" id="book-shipping-cost" name="shipping_cost">
                                <input type="hidden" id="book-note" name="note">
                                <input type="hidden" id="book-insurance" name="insurance">
                                <input type="hidden" id="book-unique-code" name="unique_code">
                                <input type="hidden" id="book-total-payment" name="total_pembayaran">
                                <input type="hidden" id="book-payment-method" name="metode_pembayaran">
                                <input type="hidden" id="book-customer-address" name="alamat_pengiriman">
                                <button type="submit" id="payment-button" class="btn btn-red w-100">
                                    <i class="fas fa-shield-alt mr-2"></i>Bayar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @csrf
            </form>
        </div>
    </div>
</div>

@endsection
