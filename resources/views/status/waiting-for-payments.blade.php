@extends('layouts.app')
@section('content')

<h4>Status Pembayaran</h4>

<div class="row flex-row-reverse mt-4">
    @include('book_user.status.sidebar', array('margin_zero' => 'm-0'))
    <div class="col-md-9">
        @foreach ($book_users as $book_user)
        <div class="upload-payment-value white-content-0">
            <div class="p-15 borbot-gray-bold">
                <div class="d-flex justify-content-between">
                    <div>
                        <span>Tanggal Pembelian - </span>
                        <span class="text-grey">{{ $book_user->created_at->isoFormat('dddd, D MMMM YYYY H:mm') }}</span>
                    </div>
                    <div>
                        <span class="text-grey">{{ $book_user->invoice }}</span>
                        <button class="upload-payment-failed btn-none tred-bold" data-id="{{ $book_user->id }}">Batalkan pembelian</button>
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
                                        <span class="tred-bold">({{ $book_user->amount }})</span>
                                    </div>
                                    <div>
                                        <span>Bayar sebelum - </span>
                                        <span class="text-grey">{{ $book_user->payment_deadline->isoFormat('dddd, D MMMM YYYY H:mm') }} WIB</span>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button class="see-billing-list d-block btn-none p-0 tred-bold" data-toggle="modal" data-target="#bill" data-id="{{ $book_user->id }}">Lihat tagihan</button>
                                    <div>Metode pembayaran : <span class="text-grey tbold">{{ $book_user->payment_method }}</span></div>
                                    <div>Nama kurir : <span class="text-grey tbold">{{ $book_user->courier_name }}</span></div>
                                    <div>Layanan kurir : <span class="text-grey tbold">{{ $book_user->courier_service }}</span></div>
                                    <div>Ongkos kirim : <span class="text-grey tbold">{{ rupiah_format($book_user->shipping_cost )}}</span></div>
                                    <div>Total pembayaran : <span class="text-grey tbold">{{ rupiah_format($book_user->total_payment) }}</span></div>
                                </div>
                                <div>
                                    <div class="text-right text-righteous">
                                        <button id="upload-payment-button" class="btn btn-sm-0 btn-red hd-14" data-toggle="modal" data-target="#upload_payment" data-id="{{ $book_user->id }}">Unggah bukti pembayaran</button>
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
        "modal_trigger_id" => "upload_payment",
        "modal_size_class" => "modal-md",
        "modal_header" => "Unggah bukti pembayaran",
        "modal_content" => "upload_payment"
        )
        )

        @include('layouts.modal-custom',
        array(
        "modal_trigger_id" => "bill",
        "modal_size_class" => "modal-lg",
        "modal_header" => "Tagihan Anda",
        "modal_content" => "bill"
        )
        )
    </div>
</div>

@endsection
