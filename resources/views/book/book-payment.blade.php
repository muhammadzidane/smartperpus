@extends('layouts.app')
@section('content')

<div class="text-center">
    <h4>Lakukan pembayaran dalam</h4>
    <h1 id="payment-limit-time" class="text-righteous my-4"></h1>
    <div>Batas waktu pembayaran : <span id="payment-limit-date" data-id="{{ $datas[0]['book_user']->id }}" class="tbold"></span></div>
</div>

<div>
    <div class="white-content px-0 pt-0 pb-4 borbot-gray-bold mt-c w-75 mx-auto">
        <div class="white-content-header-2">
            @switch($datas[0]['book_user']->payment_method)
            @case('Transfer Bank BRI')
            <h4 class="hd-18">Transfer Bank BRI</h4>
            @break
            @case('Transfer Bank BNI')
            <h4 class="hd-18">Transfer Bank BNI</h4>
            @break
            @case('Transfer Bank BCA')
            <h4 class="hd-18">Transfer Bank BCA</h4>
            @break
            @endswitch
        </div>
        <div class="mt-4">
            <div class="container text-center borbot-gray-0 pb-4">
                <h4 class="hd-18">Transfer pembayaran pada nomer rekening berikut :</h4>
                <div class="mt-4">
                    <div>
                        <div class="d-flex">
                            <div>
                                @switch($datas[0]['book_user']->payment_method)
                                @case('Transfer Bank BRI')
                                <img src="{{ asset('img/transfer/bri.png') }}" class="w-20">
                                @break
                                @case('Transfer Bank BNI')
                                <img src="{{ asset('img/transfer/bni.png') }}" class="w-20">
                                @break
                                @case('Transfer Bank BCA')
                                <img src="{{ asset('img/transfer/bca.png') }}" class="w-20">
                                @break
                                @endswitch

                                <h4 class="d-inline align-middle tred ml-3 f-28">1903847182123</h4>
                            </div>
                        </div>
                        <h4 class="mt-4 hd-14">Atas nama <span class="text-grey">Muhammad Zidane Alsaadawi</span></h4>
                    </div>
                </div>
            </div>
            <div class="d-flex mt-4 px-4">
                <div class="w-100">
                    <div id="accordion">
                        <div>
                            <div id="headingOne" class="d-flex justify-content-between border py-2 px-2">
                                <button class="btn-none collapsed p-0 w-100 text-left" data-toggle="collapse" data-target="#detailCollapse" aria-expanded="false" aria-controls="detailCollapse">
                                    Lihat Detail
                                </button>
                                <i class="fa fa-caret-down" aria-hidden="true"></i>
                            </div>
                            <div id="detailCollapse" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="mt-3">
                                    <div>
                                        <div class="tbold">Kurir</div>
                                        <div class="text-grey">{{ $courier_name }}</div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="tbold">Layanan Kurir</div>
                                        <div class="text-grey">{{ $datas[0]['book_user']->courier_service }}</div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="tbold">Ongkos Kirim</div>
                                        <div class="text-grey">+{{ rupiah_format($datas[0]['book_user']->shipping_cost) }}</div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="tbold">Kode Unik</div>
                                        <div class="text-grey">+{{ rupiah_format($datas[0]['book_user']->unique_code) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5>Total Pembayaran</h5>
                                <div class="tred-bold hd-18">
                                    <span>{{ $total_payment }}</span>
                                    <span>{{ $total_payment_sub_last }}</span>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="my-auto mr-2">Contoh Foto Transfer</div>
                                <div>
                                    <img class="zoom-modal-image transfer-sample" src="{{ asset('img/sample-transfer.jpg') }}" alt="Contoh Foto Transfer">
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">Pastikan nominal transfer <span class="tbold">TEPAT BERJUMLAH ANGKA DI ATAS (Hingga 3 angka terakhir)</span></div>
                    </div>
                    <div class="mt-4 text-grey"><i class="fa fa-info-circle" aria-hidden="true"></i> Unggah bukti pembayaran agar proses cepat dilakukan</div>
                    <div class="mt-3 row">
                        <div class="col-6">
                            <button class="upload-payment-button btn btn-outline-danger w-100">Upload Bukti Pembayaran</button>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('status.waiting.for.payment') }}#{{ $datas[0]['book_user']->invoice }}" class="btn btn-outline-danger w-100">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-content px-0 pt-0 pb-4 borbot-gray-bold mt-c w-75 mx-auto">
    <div class="white-content-header-2">
        <h4 class="hd-18">Petunjuk Pembayaran</h4>
    </div>
    @switch($datas[0]['book_user']->payment_method)
    @case('Transfer Bank BRI')
    <div class="c-p">
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

    <div class="p-15">
        <span>
            <i class="fas fa-info-circle" aria-hidden="true"></i>
        </span>
        <span class="tred-bold">Pembayaran dari Bank lain ke Bank BRI, Dikenakan biaya transaksi antarbank (Rp 5.000 – Rp 8.000)</span>
    </div>
    @break
    @case('Transfer Bank BNI')
    <div class="c-p">
        <div class="payment-instructions borbot-gray-0" data-bank="bri-atm">
            <div class="p-15">
                <h4 class="hd-18">BNI - ATM <i class="payment-instructions-caret fa fa-caret-right ml-2" aria-hidden="true"></i></h4>
            </div>
            <div class="mt-4 px-15">
                <div>
                    <ul class="text-grey">
                        <li>1. Pilih “MENU LAIN”</li>
                        <li>2. Pilih “TRANSFER”</li>
                        <li>3. Pilih “TRANSAKSI LAINNYA”</li>
                        <li>4. Pilih ke “REKENING BNI”</li>
                        <li>5. Masukkan Nomor Account Virtual, lalu tekan “BENAR”</li>
                        <li>6. Masukkan NOMINAL, lalu tekan “YA”</li>
                        <li>7. Konfirmasi transaksi selesai, tekan “TIDAK” untuk menyelesaikan transaksi</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="payment-instructions borbot-gray-0" data-bank="bri-internet-banking">
            <div class="p-15">
                <h4 class="hd-18">BNI - Internet Banking <i class="payment-instructions-caret fa fa-caret-right ml-2" aria-hidden="true"></i></h4>
            </div>
            <div class="mt-4 px-15">
                <div>
                    <ul class="text-grey">
                        <li>1. Pilih “TRANSAKSI”</li>
                        <li>2. Pilih “INFO & ADMINISTRASI TRANSFER”</li>
                        <li>3. Pilih “ATUR REKENING TUJUAN”</li>
                        <li>4. Tambahkan rekening tujuan Kemudian klik “OK”</li>
                        <li>5. Isi data rekening dan tekan “LANJUTKAN”</li>
                        <li>6. Rincian konfirmasi akan muncul, jika benar dan sesuai,
                            masukkan 8-digit angka yang dihasilkan dari APPLI 2 pada token BNI Anda lalu klik “PROSES”</li>
                        <li>7. Akun tujuan berhasil ditambahkan</li>
                        <li>8. Pilih “TRANSFER”</li>
                        <li>8. Pilih “TRANSFER KE REKENING BNI”, lalu lengkapi semua data rekening penerima, lalu klik “LANJUTKAN”</li>
                        <li>8. Transaksi Anda telah berhasil</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="payment-instructions borbot-gray-0" data-bank="bri-mobile-banking">
            <div class="p-15">
                <h4 class="hd-18">BNI - Mobile Banking <i class="fa fa-caret-down ml-2" aria-hidden="true"></i></h4>
            </div>
            <div class="mt-4 px-15">
                <div>
                    <ul class="text-grey">
                        <li>1. Log in BNI Mobile Banking (unduh versi terbaru)</li>
                        <li>2. Pilih menu “TRANSFER”</li>
                        <li>3. Pilih “SESAMA BANK BNI”</li>
                        <li>4. Isi kolom “REKENING DEBET” lalu klik menu “KE REKENING</li>
                        <li>5. Isi kolom “REKENING DEBET” lalu klik menu “KE REKENING</li>
                        <li>6. Lengkapi data dengan mengisi, NAMA, NO VIRTUAL ACCOUNT DAN ALAMAT EMAIL BENEFICIARY</li>
                        <li>7. Konfirmasi akan muncul kemudian klik “LANJUTKAN”</li>
                        <li>8. Isi semua form yang ada lalu klik “LANJUTKAN”</li>
                        <li>9. Rincian konfirmasi muncul dengan meminta password transaksi, setelah selesai klik “LANJUTKAN”</li>
                        <li>10. Transaksi Anda telah berhasil</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="p-15">
        <span>
            <i class="fas fa-info-circle" aria-hidden="true"></i>
        </span>
        <span class="tred-bold">Pembayaran dari Bank lain ke Bank BNI, Dikenakan biaya transaksi antarbank (Rp 5.000 – Rp 8.000)</span>
    </div>
    @break
    @case('Transfer Bank BCA')
    <div class="c-p">
        <div class="payment-instructions borbot-gray-0" data-bank="bri-atm">
            <div class="p-15">
                <h4 class="hd-18">BCA - ATM<i class="payment-instructions-caret fa fa-caret-right ml-2" aria-hidden="true"></i></h4>
            </div>
            <div class="mt-4 px-15">
                <div>
                    <ul class="text-grey">
                        <li>1. Masukan kartu ATM BCA & PIN</li>
                        <li>2. Pilih “Transaksi Lainnya”</li>
                        <li>3. Pilih “Transfer”</li>
                        <li>4. Pilih “Rekening BCA Virtual Account”</li>
                        <li>5. Masukan nomor BCA Virtual Account (Contoh : 39539xxx)</li>
                        <li>6. Masukan jumlah yang ingin dibayarkan</li>
                        <li>7. Konfirmasi transaksi selesai, tekan “TIDAK” untuk menyelesaikan transaksi</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="payment-instructions borbot-gray-0" data-bank="bri-internet-banking">
            <div class="p-15">
                <h4 class="hd-18">BCA - Internet Banking / Click BCA <i class="payment-instructions-caret fa fa-caret-right ml-2" aria-hidden="true"></i></h4>
            </div>
            <div class="mt-4 px-15">
                <div>
                    <ul class="text-grey">
                        <li>1. Lakukan Log in pada aplikasi KlikBCA”</li>
                        <li>2. Masukan user ID dan PIN</li>
                        <li>3. Pilih “Transfer Dana” </li>
                        <li>4. Pilih “Transfer ke BCA Virtual Account”</li>
                        <li>5. Masukan nomor BCA Virtual Account (Contoh : 39539xxx) atau pilih dari Daftar Transfer</li>
                        <li>6. Masukan jumlah yang ingin dibayarkan</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="payment-instructions borbot-gray-0" data-bank="bri-mobile-banking">
            <div class="p-15">
                <h4 class="hd-18">BCA - Mobile Banking <i class="fa fa-caret-down ml-2" aria-hidden="true"></i></h4>
            </div>
            <div class="mt-4 px-15">
                <div>
                    <ul class="text-grey">
                        <li>1. Lakukan Log in pada aplikasi BCA Mobile</li>
                        <li>2. Pilih “m-BCA” Masukan kode akses m-BCA</li>
                        <li>3. Pilih “m-Transfer”</li>
                        <li>4. Pilih “BCA Virtual Account”</li>
                        <li>5. Pilih “BCA Virtual Account”</li>
                        <li>6. Masukan jumlah yang ingin dibayarkan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="p-15">
        <span>
            <i class="fas fa-info-circle" aria-hidden="true"></i>
        </span>
        <span class="tred-bold">Pembayaran dari Bank lain ke Bank BCA, Dikenakan biaya transaksi antarbank (Rp 5.000 – Rp 8.000)</span>
    </div>

    @break
    @endswitch
</div>
</div>
@endsection
