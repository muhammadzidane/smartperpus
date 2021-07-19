<div class="col-md-3">
    <div class="white-content borbot-gray-bold">
        <div class="borbot-gray-0 pb-3">
            <div class="mt-1">
                <h4 class="hd-16">Status</h4>

                @can('viewAny', 'App\\Models\User')
                <div class="text-grey">
                    <div class="{{ $waiting_for_payment ?? '' }}">
                        <div class="d-flex w-maxc position-relative">
                            <a class="text-decoration-none text-grey" href="{{ route('uploaded.payments') }}">Upload-an bukti pembayaran</a>
                        </div>
                    </div>
                    <div class="{{ $on_process ?? '' }}">
                        <a class="text-decoration-none text-grey" href="{{ route('on.delivery') }}">Sedang diproses</a>
                    </div>
                    <div class="{{ $on_delivery ?? '' }}">
                        <a class="text-decoration-none text-grey" href="{{ route('on.delivery') }}">Sedang dikirim</a>
                    </div>
                    <div class="{{ $arrived ?? '' }}">
                        <a class="text-decoration-none text-grey" href="{{ route('on.delivery') }}">Telah sampai</a>
                    </div>
                </div>

                @else
                <div class="text-grey">
                    <div class="{{ $waiting_for_payment ?? '' }}">
                        <div class="d-flex w-maxc position-relative">
                            <a class="text-decoration-none text-grey" href=#">Menunggu pembayaran</a>
                        </div>
                    </div>
                    <div class="{{ $on_process ?? '' }}">
                        <a class="text-decoration-none text-grey" href="{{ route('on.delivery') }}">Sedang diproses</a>
                    </div>
                    <div class="{{ $on_delivery ?? '' }}">
                        <a class="text-decoration-none text-grey" href="{{ route('on.delivery') }}">Sedang dikirim</a>
                    </div>
                    <div class="{{ $arrived ?? '' }}">
                        <a class="text-decoration-none text-grey" href="{{ route('on.delivery') }}">Telah sampai</a>
                    </div>
                </div>
                @endcan

            </div>
        </div>
        <div class="mt-3">
            <div class="mt-1">
                <h4 class="hd-16">Kontak Masuk</h4>
                <div class="text-grey">
                    <div>
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
