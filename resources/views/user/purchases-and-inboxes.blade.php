<div class="col-lg-3 h-maxc purchases-and-inboxes">
    <div class="white-content m-0 h-100 borbot-gray-bold">
        <div class="borbot-gray-0 pb-3">
            <div class="container mt-1">
                <h4 class="hd-16">Pembelian</h4>
                <div class="text-grey">
                    <div class="{{ $waiting_for_confirmations ?? '' }}">
                        <div class="d-flex w-maxc position-relative">
                            <a class="text-decoration-none text-grey" href=#">Menunggu pembayaran</a>
                            <div class="waiting-for-payment">1</div>
                        </div>
                    </div>
                    <div class="">
                        <a class="text-decoration-none text-grey" href="#">Daftar transaksi</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <div class="container mt-1">
                <h4 class="hd-16">Kontak Masuk</h4>
                <div class="text-grey">
                    <div class="{{ $reviews ?? '' }}">
                        <a class="text-decoration-none text-grey" href="#">Ulasan</a>
                    </div>
                    <div class="{{ $product_discutions ?? '' }}">
                        <a class="text-decoration-none text-grey" href="#" class=" $product_discutions ?? ''  }}">Chat</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
