@extends('layouts.app')
@section('content')

<h4>Sedang Dikirim</h4>

<div class="row flex-row-reverse mt-4">
    @include('book_user.status.sidebar', array('on_delivery' => 'active-acc'))
    @include('status.content', array('content_image' => asset('img/on-delivery-truck.png')))
</div>

@endsection
