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

        @foreach ($book_users as $book_user)
        <div class="white-content-0 mt-c">
            <div class="p-15 borbot-gray-bold">
                <div class="d-flex justify-content-between">
                    <div>
                        <span>Tanggal Pembelian - </span>
                        <span class="text-grey">{{ $book_user->created_at->isoFormat('dddd, D MMMM YYYY H:mm') }}</span>
                    </div>
                    <div>
                        <span class="text-grey">{{ $book_user->invoice }}</span>
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
                                        <span class="tred-bold">(1)</span>
                                    </div>
                                    <div>
                                        <span>Bayar sebelum - </span>
                                        <span class="text-grey">{{ $book_user->payment_deadline->isoFormat('dddd, D MMMM YYYY H:mm') }} WIB</span>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button class="d-block btn-none p-0 tred-bold">Lihat tagihan</button>
                                    <div>Total pembayaran : <span class="text-grey tbold">{{ rupiah_format($book_user->total_payment) }}</span></div>
                                    <div>Metode pembayaran : <span class="text-grey tbold">{{ $book_user->payment_method }}</span></div>
                                </div>
                                <div>
                                    <div class="text-right text-righteous">
                                        <a href="#" class="btn btn-sm-0 btn-red hd-14" data-toggle="modal" data-target="#exampleModalCenter" data-id="{{ $book_user->id }}">Unggah bukti pembayaran</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        @include('layouts.modal-custom',
        array(
        "modal_trigger_id" => "exampleModalCenter",
        "modal_size_class" => "modal-md",
        "modal_header" => "Unggah bukti pembayaran",
        "modal_content" => "upload_payment"
        )
        )
    </div>
    @include('user.purchases-and-inboxes', array('waiting_for_payments' => 'active-acc'))
</div>

@endsection
