@extends('layouts.app')
@section('content')

    <div class="text-center">
        <h4>Lakukan pembayaran dalam</h4>
        <h1 id="payment-limit-time" class="text-righteous my-4">24 : 00 : 00</h1>
        <div>Batas waktu pembayaran : <span id="payment-limit-date" class="tbold">Kamis, 15 September 2021</span></div>
    </div>

    <div>
        <div class="white-content px-0 pt-0 pb-4 borbot-gray-bold mt-c w-75 mx-auto">
            <div class="white-content-header-2">
                <h4 class="hd-18">Transfer Bank BRI</h4>
            </div>
            <div class="mt-4">
                <div class="container text-center borbot-gray-0 pb-4">
                    <h4 class="hd-18">Transfer pembayaran pada nomer rekening berikut :</h4>
                    <div class="mt-4">
                        <div>
                            <div class="d-flex">
                                <div>
                                    <img src="{{ asset('img/transfer/bri.png') }}" class="w-20">
                                    <h4 class="d-inline tred ml-3 f-28">1903847182123</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex p-15 ml-4">
                    <div>
                        <h4 class="hd-18">Total Pembayaran</h4>
                        <div class="hd-18 tred-bold">Rp92.300</div>
                    </div>
                    <div class="ml-4">
                        <div class="tred-bold">Lihat Detail</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="white-content px-0 pt-0 pb-4 borbot-gray-bold mt-c w-75 mx-auto">
            <div class="white-content-header-2">
                <h4 class="hd-18">Petunjuk Pembayaran</h4>
            </div>
            <div class="my-4 c-p">
                <div class="payment-instructions borbot-gray-0" data-bank="bri-atm">
                    <div class="p-15">
                        <h4 class="hd-18">BRI - ATM <i class="payment-instructions-caret fa fa-caret-right ml-2" aria-hidden="true"></i></h4>
                    </div>
                    <div class="mt-4 px-15">
                        <div>
                            <ul class="text-grey">
                                <li>1. Log in BRI Mobile Banking (unduh versi terbaru)</li>
                                <li>2. Pilih menu “PEMBAYARAN”</li>
                                <li>3. Pilih “BRIVA”</li>
                                <li>4. Masukan nomor BRI Virtual Account Anda dan jumlah pembayaran</li>
                                <li>5. Masukan Nomor Pin Anda</li>
                                <li>6. Tekan “OK” untuk melanjutkan transaksi Anda</li>
                                <li>7. Transaksi berhasil</li>
                                <li>8. Konfirmasi sms akan masuk ke nomor telepon Anda</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="payment-instructions borbot-gray-0" data-bank="bri-internet-banking">
                    <div class="p-15">
                        <h4 class="hd-18">BRI - Internet Banking <i class="payment-instructions-caret fa fa-caret-right ml-2" aria-hidden="true"></i></h4>
                    </div>
                    <div class="mt-4 px-15">
                        <div>
                            <ul class="text-grey">
                                <li>1. Log in BRI Mobile Banking (unduh versi terbaru)</li>
                                <li>2. Pilih menu “PEMBAYARAN”</li>
                                <li>3. Pilih “BRIVA”</li>
                                <li>4. Masukan nomor BRI Virtual Account Anda dan jumlah pembayaran</li>
                                <li>5. Masukan Nomor Pin Anda</li>
                                <li>6. Tekan “OK” untuk melanjutkan transaksi Anda</li>
                                <li>7. Transaksi berhasil</li>
                                <li>8. Konfirmasi sms akan masuk ke nomor telepon Anda</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="payment-instructions borbot-gray-0" data-bank="bri-mobile-banking">
                    <div class="p-15">
                        <h4 class="hd-18">BRI - Mobile Banking <i class="fa fa-caret-down ml-2" aria-hidden="true"></i></h4>
                    </div>
                    <div class="mt-4 px-15">
                        <div>
                            <ul class="text-grey">
                                <li>1. Log in BRI Mobile Banking (unduh versi terbaru)</li>
                                <li>2. Pilih menu “PEMBAYARAN”</li>
                                <li>3. Pilih “BRIVA”</li>
                                <li>4. Masukan nomor BRI Virtual Account Anda dan jumlah pembayaran</li>
                                <li>5. Masukan Nomor Pin Anda</li>
                                <li>6. Tekan “OK” untuk melanjutkan transaksi Anda</li>
                                <li>7. Transaksi berhasil</li>
                                <li>8. Konfirmasi sms akan masuk ke nomor telepon Anda</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
