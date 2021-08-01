@extends('layouts.app')
@section('content')

<h4>Penghasilan</h4>

<div class="d-md-flex mt-4">
    <div class="income-box">
        <div class="mb-4">
            <div class="d-flex justify-content-between">
                <h4 class="hd-14">Hari ini</h4>
                <div class="btn-none tred-bold">Lihat Detail</div>
            </div>
            <div>Kamis, 15 Januari 2022</div>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="text-center">
                    <div class="income-book-amount">10</div>
                    <div class="tbold">Buku Terjual</div>
                </div>
            </div>
            <div class="col-8 m-auto text-center">
                <h4 class="hd-14">Total</h4>
                <div class="income-money">
                    <i class="fas fa-money-bill"></i>
                    <span>Rp2.000.000</span>
                </div>
            </div>
        </div>
    </div>
    <div class="income-box">
        <div class="mb-4">
            <div class="d-flex justify-content-between">
                <h4 class="hd-14">Bulan ini</h4>
                <div class="btn-none tred-bold">Lihat Detail</div>
            </div>
            <div>Kamis, 15 Januari 2022</div>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="text-center">
                    <div class="income-book-amount">10</div>
                    <div class="tbold">Buku Terjual</div>
                </div>
            </div>
            <div class="col-8 m-auto text-center">
                <h4 class="hd-14">Total</h4>
                <div class="income-money">
                    <i class="fas fa-money-bill"></i>
                    <span>Rp2.000.000</span>
                </div>
            </div>
        </div>
    </div>
    <div class="income-box">
        <div class="mb-4">
            <div class="d-flex justify-content-between">
                <h4 class="hd-14">Semua</h4>
                <div class="btn-none tred-bold">Lihat Detail</div>
            </div>
            <div>Kamis, 15 Januari 2022</div>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="text-center">
                    <div class="income-book-amount">10</div>
                    <div class="tbold">Buku Terjual</div>
                </div>
            </div>
            <div class="col-8 m-auto text-center">
                <h4 class="hd-14">Total</h4>
                <div class="income-money">
                    <i class="fas fa-money-bill"></i>
                    <span>Rp2.000.000</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-md-flex mt-4">
    <div class="income-box borbot-gray-bold">
        <div class="mb-4">
            <div class="d-flex justify-content-between">
                <h4 class="hd-14">Lihat berdasarkan bulan</h4>
                <div class="btn-none tred-bold">Lihat Detail</div>
            </div>
            <div class="mt-4">
                <form action="#" method="get">
                    <input type="month" class="form-control-custom">
                    <div class="text-right mt-3">
                        <button class="btn btn-red">Cari</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row income-search">
            <div class="col-4">
                <div class="text-center">
                    <div class="income-book-amount">10</div>
                    <div class="tbold">Buku Terjual</div>
                </div>
            </div>
            <div class="col-8 m-auto text-center">
                <h4 class="hd-14">Total</h4>
                <div class="income-money">
                    <i class="fas fa-money-bill"></i>
                    <span>Rp2.000.000</span>
                </div>
            </div>
        </div>
    </div>
    <div class="income-box borbot-gray-bold">
        <div class="mb-4">
            <div class="d-flex justify-content-between">
                <h4 class="hd-14">Lihat berdasarkan hari</h4>
                <div class="btn-none tred-bold">Lihat Detail</div>
            </div>
            <div class="mt-4">
                <form action="#" method="get">
                    <input type="month" class="form-control-custom">
                    <div class="text-right mt-3">
                        <button class="btn btn-red">Cari</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row income-search">
            <div class="col-4">
                <div class="text-center">
                    <div class="income-book-amount">10</div>
                    <div class="tbold">Buku Terjual</div>
                </div>
            </div>
            <div class="col-8 m-auto text-center">
                <h4 class="hd-14">Total</h4>
                <div class="income-money">
                    <i class="fas fa-money-bill"></i>
                    <span>Rp2.000.000</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
