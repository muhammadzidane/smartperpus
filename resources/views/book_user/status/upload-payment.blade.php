@extends('layouts.app')
@section('content')

<h4>Status Pembelian</h4>

<div class="row flex-row-reverse">
    @include('book_user.status.sidebar', array('waiting_for_payment' => 'active-acc'))
    <div class="col-md-9">
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

                            <div class="d-flex justify-content-between mt-auto pt-3">
                                <button class="see-billing-list btn-none p-0 mt-2 tred tred-bold" data-toggle="modal" data-id="{{ $book_user->id }}" data-target="#bill">Lihat Detail</button>
                                <div class="text-right">
                                    <button data-id="{{ $book_user->id }}" class="confirm-payment btn btn-red">Konfirmasi pembayaran</button>
                                    <button class="cancel-confirm-payment btn-none tred-bold">Batalkan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @empty
        @include('book_user.status.empty-values', array('text' => 'Tidak ada unggahan foto bukti pembayaran'))

        @endforelse
    </div>
</div>

@include('layouts.modal-custom',
array(
"modal_trigger_id" => "bill",
"modal_size_class" => "modal-lg",
"modal_header" => "Detail Tagihan",
"modal_content" => "bill"
)
)

@endsection
