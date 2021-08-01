@extends('layouts.app')
@section('content')

<h4>Menunggu Pembayaran</h4>

<div class="row flex-row-reverse mt-4">
    @include('book_user.status.sidebar', array('waiting_for_payment' => 'active-acc'))
    @include('status.content', array('content_image' => asset('img/monitor.png')))
</div>

@endsection
