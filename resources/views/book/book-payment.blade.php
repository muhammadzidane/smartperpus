@extends('layouts.app')
@section('content')

    <div class="text-center">
        <h4>Lakukan pembayaran dalam</h4>
        <h1 id="payment-limit-time" class="text-righteous my-4"></h1>
        <div>Batas waktu pembayaran : <span id="payment-limit-date" data-id="{{ $bookUser->id }}" class="tbold"></span></div>
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
                                    @switch($bookUser->payment_method)
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
                <div class="d-flex justify-content-between p-15 ml-4">
                    <div>
                        <h4 class="hd-18">Total Pembayaran</h4>
                        <div class="hd-18 tred-bold">{{ rupiah_format($bookUser->total_payment) }}</div>
                        <div class="mt-4 text-grey"><i class="fa fa-info-circle" aria-hidden="true"></i> Unggah bukti pembayaran agar proses cepat dilakukan</div>
                    </div>
                    <div class="ml-4">
                        <button class="btn-none tred tred-bold" data-toggle="modal"
                          data-target="#modal-payment-detail">Lihat Detail</button>

                        <!-- Modal lihat detail pembayaran -->
                        <div class="modal fade" id="modal-payment-detail" tabindex="-1" role="dialog"
                          aria-labelledby="modelTitleId" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered p-5" role="document">
                                <div class="modal-content modal-content-login">
                                    <div class="px-3 mb-4 d-flex justify-content-between">
                                        <h5 class="modal-title tred login-header">Detail Pembayaran</h5>
                                        <button type="button" class="close c-p" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row borbot-gray-0 pb-2 px-3">
                                            <div class="col-3">
                                                <img class="w-100"
                                                  src="{{ asset('storage/books/' . App\Models\Book::find($bookUser->book_id)->image) }}">
                                            </div>
                                                <div class="col-9">
                                                    <div>
                                                        <h4 class="hd-14">{{ App\Models\Book::find($bookUser->book_id)->name }} </h4>
                                                        <h4 class="hd-14 tred">( Buku Cetak )</h4>
                                                    </div>
                                                    <div class="text-grey">
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <div>Jumlah barang</div>
                                                            <div>{{ $bookUser->amount }}</div>
                                                        </div>
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <div>Harga barang</div>
                                                            <div>{{ rupiah_format(App\Models\Book::find($bookUser->book_id)->price) }}</div>
                                                        </div>
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <div>Kurir</div>
                                                            <div>{{ $bookUser->courier_name }}</div>
                                                        </div>
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <div>Layanan kurir</div>
                                                            <div>{{ $bookUser->courier_service }}</div>
                                                        </div>
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <div>Ongkos Kirim</div>
                                                            <div>Rp7.000</div>
                                                        </div>
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <div>Layanan pembayaran</div>
                                                            <div>{{ $bookUser->payment_method }}</div>
                                                        </div>
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <div>Ongkos Kirim</div>
                                                            <div>{{ rupiah_format($bookUser->shipping_cost) }}</div>
                                                        </div>
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <div>Kode unik</div>
                                                            <div>{{ rupiah_format($bookUser->unique_code) }}</div>
                                                        </div>
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <div>Total Pembayaran</div>
                                                            <div class="tred-bold">{{ rupiah_format($bookUser->total_payment) }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
            @switch($bookUser->payment_method)
                @case('Transfer Bank BRI')
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

                <div class="p-15">
                    <span>
                        <i class="fas fa-info-circle" aria-hidden="true"></i>
                    </span>
                    <span class="tred-bold">Pembayaran dari Bank lain ke Bank BRI, Dikenakan biaya transaksi antarbank (Rp 5.000 – Rp 8.000)</span>
                </div>
                @break
                @case('Transfer Bank BNI')
                <div class="my-4 c-p">
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
                <div class="my-4 c-p">
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
                            <h4 class="hd-18">BNI - Internet Banking / Click BCA <i class="payment-instructions-caret fa fa-caret-right ml-2" aria-hidden="true"></i></h4>
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
                            <h4 class="hd-18">BNI - Mobile Banking <i class="fa fa-caret-down ml-2" aria-hidden="true"></i></h4>
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
