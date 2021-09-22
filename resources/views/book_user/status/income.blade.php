@extends('layouts.app')
@section('content')

@include('content-header',
array(
'icon_html' => '<i class="user-icon fas fa-chart-pie mr-2"></i>',
'title' => 'Penghasilan',
))

<div class="row d-md-flex flex-md-row-reverse mt-md-4">
    @include('profile-sidebar')
    <div class="col-md-9" id="income">
        <div class="row">
            <div class="col-md-4">
                <div class="income-box">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between">
                            <h5>Hari ini</h5>
                            <a href="{{ route('income.detail.today') }}" class="btn-none tred-bold">Lihat Detail</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="text-center">
                                <h4>{{ $today['count'] }}</h4>
                                <div class="tbold">Terjual</div>
                            </div>
                        </div>
                        <div class="col-8 m-auto text-center">
                            <h5>Total</h5>
                            <div class="income-money">
                                <span>{{ rupiah_format($today['total_payments']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="income-box">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5>Bulan ini</h5>
                            </div>
                            <a href="{{ route('income.detail.this.month') }}" id="income-this-month" class="btn-none tred-bold">Lihat Detail</a>
                        </div>
                    </div>
                    <div class="row mt-auto">
                        <div class="col-4">
                            <div class="text-center">
                                <h4>{{ $this_month['book_users']->count() }}</h4>
                                <div class="tbold">Terjual</div>
                            </div>
                        </div>
                        <div class="col-8 m-auto text-center">
                            <h5>Total</h5>
                            <div class="income-money">
                                <span>{{ rupiah_format($this_month['total_payments']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="income-box">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between">
                            <h5>Semua</h5>
                            <div id="income-all" class="btn-none tred-bold">Lihat Detail</div>
                        </div>
                    </div>
                    <div class="row mt-auto">
                        <div class="col-4">
                            <div class="text-center">
                                <h4>{{ $all['book_users']->count() }}</h4>
                                <div class="tbold">Terjual</div>
                            </div>
                        </div>
                        <div class="col-8 m-auto text-center">
                            <h5>Total</h5>
                            <div class="income-money">
                                <span>{{ rupiah_format($all['total_payments']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-md-flex mt-3">
            <div class="income-box borbot-gray-bold">
                <div class="mb-4">
                    <div class="d-flex justify-content-between">
                        <h5>Cari berdasarkan bulan</h5>
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
                            <div class="tbold">Terjual</div>
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
</div>

<div class="">
</div>

@endsection
