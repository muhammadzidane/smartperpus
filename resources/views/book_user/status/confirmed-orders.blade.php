@extends('layouts.app')
@section('content')

@include('book_user.status.header', array('confirmed' => 'upload-active'))

@forelse ($book_users as $book_user)
<div class="uploaded-payment mt-c">
    <div class="white-content borbot-gray-bold">
        <div class="row">
            <div class="col-md-3 mb-5">
                <img class="zoom-modal-image w-100" src="{{ asset('storage/uploaded_payment/' . $book_user->upload_payment_image) }}">
            </div>
            <div class="col-md-9">
                <div class="d-flex justify-content-between flex-column h-100">

                    @include('book_user.status.main')

                    <div class="d-md-flex justify-content-between mt-auto pt-3">
                        <button class="see-billing-list btn-none p-0 mt-2 tred tred-bold" data-toggle="modal" data-id="{{ $book_user->id }}" data-target="#bill">Lihat Detail</button>
                        <div class="float-right">
                            <button data-id="{{ $book_user->id }}" class="confirm-shipping btn btn-red">Konfirmasi Pengiriman</button>
                            <button class="cancel-confirm-payment btn-none tred-bold">Batalkan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@empty
<div class="white-content-0 py-3">
    <div class="col-5 mx-auto mt-3">
        <h4 class="hd-18 mb-4 text-center">Tidak ada unggahan foto bukti pembayaran</h4>
        <img class="w-100" src="{{ asset('img/no-data.png') }}" alt="">
    </div>
</div>

@endforelse

@include('layouts.modal-custom',
array(
"modal_trigger_id" => "bill",
"modal_size_class" => "modal-lg",
"modal_header" => "Detail Tagihan",
"modal_content" => "bill"
)
)
@endsection
