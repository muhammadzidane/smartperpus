@extends('layouts.app')
@section('content')

<h4>Selesai</h4>

<div class="row flex-row-reverse mt-4">
    @include('book_user.status.sidebar', array('arrived' => 'active-acc'))
    <div class="col-sm-9">
        @forelse ($book_users as $book_user)
        <div class="uploaded-payment upload-payment-value">
            <div class="white-content m-0 borbot-gray-bold">
                <div class="row">
                    <div class="col-md-3 mb-5">
                        @can('viewAny', 'App\Models\User')
                        <img class="zoom-modal-image book-status-image" src="{{ asset('storage/uploaded_payment/' . $book_user->upload_payment_image) }}">

                        @else
                        <img class="w-100" src="{{ asset('img/checklist.png') }}">
                        @endcan
                    </div>
                    <div class="col-md-9">
                        <div class="d-flex justify-content-between flex-column h-100">

                            @include('book_user.status.main')

                            <div class="d-flex justify-content-between mt-auto pt-3">
                                <button class="see-billing-list btn-none p-0 mt-2 tred tred-bold" data-toggle="modal" data-id="{{ $book_user->id }}" data-target="#bill">Lihat Detail</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @empty
            @include('book_user.status.empty-values', array('text' => 'Tidak ada pesanan yang sedang dikirim'))
            @endforelse
        </div>
        <div class="d-flex justify-content-end mt-4">{{ $book_users->links() }}</div>
    </div>

    @include('layouts.modal-custom',
    array(
    "modal_trigger_id" => "bill",
    "modal_size_class" => "modal-lg",
    "modal_header" => "Detail Tagihan",
    "modal_content" => "bill"
    )
    )
</div>

@endsection
