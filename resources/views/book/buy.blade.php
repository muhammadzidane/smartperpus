@extends('layouts.app')
@section('content')



<div class="home-and-anymore-show">
    <a class="tsmall" href="#">Home</a><i class="fas fa-caret-right tsmall"></i>
    <a class="tsmall" href="#">Categories</a><i class="fas fa-caret-right tsmall"></i>
    <a class="tsmall" href="#">{{ $book->categories[0]->name }}</a><i class="fas fa-caret-right tsmall"></i>
    <span class="tsmall tred-bold">{{ $book->name }}</>
</div>
<div id="buying-content" class="d-flex">
    <div class="purchase-records">
        <div class="white-content px-0 pt-0 pb-4 borbot-gray-bold">
            <div class="white-content-header">
                <h4 class="hd-18">{{ $book->name }}</h4>
            </div>
            <div class="container mx-3">
            <div>
                <div class="d-flex flex-wrap">
                    <div class="text-righteous w-maxc flex-grow-1 mb-2 mr-2">
                        <div>Harga</div>
                        <div class="tred-bold">{{ rupiah_format($book->price) }}</div>
                    </div>
                    <div class="text-righteous w-maxc flex-grow-1 mb-2 mr-2">
                        <div>Jumlah</div>
                        <div class="d-flex">
                            <div>
                                <span id="book-needed">1</span>
                                <span>/</span>
                                <span id="total-book" data-total-book="{{ $book->printedStock->amount }}">{{ $book->printedStock->amount - 1 }}</span>
                            </div>
                            <div class="ml-2">
                                <button id="plus-one-book" class="btn-none p-0"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                                <button id="sub-one-book" class="btn-none p-0"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="text-righteous w-maxc flex-grow-1 mb-2 mr-2">
                        <div>Berat Barang</div>
                        <div id="weight" data-weight="300">300 gram</div>
                    </div>
                    <div class="text-righteous w-maxc flex-grow-1 mb-2 mr-2">
                        <div>Asuransi Pengiriman <i class="fa fa-info-circle" aria-hidden="true"></i></div>
                        <div>
                            <label class="d-flex c-p">
                                <input type="checkbox" class="mt-1 mr-2 c-p" name="shipping_insurance" id="shipping-insurance">
                                <div>Rp1.000</div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
                <div class="text-righteous mt-4 pl-3">
                    <button id="btn-write-notes" class="btn-none"><i class="fas fa-pencil-alt"></i> Tulis Catatan</button>
                    <div id="input-write-notes" class="d-none">
                        <div class="d-flex">
                            <input type="text" class="form-control w-25" name="write_note_val" id="write-note-val" aria-describedby="helpId"
                              placeholder="Contoh : Ebook, Buku Cetak">
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
                <h4 class="hd-18">Alamat Pengiriman</h4>
            </div>
            <div class="container ml-3 mt-4">
                <div class="d-flex">
                    <div>
                        <div class="mb-2">
                            <span class="tbold">Alamat Smartperpus</span> -
                            <span id="origin" class="text-grey" data-origin-id="22"
                              data-subdistrict-id="337" data-origin-type="subdistrict">Jl Pahlawan No. 92, Cikadut,  Kota. Bandung, Jawa Barat</span>
                        </div>
                        <div>
                            <i class="fas fa-circle-notch text-grey mr-1"></i>
                            <span class="tbold">Rumah</span> -
                            <span id="destination" class="text-grey" data-destination-id="22"
                              data-subdistrict-id="317" data-destination-type="subdistrict">Jl . Pasir Honje No. 221, Cimenyan, Kab. Bandung, Jawa Barat</span>
                            <a href="#" class="text-grey tbold ml-2">Ubah</a>
                        </div>
                        <div>
                            <i class="fas fa-circle-notch text-grey mr-1"></i>
                            <span class="tbold">Nama</span> -
                            <span class="text-grey">Muhammad Zidane Alsaadawi - 081321407123</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="white-content px-0 pt-0 pb-4 mt-5 borbot-gray-bold">
            <div class="white-content-header-2 d-block">
                <h4 class="hd-18">Pilih Kurir</h4>
                <form>
                    <div class="ml-3 mt-4 d-flex">
                        <div class="courier-choise">
                            <input type="radio" name="courier-choise" value="jne" class="d-none">
                            <img src="{{ asset('img/couriers/jne.jpg') }}" alt="" srcset="">
                        </div>
                        <div class="courier-choise">
                            <input type="radio" name="courier-choise" value="tiki" class="d-none">
                            <img src="{{ asset('img/couriers/tiki.jpg') }}" alt="" srcset="">
                        </div>
                        <div class="courier-choise">
                            <input type="radio" name="courier-choise" value="pos" class="d-none">
                            <img src="{{ asset('img/couriers/pos.png') }}" alt="" srcset="">
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
                                <input type="radio" name="test-bri" class="test-bri" id="">
                                <img src="{{ asset('img/transfer/bri.png') }}" class="img-transfer" alt="" srcset="">
                                <span class="tbold">Transfer Bank BRI</span>
                            </div>
                        </label>
                        <label class="c-p d-block">
                            <div class="mt-3">
                                <input type="radio" name="test-bri" class="test-bri" id="">
                                <img src="{{ asset('img/transfer/bni.png') }}" style="width:47px;" class="img-transfer" alt="" srcset="">
                                <span class="tbold">Transfer Bank BNI</span>
                            </div>
                        </label>
                        <label class="c-p d-block">
                            <div class="mt-3">
                                <input type="radio" name="test-bri" class="test-bri" id="">
                                <img src="{{ asset('img/transfer/gopay.png') }}" style="width:70px;" class="img-transfer" alt="" srcset="">
                                <span class="tbold">Transfer Gopay</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pay-for-the-book">
        <div class="w-75 ml-auto">
            <div class="white-content pt-0">
                <div>
                    <img id="buying-img" class="w-90 d-block mx-auto" src="{{ asset('img/book/' . $book->image) }}" alt="" srcset="">
                </div>
                <div class="d-flex flex-column">
                    <div id="book-payment" class="text-grey mt-4 py-2 mb-0 bordash-gray">
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
                            <div id="total-payment" data-total-payment="{{ $book->price }}"
                              class="tred-bold text-righteous text-right">{{ rupiah_format($book->price) }}</div>
                        </div>
                        <div class="mt-3">
                            <a id="payment-button" href="{{ route('books.payment', array('book' => $book->name)) }}" class="btn btn-red w-100">
                                <i class="fas fa-shield-alt mr-2"></i>Bayar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
