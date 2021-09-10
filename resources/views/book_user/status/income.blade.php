@extends('layouts.app')
@section('content')

<h4>Penghasilan</h4>

<div id="income">
    <div class="d-md-flex mt-4">
        <div class="income-box">
            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <h4 class="hd-14">Hari ini</h4>
                    <a href="{{ route('income.detail.today') }}" class="btn-none tred-bold">Lihat Detail</a>
                </div>
                <div>{{ $now->isoFormat('dddd, D MMMM YYYY') }}</div>
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="text-center">
                        <div class="income-book-amount">{{ $today['count'] }}</div>
                        <div class="tbold">Buku Terjual</div>
                    </div>
                </div>
                <div class="col-8 m-auto text-center">
                    <h4 class="hd-14">Total</h4>
                    <div class="income-money">
                        <i class="fas fa-money-bill"></i>
                        <span>{{ rupiah_format($today['total_payments']) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="income-box">
            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="hd-14">Bulan ini</h4>
                    </div>
                    <a href="{{ route('income.detail.this.month') }}" id="income-this-month" class="btn-none tred-bold">Lihat Detail</a>
                </div>
            </div>
            <div class="row mt-auto">
                <div class="col-4">
                    <div class="text-center">
                        <div class="income-book-amount">{{ $this_month['book_users']->count() }}</div>
                        <div class="tbold">Buku Terjual</div>
                    </div>
                </div>
                <div class="col-8 m-auto text-center">
                    <h4 class="hd-14">Total</h4>
                    <div class="income-money">
                        <i class="fas fa-money-bill"></i>
                        <span>{{ rupiah_format($this_month['total_payments']) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="income-box">
            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <h4 class="hd-14">Semua</h4>
                    <div id="income-all" class="btn-none tred-bold">Lihat Detail</div>
                </div>
            </div>
            <div class="row mt-auto">
                <div class="col-4">
                    <div class="text-center">
                        <div class="income-book-amount">{{ $all['book_users']->count() }}</div>
                        <div class="tbold">Buku Terjual</div>
                    </div>
                </div>
                <div class="col-8 m-auto text-center">
                    <h4 class="hd-14">Total</h4>
                    <div class="income-money">
                        <i class="fas fa-money-bill"></i>
                        <span>{{ rupiah_format($all['total_payments']) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-md-flex mt-4">
        <div class="income-box borbot-gray-bold">
            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <h4 class="hd-14">Cari berdasarkan bulan</h4>
                    <div class="btn-none tred-bold">Lihat Detail</div>
                </div>
                <div class="mt-4">
                    <form method="GET">
                        <input type="month" name="month" class="form-control-custom" value="{{ old('month') }}">
                        <div class="text-right mt-3">
                            <button class="btn btn-red">Cari</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row income-search">
                <div class="col-4">
                    <div class="text-center">
                        <div class="income-book-amount">{{ isset($search) ? $search['count'] : '-' }}</div>
                        <div class="tbold">Buku Terjual</div>
                    </div>
                </div>
                <div class="col-8 m-auto text-center">
                    <h4>Total</h4>
                    <h4>{{ rupiah_format(isset($search) ? $search['total_payments'] : 0) }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
