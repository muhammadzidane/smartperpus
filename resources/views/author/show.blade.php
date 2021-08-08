@extends('layouts/app')
@section('content')

<div class="borbot-gray-bold pb-2">
    <div class="row">
        <div class="col-1"><img src="{{ asset('img/avatar-icon.png') }}" class="w-100"></div>
        <div class="col-11 pl-1 my-auto">
            <h4 class="hd-14">{{ $author->name }}</h4>
            <div>Jumlah Buku : <span class="text-grey">{{ $author->books->count() }}</span></div>
        </div>
    </div>
</div>
<div class="mt-4">
    @include('layouts.books', array('books' => $paginate))
</div>
<div class="d-flex mt-4">
    <div class="ml-auto">{{ $paginate->links() }}</div>
</div>

@endsection
