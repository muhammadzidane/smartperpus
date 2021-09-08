@extends('layouts.app')
@section('content')

<h4>Penghasilan Bulan Ini</h4>

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
        <div class="white-content d-flex borbot-gray pb-3">
            <div class="row">
                <div class="col-md-2">
                    <div class="row">
                        <div class="col-sm-6 col-md-12 mx-auto mb-lg-0 mb-4">
                            <img class="zoom-modal-image w-100" src="{{ asset('img/books_test_image/detektif-conan-97.jpg') }}" alt="" srcset="">
                        </div>
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-8 col-lg-9">
                            <div>
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Alias atque delectus, placeat doloremque nobis quidem ducimus, culpa laborum rem cum autem quam tenetur incidunt itaque nulla adipisci vitae veniam error?
                            </div>
                            <div class="mt-1 tred-bold">Buku Cetak</div>
                            <div class="text-grey">Rp15.500</div>
                            <div class="text-grey">19x Terjual</div>
                        </div>
                        <div class="col-md-4 col-lg-3 text-grey text-md-right">
                            <div class="tbold">1231231223231</div>
                            <div>Lara Greyrat</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
