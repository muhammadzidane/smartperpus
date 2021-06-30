@extends('layouts.app')
@section('content')
<div class="row mt-md-4">
    <div class="col-lg-9">
        <div class="white-content-0">
            <div class="white-content-header-2 flex-column">
                <h4 class="hd-14 m-0">Menungggu Pembayaran <span class="tred">({{ $book_users->count() }})</span></h4>
                <div class="mt-2">
                    <div class="wishlist-search">
                        <span><i class="fa fa-search wishlist-search-icon" aria-hidden="true"></i></span>
                        <input class="text-righteous hd-12" type="search" placeholder="Cari pembayaran">
                    </div>
                </div>
            </div>
        </div>

        @foreach ($book_users as $book_users)
            <div class="white-content-0 mt-c">
                <div class="p-15 borbot-gray-bold">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span>Tanggal Pembelian - </span>
                            <span class="text-grey">{{ $book_users->created_at->isoFormat('dddd, D MMMM YYYY H:MM') }}</span>
                        </div>
                        <div>
                            <span class="text-grey">ABCD2K209128</span>
                            <button class="btn-none tred-bold">Batalkan pembelian</button>
                        </div>
                    </div>
                </div>
                <div class="p-4 borbot-gray-bold">
                    <div class="ml-1 borbot-gray-0">
                        <div class="d-flex">
                            <div class="img-monitor">
                                <img class="w-100" src="{{ asset('img/monitor.png') }}">
                            </div>
                            <div class="d-flex flex-column w-100">
                                <div class="pl-3">
                                    <div class="d-flex justify-content-between">
                                        <div class="text-righteous">
                                            <span>Total Belanja </span>
                                            <span class="tred-bold">(2)</span>
                                        </div>
                                        <div>
                                            <span>Bayar sebelum - </span>
                                            <span class="text-grey">{{ $book_users->payment_deadline->isoFormat('dddd, D MMMM YYYY H:MM') }} WIB</span>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <button class="d-block btn-none p-0 tred-bold">Lihat tagihan</button>
                                        <div>Total pembayaran : <span class="text-grey tbold">{{ rupiah_format($book_users->total_payment) }}</span></div>
                                        <div>Metode pembayaran : <span class="text-grey tbold">{{ $book_users->payment_method }}</span></div>
                                    </div>
                                    <div>
                                        <div class="text-right text-righteous">
                                            <a href="#" class="btn btn-sm-0 btn-red hd-14">Unggah bukti pembayaran</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
    @include('user.purchases-and-inboxes', array('waiting_for_payments' => 'active-acc'))
</div>

@endsection
