@extends('layouts.app')
@section('content')

<h4>Detail Penghasilan Hari Ini</h4>

<div class="row flex-md-row-reverse">
    <div class="col-md-3">
        <div class="white-content">
            <div class="text-grey py-2 pb-3 mb-0 borbot-gray">
                <div class="mb-4">
                    <h4 class="hd-16 text-center text-body">Ringkasan Pembayaran</h4>
                </div>
                <div class="d-flex justify-content-between">
                    <div>Jumlah Barang</div>
                    <input id="cart-amounts" type="text" value="0" class="input-none text-right w-25" readonly>
                </div>
            </div>
            <div class="mt-2 text-grey">
                <div class="d-flex justify-content-between">
                    <div>Total Pembayaran</div>
                    <input id="cart-total-payment" type="text" value="Rp0" class="input-none text-right tred-bold w-50" readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        @forelse ($book_users as $book_user)
        <div class="white-content d-flex borbot-gray pb-3">
            <div class="row">
                <div class="col-md-2">
                    <div class="row">
                        <div class="col-sm-6 col-md-12 mx-auto mb-lg-0 mb-4">
                            <img class="zoom-modal-image w-100" src="{{ asset('img/income.jpg') }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="tbold">1231231223231</div>
                            <div>Lara Greyrat</div>
                            <div class="mt-2">
                                <div>Metode Pembayaran: <span class="text-grey">Transfer Bank BRI</span></div>
                                <div>Kurir: <span class="text-grey">POS Indonesia</span></div>
                                <div>Layanan Kurir: <span class="text-grey">Pos Kilat Buceng</span></div>
                                <div>Jumlah barang: <span class="text-grey">90</span></div>
                                <div>Total: <span class="tred-bold">Rp2.000</span></div>
                            </div>
                        </div>
                        <div class="d-flex flex-column justify-content-between text-right">
                            <div>Kamis, 20 Juli 1945</div>
                            <div class="btn-none tred-bold p-0 m-0">Lihat Detail</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @empty
        @include('./empty-image', array('title' => 'Tidak ada data'))
        @endforelse

        <div class="d-flex justify-content-end mt-4">{{ $book_users->links() }}</div>
    </div>
</div>

@endsection
