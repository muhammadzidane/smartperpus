@extends('layouts.app')
@section('content')

<h4>{{ $status_title }}</h4>

<div class="row flex-row-reverse mt-4">
    <div class="col-md-3 mb-4">
        <div class="white-content m-0 borbot-gray-bold {{ $margin_zero ?? '' }}">
            <div class="borbot-gray-0 pb-3">
                <div class="mt-1">
                    <h4 class="hd-16">Status</h4>

                    <div class="text-grey">
                        <div class="py-1 position-relative {{ request()->path() == 'status/all' ? 'active-acc' : '' }}">
                            <a class="text-decoration-none text-grey" href="{{ route('status.all') }}">Semua</a>
                        </div>
                        <div class="py-1 position-relative {{ request()->path() == 'status/failed' ? 'active-acc' : '' }}">
                            <a class="text-decoration-none text-grey" href="{{ route('status.failed') }}">Di Batalkan</a>
                        </div>
                        <div class="py-1 position-relative {{ request()->path() == 'status/unpaid' ? 'active-acc' : '' }}">
                            <a class="text-decoration-none text-grey" href="{{ route('status.unpaid') }}">Belum Dibayar</a>
                            @isset($counts['waiting_for_payment'])
                            @if ($counts['waiting_for_payment'] != 0)
                            <span class="status-circle">{{ $counts['waiting_for_payment'] }}</span>
                            @endif
                            @endisset
                        </div>
                        @if (auth()->user()->role != 'guest')
                        <div class="py-1 position-relative {{ request()->path() == 'status/uploaded-payment' ? 'active-acc' : '' }}">
                            <a class="text-decoration-none text-grey" href="{{ route('status.uploaded.payment') }}">Unggahan Bukti Pembayaran</a>
                        </div>
                        @endif
                        <div class="py-1 position-relative {{ request()->path() == 'status/on-process' ? 'active-acc' : '' }}">
                            <a class="text-decoration-none text-grey" href="{{ route('status.on.process') }}">Sedang Diproses</a>
                            @isset($counts['on_process'])
                            @if ($counts['on_process'] != 0)
                            <span class="status-circle">{{ $counts['on_process'] }}</span>
                            @endif
                            @endisset
                        </div>
                        <div class="py-1 position-relative {{ request()->path() == 'status/on-delivery' ? 'active-acc' : '' }}">
                            <a class="text-decoration-none text-grey" href="{{ route('status.on.delivery') }}">Sedang Dikirim</a>
                            @isset($counts['on_delivery'])
                            @if ($counts['on_delivery'] != 0)
                            <span class="status-circle">{{ $counts['on_delivery'] }}</span>
                            @endif
                            @endisset
                        </div>
                        <div class="py-1 position-relative {{ request()->path() == 'status/completed' ? 'active-acc' : '' }}">
                            <a class="text-decoration-none text-grey" href="{{ route('status.completed') }}">Selesai</a>
                            @isset($counts['arrived'])
                            @if ($counts['arrived'] != 0)
                            <span class="status-circle">{{ $counts['arrived'] }}</span>
                            @endif
                            @endisset
                        </div>
                    </div>
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
    <div class="col-md-9">
        @forelse ($book_users as $book_user)
        <div id="{{ $book_user['first']->invoice }}" class="status-invoice white-content m-0">
            <div class="borbot-gray-0 pb-3">
                <div class="d-flex justify-content-between text-grey tbold">
                    <div>
                        <span>{{ $book_user['first']->invoice }}</span>
                        @if (auth()->user()->role != 'guest')
                        <span>/ {{ $book_user['user_fullname'] }}</span>
                        @endif
                    </div>
                    <div class="text-right">
                        @if (request()->path() == 'status/unpaid')
                        <span>Bayar sebelum tanggal, {{ $book_user['first']->created_at->isoFormat('dddd, D MMMM Y H:mm:ss') }} WIB -</span>
                        @endif
                        <span class="tred">{{ $book_user['status'] }}</span>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                @foreach ($book_user['books'] as $book)
                <div class="row d-flex borbot-gray-0 pb-3 mt-4">
                    <div class="col-12 col-md-2 mb-2 mb-md-2">
                        <img class="zoom-modal-image book-status-image" src="{{ asset('storage/books/' . $book->image ) }}">
                    </div>
                    <div class="col-md-10">
                        <div class="h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div>{{ $book->name }}</div>
                                <a href="{{ route('authors.show', array('author' => $book->author->id)) }}" class="mt-2 text-grey">{{ $book->author->name }}</a>
                            </div>
                            <div class="row flex-row">
                                @if (auth()->user()->role == 'guest')
                                <div class="col-sm-6 col-lg-5">
                                    <div class="tred-bold">{{ $book->pivot->book_version == 'hard_cover' ? 'Buku Cetak' : 'E-Book' }}</div>
                                    <div class="text-grey">
                                        <span>{{ $book->pivot->amount }} x</span>
                                        <span>{{ rupiah_format($book->price - $book->discount) }}</span>
                                    </div>
                                    <div class="text-grey tbold">{{ rupiah_format(($book->price - $book->discount) * $book->pivot->amount) }}</div>
                                </div>
                                <div class="col-sm-6 col-lg-7 mt-2 mt-sm-0">
                                    <div class="d-flex">
                                        <div><i class="fas fa-pencil-alt mr-1"></i> Catatan:</div>
                                        <div class="ml-2">
                                            <div>{{ $book->pivot->note ?? $book->users->first()->note ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>

                                @else
                                <div class="col-sm-6 col-lg-5">
                                    <div class="tred-bold">{{ $book->book_version == 'hard_cover' ? 'Buku Cetak' : 'E-Book' }}</div>
                                    <div class="text-grey">
                                        <span>{{ $book->amount }} x</span>
                                        <span>{{ rupiah_format($book->price - $book->discount) }}</span>
                                    </div>
                                    <div class="text-grey tbold">{{ rupiah_format(($book->price - $book->discount) * $book->amount) }}</div>
                                </div>
                                <div class="col-sm-6 col-lg-7 mt-2 mt-sm-0">
                                    <div class="d-flex">
                                        <div class="mr-2">
                                            <span>
                                                <i class="fas fa-pencil-alt mr-1"></i> Catatan:
                                            </span>
                                        </div>
                                        <div>
                                            <div class="">{{ $book->note ?? $book->users->first()->note ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-3">
                <div class="d-md-flex justify-content-between">
                    <div class="my-auto d-flex justify-content-between">
                        <span>Total Pembayaran :</span>
                        <h5 class="tred-bold d-inline ml-2">{{ rupiah_format($book_user['total_payment']) }}</h4>
                    </div>
                    <div class="d-flex justify-content-between ">
                        <div>
                            @if (request()->path() == 'status/unpaid' && auth()->user()->role == 'guest')
                            @if ($book_user['first']->upload_payment_image == null)
                            <div>
                                <a href="{{ route('book.purchases.show', array('invoice' => $book_user['first']->invoice)) }}" class="btn btn-outline-danger">Unggah Bukti Pembayaran</a>
                            </div>

                            @else
                            <div>
                                <span class="text-grey mr-1"><i class="fa fa-info-circle" aria-hidden="true"></i> Dicek dalam 24 Jam</span>
                                <button type="button" class="btn btn-outline-danger" disabled>Bukti Sedang Diproses</button>
                            </div>
                            @endif
                            @endif

                            @if ((request()->path() == 'status/uploaded-payment' || request()->path() == 'status/all') && auth()->user()->role != 'guest' && $book_user['first']->upload_payment_image != null && $book_user['first']->payment_status == 'waiting_for_confirmation')
                            <div>
                                <button class="zoom-modal-image btn btn-outline-red mr-1" data-src="{{ asset('storage/uploaded_payment/' . $book_user['first']->upload_payment_image) }}">Lihat Bukti</button>
                                <button class="confirm-payment btn btn-outline-red">Proses Bukti</button>
                                <button class="cancel-upload-image btn-none tred-bold">Batalkan</button>
                            </div>
                            @endif
                        </div>

                        <div class="ml-2 tred-bold my-auto">
                            <button class="status-detail btn-none tred-bold p-0 m-0" data-invoice="{{ $book_user['first']->invoice }}">Lihat Detail</button>
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
