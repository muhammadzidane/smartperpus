@extends('layouts.app')
@section('content')

@include('content-header', array(
'title' => 'Pembelian',
'icon_html' => '<i class="fas fa-shopping-bag user-icon mr-2 text-green f-20"></i>',
)
)

<div class="row flex-row-reverse mt-4">
    @include('profile-sidebar')
    <div class=" col-md-9">
        <div class="status-links">
            <a href="/status/all" class="status-link {{ request()->path() == 'status/all' ? 'status-link-active' : '' }}">
                <span>Semua</span>
            </a>
            <a href="/status/unpaid" class="status-link {{ request()->path() == 'status/unpaid' ? 'status-link-active' : '' }}">
                <span>Belum Dibayar</span>

                @if (isset($counts['waiting_for_confirmation']) && $counts['waiting_for_confirmation'] != 0)
                <span>({{ $counts['waiting_for_confirmation'] }})</span>
                @endif
            </a>
            <a href="/status/on-process" class="status-link {{ request()->path() == 'status/on-process' ? 'status-link-active' : '' }}">
                <span>Diproses</span>

                @if (isset($counts['order_in_process']) && $counts['order_in_process'] != 0)
                <span>({{ $counts['order_in_process'] }})</span>
                @endif
            </a>
            <a href="/status/on-delivery" class="status-link {{ request()->path() == 'status/on-delivery' ? 'status-link-active' : '' }}">
                <span>Dikirim</span>

                @if (isset($counts['being_shipped']) && $counts['being_shipped'] != 0)
                <span>({{ $counts['being_shipped'] }})</span>
                @endif
            </a>
            <a href="/status/completed" class="status-link {{ request()->path() == 'status/completed' ? 'status-link-active' : '' }}">
                <span>Selesai</span>

            </a>
            <a href="/status/failed" class="status-link {{ request()->path() == 'status/failed' ? 'status-link-active' : '' }}">
                <span>Dibatalkan</span>
            </a>
        </div>
        @if (auth()->user()->role != 'guest')
        <div class="mt-4">
            <a href="/status/uploaded-payment" class="status-link {{ request()->path() == 'status/uploaded-payment' ? 'status-link-active' : '' }}">
                <span>Bukti Pembayaran</span>
            </a>
        </div>
        @endif
        <div class="row mt-4">
            <div class="col-12">
                <form action="{{ url()->current() }}" method="get">
                    <div class="status-search">
                        <button class="status-search-icon btn-none p-0">
                            <i class="fa fa-search d-none d-md-block" aria-hidden="true"></i>
                        </button>
                        <input name="keywords" class="status-search-input" type="text" placeholder="Cari berdasarkan nama produk, nama author dan nomer pesanan">
                    </div>
                </form>

                @if (request()->keywords)
                <div class="text-grey mt-4">
                    <span>Hasil pencarian untuk</span>
                    <span class="tbold">"{{ request()->keywords }}"</span>
                    <span>. Menampilkan <span class="tbold">{{ $book_users->count() }}</span> hasil </span>
                </div>
                @endif
            </div>
        </div>

        @forelse ($book_users as $book_user)
        <div id="{{ $book_user['first']->invoice }}" class="status-invoice white-content m-0">
            <div class="borbot-gray-0 pb-3">
                <div class="d-flex justify-content-between text-grey tbold">
                    <div>
                        <i class="fas fa-shopping-bag mr-1"></i>
                        <span>No. Pesanan: {{ $book_user['first']->invoice }}</span>
                        @if (auth()->user()->role != 'guest')
                        <span>/ {{ $book_user['user_fullname'] }}</span>
                        @endif
                    </div>
                    <div class="text-right">
                        @if (request()->path() == 'status/unpaid')
                        <span>Bayar sebelum tanggal, {{ $book_user['first']->created_at->isoFormat('dddd, D MMMM Y H:mm:ss') }} WIB -</span>
                        @endif

                        @if (request()->path() == 'status/failed')
                        <span>{{ $book_user['first']->failed_date->isoFormat('dddd, D MMMM Y H:mm:ss') }} -</span>
                        @endif

                        @if (request()->path() == 'status/on-process')
                        <span>{{ $book_user['first']->payment_date->isoFormat('dddd, D MMMM Y H:mm:ss') }} -</span>
                        @endif

                        @if (request()->path() == 'status/on-delivery')
                        <span>{{ $book_user['first']->shipped_date->isoFormat('dddd, D MMMM Y H:mm:ss') }} -</span>
                        @endif

                        @if ((request()->path() == 'status/completed' || request()->path() == 'status/all') && $book_user['first']->payment_status == 'arrived')
                        <span>{{ $book_user['first']->completed_date->isoFormat('dddd, D MMMM Y HH:mm:ss') }} -</span>
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
                @if ($book_user['first']->failed_message != null)
                <div class="mb-3"><i class="fas fa-info-circle mr-1"></i>
                    <span class="text-grey">{{ $book_user['first']->failed_message }}</span>
                </div>
                @endif


                <div class="text-right mb-3">
                    @if (auth()->user()->role == 'guest')

                    @if ((request()->path() == 'status/unpaid' || request()->path() == 'status/all') && $book_user['first']->upload_payment_image == null)
                    <div>
                        <a href="{{ route('book.purchases.show', array('invoice' => $book_user['first']->invoice)) }}" class="btn btn-outline-danger">Unggah Bukti Pembayaran</a>
                    </div>
                    @endif

                    @if (request()->path() == 'status/unpaid' && $book_user['first']->upload_payment_image != null)
                    <div>
                        <span class="text-grey mr-1"><i class="fa fa-info-circle" aria-hidden="true"></i> Dicek dalam 24 Jam</span>
                        <button type="button" class="btn btn-outline-danger" disabled>Bukti Sedang Diproses</button>
                    </div>
                    @endif

                    @else
                    @if ((request()->path() == 'status/uploaded-payment' || request()->path() == 'status/all') && $book_user['first']->upload_payment_image != null && $book_user['first']->payment_status == 'waiting_for_confirmation')
                    <div>
                        <button class="zoom-modal-image btn btn-outline-red mr-1" data-src="{{ asset('storage/uploaded_payment/' . $book_user['first']->upload_payment_image) }}">Lihat Bukti</button>
                        <button class="status-confirm-payment btn btn-outline-red">Proses Bukti</button>
                        <button class="status-cancel-upload btn btn-outline-red">Batalkan</button>
                    </div>
                    @elseif ($book_user['first']->upload_payment_image != null && $book_user['first']->payment_status == 'order_in_process')
                    <div>
                        <button type="button" class="status-on-delivery btn btn-outline-danger">Kirim</button>
                    </div>
                    @endif
                    @endif

                    @if ((request()->path() == 'status/on-delivery' || request()->path() == 'status/all') && $book_user['first']->payment_status == 'being_shipped')
                    <div>
                        <button type="button" class="status-complete btn btn-outline-danger mr-2">Selesai</button>
                        <button type="button" class="tracking-packages btn btn-outline-danger" data-courier="{{ $book_user['first']->courier_name }}" data-resi="{{ $book_user['first']->resi_number }}">Informasi Pengiriman</button>
                    </div>
                    @endif
                </div>
                <div class="d-md-flex justify-content-between">
                    <div class="my-auto d-flex justify-content-between">
                        <span>Total Pembayaran :</span>
                        <h5 class="tred-bold d-inline ml-2">{{ rupiah_format($book_user['total_payment']) }}</h5>
                    </div>
                    <div>
                        <div class="ml-2 tred-bold my-auto">
                            <button class="status-detail btn-none tred-bold p-0 m-0" data-invoice="{{ $book_user['first']->invoice }}">Lihat Detail</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @empty
        <div class="mt-4">
            @include('book_user.status.empty-values', array('text' => 'Belum ada pesanan'))
        </div>
        @endforelse
    </div>
</div>

@endsection
