@extends('layouts.app')
@section('content')


<div class="home-and-anymore-show">
    <a class="tsmall" href="#">Home</a><i class="fas fa-caret-right tsmall"></i>
    <a class="tsmall" href="#">Categories</a><i class="fas fa-caret-right tsmall"></i>
    <a class="tsmall" href="#">Komik</a><i class="fas fa-caret-right tsmall"></i>
    <span class="tsmall tred-bold">{{ 'Jujutsu Kaisen 01' }}</span>
</div>
<div class="d-flex">
    <div class="purchase-records">
        <div class="white-content px-0 pt-0 pb-4 borbot-gray-bold">
            <div class="white-content-header">
                <h4>Jujutsu Kaisen 01</h4>
            </div>
            <div class="container ml-3">
                <div class="d-flex">
                    <div class="text-righteous">
                        <div class="hd-18">Harga</div>
                        <div class="tred-bold">Rp35.000</div>
                    </div>
                    <div class="text-righteous ml-5 pl-5">
                        <div class="hd-18">Jumlah</div>
                        <div class="d-flex">
                            <div>1 / 239</div>
                            <div class="ml-2">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                <i class="fa fa-minus-circle" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <div class="text-righteous ml-5 pl-5">
                        <div class="hd-18">Asuransi Pengiriman <i class="fa fa-info-circle" aria-hidden="true"></i></div>
                        <div class="d-flex">
                            <div>Rp1.000</div>
                        </div>
                    </div>
                </div>
                <div class="text-righteous mt-4 pl-3">
                    <div><i class="fas fa-pencil-alt"></i> Tulis Catatan</div>
                </div>
            </div>
        </div>
        <div class="white-content px-0 pt-0 pb-4 m-0 mt-5 borbot-gray-bold">
            <div class="white-content-header-2">
                <h4 class="hd-18">Alamat Pengiriman</h4>
            </div>
            <div class="container ml-3 mt-2">
                <div class="d-flex">
                    <div>
                        <div class="mb-2">
                            <span class="tbold">Alamat Smartperpus</span> -
                            <span class="text-grey">Jl Pahlawan No. 92, Cikadut,  Kota. Bandung, Jawa Barat</span>
                        </div>
                        <div>
                            <i class="fas fa-circle-notch text-grey mr-1"></i>
                            <span class="tbold">Rumah</span> -
                            <span class="text-grey">Jl . Pasir Honje No. 221, Cimenyan, Kab. Bandung, Jawa Barat</span>
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
                <div class="ml-3 mt-4 d-flex">
                    <div class="courier-choise cc-active">
                        <img src="{{ asset('img/couriers/jne.jpg') }}" alt="" srcset="">
                    </div>
                    <div class="courier-choise">
                        <img src="{{ asset('img/couriers/tiki.jpg') }}" alt="" srcset="">
                    </div>
                    <div class="courier-choise">
                        <img src="{{ asset('img/couriers/pos.png') }}" alt="" srcset="">
                    </div>
                </div>
            </div>
            <div class="container ml-3 mt-2">
                <div class="d-flex">
                    <div>
                        <div>
                            <span><i class="mr-1 fa fa-check-circle circle-checked" aria-hidden="true"></i></span>
                            <span class="tbold">JNE Reguler</span>
                            <span>-</span>
                            <span class="text-grey">2 - 3 Hari</span>
                            <div class="ml-4">
                                <span>Rp20.000</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span><i class="mr-1 fa fa-check-circle hd-18 text-grey" aria-hidden="true"></i></span>
                            <span class="tbold">JNE Reguler</span>
                            <span>-</span>
                            <span class="text-grey">2 - 3 Hari</span>
                            <div class="ml-4">
                                <span>Rp20.000</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pay-for-the-book">
        <div class="w-75 ml-auto">
            <div class="white-content pt-0">
                <div>
                    <img class="w-95 d-block mx-auto" src="{{ asset('img/book/jujutsu-kaisen-01.jpg') }}" alt="" srcset="">
                </div>
                <div class="text-grey mt-4 py-2 mb-0 bordash-gray">
                    <div class="d-flex justify-content-between">
                        <div>Harga Buku</div>
                        <div>Rp35.000</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Jumlah Barang</div>
                        <div>1</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Ongkos Kirim</div>
                        <div>Rp20.000</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Asuransi Pengiriman</div>
                        <div>Rp1.000</div>
                    </div>
                </div>
                <div class="mt-2 text-grey">
                    <div class="d-flex justify-content-between">
                        <div>Total Pembayaran</div>
                        <div class="tred-bold text-righteous">Rp56.000</div>
                    </div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-red w-100"><i class="fas fa-money-check mr-2 text-success"></i>Bayar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
