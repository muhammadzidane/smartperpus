@extends('layouts.app')
@section('content')

<h4>Dibatalkan</h4>

<div class="row flex-row-reverse mt-4">
    @include('book_user.status.sidebar', array('failed' => 'active-acc'))
    @include('status.content', array('content_image' => asset('img/gear.png')))
</div>

@endsection
