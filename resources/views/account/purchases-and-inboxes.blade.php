<div class="col-lg-3 h-maxc">
    <div class="white-content m-0 h-100 borbot-gray-bold">
        <div class="borbot-gray-0 pb-3">
            <div class="container mt-1">
                <h4 class="hd-16">Pembelian</h4>
                <div class="text-grey">
                    <div class="{{ $waiting_for_payments ?? '' }}">
                        <div class="d-flex w-maxc position-relative">
                            <a class="text-decoration-none text-grey" href=#">Menunggu pembayaran</a>
                            <div class="waiting-for-payment">6</div>
                        </div>
                    </div>
                    <div class="{{ $transaction_list ?? '' }}">
                        <a class="text-decoration-none text-grey" href="{{ route('transaction.lists') }}">Daftar transaksi</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <div class="container mt-1">
                <h4 class="hd-16">Kontak Masuk</h4>
                <div class="text-grey">
                    <div class="{{ $reviews ?? '' }}">Ulasan</div>
                    <div class="{{ $product_discutions ?? '' }}">Diskusi produk</div>
                </div>
            </div>
        </div>
    </div>
</div>
