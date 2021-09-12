@extends('layouts.app')
@section('content')

<h4>Menunggu Pembayaran</h4>

<div class="row flex-row-reverse mt-4">
    @include('book_user.status.sidebar', array('waiting_for_payment' => 'active-acc'))
    <div class="col-md-9">
        @forelse ($book_users as $book_user)
        <div id="{{ $book_user['first']->invoice }}" class="status-invoice white-content m-0">
            <div class="borbot-gray-0 pb-3">
                <div class="d-flex justify-content-between text-grey tbold">
                    <div>{{ $book_user['first']->invoice }}</div>
                    <div>
                        <span>Bayar sebelum tanggal, {{ $book_user['first']->created_at->isoFormat('dddd, D MMMM Y H:mm:s') }}WIB </span>-
                        <span class="tred">BELUM DI BAYAR</span>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                @foreach ($book_user['books'] as $book)
                <div class="row d-flex borbot-gray-0 pb-3 mt-4">
                    <div class="col-2">
                        <img class="zoom-modal-image book-status-image" src="{{ asset('storage/books/' .$book->image ) }}">
                    </div>
                    <div class="col-10">
                        <div class="h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div>{{ $book->name }}</div>
                                <div class="mt-2 text-grey">{{ $book->author->name }}</div>
                            </div>
                            <div class="row flex-row">
                                <div class="col-4">
                                    <div class="tred-bold">{{ $book->pivot->book_version == 'hard_cover' ? 'Buku Cetak' : 'E-Book' }}</div>
                                    <div class="text-grey">
                                        <span>{{ $book->pivot->amount }} x</span>
                                        <span>{{ rupiah_format($book->price - $book->discount) }}</span>
                                    </div>
                                    <div class="text-grey tbold">{{ rupiah_format(($book->price - $book->discount) * $book->pivot->amount) }}</div>
                                </div>
                                <div class="col-8">
                                    <div class="d-flex">
                                        <div>Catatan:</div>
                                        <div class="ml-2">
                                            <div>{{ $book->pivot->note ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-3">
                <div class="d-flex justify-content-between">
                    <div class="my-auto">
                        <span>Total Pembayaran :</span>
                        <h5 class="tred-bold d-inline ml-2">{{ rupiah_format($book_user['total_payment']) }}</h4>
                    </div>
                    <div class="d-flex">
                        <div>
                            <a href="{{ route('book.purchases.show', array('invoice' => $book_user['first']->invoice)) }}" class="btn btn-outline-danger">Unggah Bukti Pembayaran</a>
                        </div>
                        <div class="ml-2 my-auto tred-bold">
                            <button class="see-billing-list btn btn-none tred-bold" data-invoice="{{ $book_user['first']->invoice }}">Lihat Detail</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @empty
        @include('book_user.status.empty-values', array('text' => 'Belum ada pesanan'))
        @endforelse
    </div>
</div>

@endsection
