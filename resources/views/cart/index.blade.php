@extends('layouts.app')
@section('content')

@include('content-header',
    array(
        'title' => 'Keranjang Saya',
        'icon_html' => '<i class="fas fa-shopping-basket mr-2 text-green f-20"></i>',
    )
)

<form id="cart-checkout" action="{{ route('checkout') }}" method="POST">
    <div class="row flex-md-row-reverse mt-4">
        <div class="col-md-3 mb-4">
            <div class="white-content pt-0 m-0">
                <div id="book-payment" class="text-grey py-2 pb-3 mb-0 borbot-gray">
                    <div class="mt-2 mb-4">
                        <h4 class="hd-16 text-center text-body">Ringkasan Pembayaran</h4>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Jumlah Barang</div>
                        <input id="cart-amounts" type="text" value="{{ session('amount') ? session('amount') : ($amount ? $amount : 0) }}" class="input-none text-right w-25" readonly>
                    </div>
                </div>
                <div class="mt-2 text-grey">
                    <div class="d-flex justify-content-between">
                        <div>Total Pembayaran</div>
                        <input id="cart-total-payment" type="text" value="Rp{{ session('total_payment') ?? $total_payment }}" class="input-none text-right tred-bold w-50" readonly>
                    </div>
                    <div class="mt-3">
                        <button type="submit" id="payment-button" class="btn btn-red w-100">
                            <i class="fas fa-shield-alt mr-2"></i>Checkout
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="white-content px-0 pt-0 m-0 borbot-yellow-bold">
                <div class="p-15 pb-0 d-flex">
                    <input type="checkbox" class="mt-1 mr-2" id="checked-all">
                    <label for="checked-all" class="tbold m-0">Pilih Semua</label>
                    <span class="ml-1 tred-bold">({{ $books->count() }})</span>
                </div>
            </div>

            @forelse ($books as $book)
                <div class="white-content mt-4 px-0 pt-0 pb-4 m-0 borbot-gray-bold">
                    <div class="white-content-header-2">
                        <div class="d-flex justify-content-between w-100">
                            <div class="d-flex">
                                <label>
                                    <input type="checkbox" class="cart-check mr-2" name="carts[]" value="{{ $book->id ?? session('bought_directly') }}" {{ session('bought_directly') == $book->id || in_array($book->id, $book->buy_again ?? array()) ? 'checked' : '' }}>
                                    <span>Pilih</span>
                                </label>
                            </div>
                            <div>
                                <button type="button" data-id="{{ $book->carts()->where('user_id', auth()->id())->first()->id }}" class="cart-delete btn btn-outline-danger">Hapus</button>
                            </div>
                        </div>
                    </div>
                    <div class="container-sm mx-sm-3 mt-4">
                        <div class="row">
                            <div class="col-sm-2 mb-4">
                                <img class="zoom-modal-image w-100" src="{{ asset('storage/books/' . $book->image) }}">
                            </div>
                            <div class="col-sm-10">
                                <div class="text-righteous">{{ $book->name }}</div>
                                <div class="form-group w-maxc mt-2">
                                    <div>Pilih Jenis: </div>
                                    <select class="cart-book-version form-control-custom mt-2">
                                        <option disabled selected class="d-none"></option>
                                        <option value="hard_cover">Buku Cetak</option>
                                        @if ($book->ebook == 0)
                                        <option disabled>E-Book</option>

                                        @else
                                        <option value="ebook">E-Book</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="mt-4 d-flex flex-md-column justify-content-md-between">
                                    <div class="d-md-flex">
                                        <div class="mr-4 mb-4">
                                            <div class="tbold">Harga</div>
                                            <div class="cart-book-price text-grey" data-price="{{ $book->price - $book->discount }}">
                                                <span class="{{ $book->discount != 0 ? 'discount-line-through text-danger' : '' }}">{{ rupiah_format($book->price) }}</span>

                                                @if ($book->discount != 0)
                                                <span>{{ rupiah_format($book->price - $book->discount) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mr-4">
                                            <div class="tbold">Jumlah</div>
                                            <div class="text-grey d-flex">
                                                <div class="cart-stock">
                                                    <input type="text" value="{{ $book->carts()->where('user_id', auth()->id())->first()->amount }}" class="cart-amount-req input-none" readonly>
                                                    <span>/</span>
                                                    <input type="text" value="{{ $book->printed_book_stock - $book->carts()->where('user_id', auth()->id())->first()->amount }}" class="cart-total-stock input-none" readonly>
                                                </div>
                                                <div class="cart-amount ml-2">
                                                    <button type="button" class="cart-plus btn-none p-0"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                                                    <button type="button" class="cart-sub btn-none p-0"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mr-4 mb-4">
                                            <div class="tbold">Berat</div>
                                            <div class="text-grey">{{ $book->weight }} gram</div>
                                        </div>
                                    </div>
                                    <div class="cart-note w-maxc">
                                        <div>
                                            <i class="fas fa-pencil-alt mr-1" aria-hidden="true"></i>
                                            <span class="tred-bold">Tulis Catatan</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="mt-4">
                    @include('book_user.status.empty-values', ['text' => 'Anda belum memiliki keranjang belanja'])
                </div>
            @endforelse
        </div>
    </div>
    @csrf
</form>

<div class="mt-5">
    <div class="w-maxc ml-auto">{{ $books->links() }}</div>
</div>

@endsection
